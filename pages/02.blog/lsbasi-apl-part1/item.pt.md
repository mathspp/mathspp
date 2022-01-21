---
metadata:
    description: Neste artigo vou mostrar-vos como podemos começar a escrever um interpretador
        para a linguagem de programação APL.
title: Vamos construir um interpretador para APL - parte 1
---

Vamos construir um interpretador simples para [APL][apl-wiki]! APL é uma linguagem orientada para vetores (multi-dimensionais). A facilidade com que se pode escrever código relacionado com matemática, as funções primitivas estranhas (por exemplo `⍴`, `⍨`, `⍒` ou `⍣`) e o facto de APL ser executado da direita para a esquerda fazem com que seja uma experiência de programação interessante!

===

![Um boneco a preto e branco com uma pessoa à frente do computador.](./lsbasi-apl-part1-bg.png)

# Preâmbulo

Antes de mais nada gostava de dar crédito ao Ruslan Spivak e à sua série de artigos [_Let's build a simple interpreter_][lsbasi] onde ele constrói um interpretador para Pascal. A primeira vez que comecei a série de artigos acabei por [criar a linguagem de programação Roj][roj-blog], uma linguagem simplecíssima sem qualquer utilidade no mundo real. Na altura a série teria uns oito ou nove artigos. Hoje vamos começar a trabalhar num interpretador para APL e vou basear-me vagamente nessa mesma série de artigos... Com a particularidade de que APL e Pascal são bastante diferentes, portanto vou precisar de fazer algumas adaptações.

A série que estou agora a começar tem como objetivos:
 - ajudar-me a aprender APL;
 - treinar as minhas capacidades de programação em Python;
 - documentar o meu processo de aprendizagem para escrever o interpretador;
 - ajudar-te a _ti_ a escreveres o teu próprio interpretador de APL caso o queiras fazer!

Para aqueles que conhecem a série LSBASI original, a numeração da minha série não vai ter muito a ver com a numeração original. Isto porque, tal como disse, a série original vai servir apenas como base para me orientar e não vai haver uma correspondência 1-para-1 entre os meus artigos e os do Ruslan Spivak.

O que vou escrever hoje corresponde sensivelmente a tudo o que a série original cobre até meio do [oitavo artigo][lsbasi-part8].

## O código

[![](https://img.shields.io/github/stars/RojerGS/RGSPL?style=social)](https://github.com/RodrigoGiraoSerrao/RGSPL)&nbsp;&nbsp;&nbsp;[![run it on Repl.it!](https://repl.it/badge/github/RojerGS/RGSPL)](https://RGSPLpart1.rojergs.repl.run)

O código deste projeto está disponível [neste repositório do GitHub][rgspl-repo], estás à vontade para seguir o repositório. O código que diz respeito a este primeiro artigo da série é o ficheiro [`rgspl1.py`][rgspl1], faz o download do ficheiro para poderes testar o código no teu computador. Também podes testar o código online no teu navegador, basta carregares no botão "run on repl.it" aqui em cima.


# Objetivo a longo prazo

Esta série de artigos vai acompanhar a minha jornada de escrever um interpretador para APL. No fim da série tu e eu teremos um interpretador para APL escrito em Python! Vai dar bastante trabalho ;)


# Objetivo para hoje

No artigo de hoje vamos tratar de programar o mínimo essencial para que o interpretador faça _alguma_ coisa; vamos criar um programa que consegue analisar expressões APL simples com:

 1. números inteiros e decimais (positivos e negativos - em APL [usamos `¯` para indicar um número negativo][apl-wiki-high-minus], e.g. `¯3` é $-3$) e vetores desses números;
 2. utilizações monádicas e diádicas das primitivas `+-×÷`;
 3. o operador `⍨`;
 4. expressões com parêntesis;

!!! Não tenho formação formal nestes assuntos e estou a ter dificuldades em determinar quais são os termos da literatura para as várias coisas que vou descrever... se conheceres os termos corretos, diz-me!

# Estrutura das expressões - símbolos

A primeira coisa a fazer é ler uma expressão de código APL e determinar onde estão - e quais são - os vários símbolos relevantes. Por exemplo, queremos procurar números e tentar perceber se esses são números inteiros ou decimais. Também vamos querer procurar as funções/os operadors primitivos de APL e associá-los com os seus nomes.

Para isto criamos a classe `Token` que identifica os vários tipos de símbolos que queremos extrair das expressões:

```py
class Token:
    """Represents a token parsed from the source code."""

    INTEGER = "INTEGER"
    FLOAT = "FLOAT"
    PLUS = "PLUS"
    MINUS = "MINUS"
    TIMES = "TIMES"
    DIVIDE = "DIVIDE"
    NEGATE = "NEGATE"
    COMMUTE = "COMMUTE"
    LPARENS = "LPARENS"
    RPARENS = "RPARENS"
    EOF = "EOF"

    # Helpful lists of token types.
    FUNCTIONS = [PLUS, MINUS, TIMES, DIVIDE]
    MONADIC_OPS = [COMMUTE]

    # What You See Is What You Get characters that correspond to tokens.
    WYSIWYG = "+-×÷()⍨"
    # The mapping from characteres to token types.
    mapping = {
        "+": PLUS,
        "-": MINUS,
        "×": TIMES,
        "÷": DIVIDE,
        "(": LPARENS,
        ")": RPARENS,
        "⍨": COMMUTE,
    }

    def __init__(self, type_, value):
        self.type = type_
        self.value = value

    def __str__(self):
        return f"Token({self.type}, {self.value})"

    def __repr__(self):
        return self.__str__()
```

Depois de definir estes símbolos e de definirmos os métodos `__str__` e `__repr__` (que permitem que os `print`s sejam mais úteis) precisamos de ser capazes de converter o texto `5 + 6` para uma lista de símbolos como `[Token(EOF, None), Token(INTEGER, 5), Token(PLUS, +), Token(INTEGER, 6)]`.

Repara que o símbolo `EOF` (end-of-file) é o primeiro da lista. Isto porque APL interpreta expressões da direita para a esquerda, portanto o fim da expressão está à esquerda e vamos imitar isto na representação dos símbolos em lista. Espero não me vir a arrepender desta decisão mais tarde...

Já agora, este parece-me um momento adequado para te dizer que, obviamente, eu cometo erros! Bastantes! Se a certo ponto achares que tomei uma má decisão, _por favor_ faz como achares que devia ser feito e depois diz-me nos comentários o que conseguiste descobrir!

De volta ao nosso programa, agora que temos a classe `Token`, vamos definir uma classe `Tokenizer` que vai pegar numa expressão APL em texto e convertê-la numa lista de símbolos relevantes:

```py
class Tokenizer:
    """Class that tokenizes source code into tokens."""

    def __init__(self, code):
        self.code = code
        self.pos = len(self.code) - 1
        self.current_char = self.code[self.pos]

    def error(self, message):
        """Raises a Tokenizer error."""
        raise Exception(f"TokenizerError: {message}")

    def advance(self):
        """Advances the cursor position and sets the current character."""

        self.pos -= 1
        self.current_char = None if self.pos < 0 else self.code[self.pos]

    # ...
```

Para criarmos um objeto do tipo `Tokenizer` basta passar-lhe a expressão APL, por exemplo com `Tokenizer("5 + 6")`. A função `error` serve apenas para indicar erros com o processamento das expressões APL e a função `advance` é outra pequena função utilitária para que seja mais fácil "atravessar" o código APL todo e sabermos quando chegámos ao fim. Quando `current_char` for `None` é porque acabámos de processar o código APL.

Com estes métodos definidos, podemos agora criar o resto da classe:

```py
class Tokenizer:

    # ...

    def skip_whitespace(self):
        """Skips all the whitespace in the source code."""

        while self.current_char and self.current_char in " \t":
            self.advance()

    def get_integer(self):
        """Parses an integer from the source code."""

        end_idx = self.pos
        while self.current_char and self.current_char.isdigit():
            self.advance()
        return self.code[self.pos+1:end_idx+1]

    def get_number_token(self):
        """Parses a number token from the source code."""

        parts = [self.get_integer()]
        # Check if we have a decimal number here.
        if self.current_char == ".":
            self.advance()
            parts.append(".")
            parts.append(self.get_integer())
        # Check for a negation of the number.
        if self.current_char == "¯":
            self.advance()
            parts.append("-")

        num = "".join(parts[::-1])
        if "." in num:
            return Token(Token.FLOAT, float(num))
        else:
            return Token(Token.INTEGER, int(num))

    def get_wysiwyg_token(self):
        """Retrieves a WYSIWYG token."""

        char = self.current_char
        if char in Token.mapping:
            self.advance()
            return Token(Token.mapping[char], char)

        self.error("Could not parse WYSIWYG token.")

    def get_next_token(self):
        """Finds the next token in the source code."""

        self.skip_whitespace()
        if not self.current_char:
            return Token(Token.EOF, None)

        if self.current_char in "0123456789":
            return self.get_number_token()

        if self.current_char in Token.WYSIWYG:
            return self.get_wysiwyg_token()

        self.error("Could not parse the next token...")

    def tokenize(self):
        """Returns the whole token list."""

        tokens = [self.get_next_token()]
        while tokens[-1].type != Token.EOF:
            tokens.append(self.get_next_token())
        return tokens[::-1]
```

Com o código aqui em cima a expressão `5 -⍨ ¯2.3` é transformada na lista `[Token(EOF, None), Token(INTEGER, 5), Token(MINUS, -), Token(COMMUTE, ⍨), Token(FLOAT, -2.3)]` se corrermos o código `print(Tokenizer("5 -⍨ ¯2.3").tokenize())`. Se não acreditas, tudo bem! Vê por ti mesmo, copia

`5 -⍨ ¯2.3`

e cola [nesta linha de comandos](https://RGSPLpart1.rojergs.repl.run).

# Reestruturar a lista de símbolos

Agora que já temos uma lista de símbolos queremos reestruturá-la de modo a que seja mais fácil interpretá-la. Para isso, vamos construir uma Árvore de Sintaxe Abstrata (lê o [sétimo artigo][lsbasi-part7] da série de artigos do Ruslan Spivak, Abstract Syntax Tree em inglês).

A hierarquia implícita na AST vai facilitar bastante a interpretação de programas APL. O preço a pagar está em construir a AST a partir da lista de `Token`s. Temos de atravessar a lista e descobrir onde estão os [escalares][apl-wiki-scalar], os [vetores multi-dimensionais][apl-wiki-array], os[operadores][apl-wiki-operator] e as funções [monádicas][apl-wiki-monad]/[diádicas][apl-wiki-dyad]. Depois disso, interpretar um programa torna-se bastante fácil.

Para saber _como_ construir a AST comecei por escrever a [_gramática_][bnf-wiki] que especifica que expressões de APL é que o programa vai ser interpretar. Esta _gramática_ é só uma ferramenta que nos dá uma notação para podermos especificar que sequências de símbolos é que fazem sentido e o que é que querem dizer.

Confesso que tive dificuldades em escrever a primeira versão da gramática que queria saber interpretar. Depois de bater bastante com a cabeça na parede,

![A black and white doodle of a head against a fissured wall](./lsbasi-apl-part1-cracked-wall.png)

depois de pensar bastante, de rascunhar bastante e de pedir ajuda a um conjunto bastante amigável de pessoas no [APL Orchard][apl-orchard], escrevi esta gramática que deve ser lida da direita para a esquerda:

```py
PROGRAM := EOF STATEMENT
STATEMENT := ( ARRAY FUNCTION | FUNCTION )* ARRAY
ARRAY := ( "(" STATEMENT ")" | SCALAR )+
SCALAR := INTEGER | FLOAT
FUNCTION := F | FUNCTION "⍨"
F := "+" | "-" | "×" | "÷"
```

Cada linha representa uma regra que permite reconhecer um pedaço de APL, e cada regra pode depender de outras regras, até que chegamos a regras mais básicas como as regras `F` e `SCALAR`, cuja verificação se limita a olhar para a lista de símbolos e ver se temos em mãos o símbolo certo.

A gramática em cima pode ser lida mais ou menos desta maneira (vou deixar em inglês e em itálico as referências às regras da gramática):

 1. um _program_ é um _statement_ seguido pelo símbolo `EOF`;
 2. um _statement_ é um _array_ seguido de 0 ou mais ocorrências de, ou uma única _function_ (função monádica), ou de uma _function_ e de outro _array_ (neste caso obtemos uma função diádica);
 3. um _array_ é composto por 1 ou mais _scalar_/_statement_ dentro de parêntesis;
 4. um _scalar_ ou é um símbolo de um número inteiro ou de um número decimal;
 5. uma _function_ é o operador `⍨` seguido de uma _function_ ou então é apenas um _f_;
 6. um _f_ é qualquer uma das primitivas que enumerámos no início: `+-×÷`.

Repara que há regras que fazem referência a regras anteriores, que estão mais acima na hierarquia; estas referências recursivas permitem que possamos reconhecer expressões muito mais interessantes mas também fazem com que criar a AST seja ligeiramente mais difícil.

Agora precisamos de usar a gramática para construir uma AST. Primeiro, olhamos para as várias regras e decidimos que tipos de nós é que a AST vai ter... De momento, precisamos de nós para os [escalares][apl-wiki-scalar], os [vetores multi-dimensionais][apl-wiki-array], as [funções diádicas][apl-wiki-dyad] e [monádicas][apl-wiki-monad] e para os [operadores][apl-wiki-operator]:

```py
class ASTNode:
    """Stub class to be inherited by the different types of AST nodes.

    The AST Nodes are used by the Parser instances to build an
        Abstract Syntax Tree out of the APL programs.
    These ASTs can then be traversed to interpret an APL program.
    """

class Scalar(ASTNode):
    """Node for a simple scalar like 3 or ¯4.2"""
    def __init__(self, token):
        self.token = token
        self.value = self.token.value

    def __str__(self):
        return f"S({self.value})"

    def __repr__(self):
        return self.__str__()

class Array(ASTNode):
    """Node for an array of simple scalars, like 3 ¯4 5.6"""
    def __init__(self, children):
        self.children = children

    def __str__(self):
        return f"A({self.children})"

    def __repr__(self):
        return self.__str__()

class MOp(ASTNode):
    """Node for monadic operators like ⍨"""
    def __init__(self, token, child):
        self.token = token
        self.child = child

    def __str__(self):
        return f"MOp({self.token.value} {self.child})"

    def __repr__(self):
        return self.__str__()

class Monad(ASTNode):
    """Node for monadic functions."""
    def __init__(self, token, child):
        self.token = token
        self.child = child

    def __str__(self):
        return f"Monad({self.token.value} {self.child})"

    def __repr__(self):
        return self.__str__()

class Dyad(ASTNode):
    """Node for dyadic functions."""
    def __init__(self, token, left, right):
        self.token = token
        self.left = left
        self.right = right

    def __str__(self):
        return f"Dyad({self.token.value} {self.left} {self.right})"

    def __repr__(self):
        return self.__str__()
```

Agora que já sabemos que tipos de nós é que vamos ter, podemos definir a classe `Parser` que recebe um `Tokenizer` e define vários métodos para transformar uma lista de símbolos numa AST.

O começo da classe é:

```py
class Parser:
    """Implements a parser for a subset of the APL language.

    The grammar parsed is available at the module-level docstring.
    """

    def __init__(self, tokenizer, debug=False):
        self.tokens = tokenizer.tokenize()
        self.pos = len(self.tokens) - 1
        self.token_at = self.tokens[self.pos]
        self.debug_on = debug

    def debug(self, message):
        """If the debugging option is on, print a message."""
        if self.debug_on:
            print(f"PD @ {message}")

    def error(self, message):
        """Throws a Parser-specific error message."""
        raise Exception(f"Parser: {message}")

    def eat(self, token_type):
        """Checks if the current token matches the expected token type."""

        if self.token_at.type != token_type:
            self.error(f"Expected {token_type} and got {self.token_at.type}.")
        else:
            self.pos -= 1
            self.token_at = None if self.pos < 0 else self.tokens[self.pos]

    def peek(self):
        """Returns the next token type without consuming it."""
        peek_at = self.pos - 1
        return None if peek_at < 0 else self.tokens[peek_at].type
```

Instâncias da classe `Parser` recebem um argumento `debug` para eu poder ligar/desligar mensagens auxiliares para poder depurar o código. Tal como na classe `Tokenizer` temos um pequeno método `error` para quando houver algum erro na análise do código.

Também defini métodos `eat` e `peek`. O método `eat` serve para quando estamos num dado ponto da análise em que _devíamos_ estar a olhar para um certo símbolo e queremos continuar a análise. Nesse ponto, "comemos" esse símbolo e se ele não corresponder ao símbolo de que estávamos à espera, temos um erro. Se tudo estiver bem, continuamos a fazer a análise do código.

A título de exemplo, considera a regra `SCALAR := INTEGER | FLOAT`. Se estivermos dentro desta regra e se soubermos que o próximo símbolo não é um número inteiro então é porque devia ser um número decimal. Por isso, podemos simplesmente "comer" um símbolo `FLOAT`. Se houver um erro, é porque o símbolo não correspondia ao que a gramática especifica e portanto não sabemos analisar esse código APL (possivelmente porque o código estava mal escrito).

O método `peek` serve, literalmente, para espreitarmos para o símbolo que virá a seguir. Precisamos disto em situações ambíguas, em que não chega olhar para um símbolo para sabermos que regra da gramática é que vamos analisar a seguir.

Por exemplo, quando estamos a olhar para um `+` não temos maneira de saber se é uma função monádica ou diádica sem olhar para o que vem a seguir para ver se temos um vetor multi-dimensional ou qualquer outra coisa.

Depois de definirmos estas funções todas vamos agora definir métodos `parse_*`, um para cada regra da gramática. Cada método vai devolver um nó da AST com o pedaço do código que foi analisado com essa regra:

```py
class Parser:

    # ...

    def parse_program(self):
        """Parses a full program."""

        self.debug(f"Parsing program from {self.tokens}")
        node = self.parse_statement()
        self.eat(Token.EOF)
        return node

    def parse_statement(self):
        """Parses a statement."""

        self.debug(f"Parsing statement from {self.tokens[:self.pos+1]}")
        node = self.parse_array()
        while self.token_at.type in Token.FUNCTIONS + Token.MONADIC_OPS:
            # pylint: disable=attribute-defined-outside-init
            func, base = self.parse_function()
            if isinstance(base, Dyad):
                base.right = node
                base.left = self.parse_array()
            elif isinstance(base, Monad):
                base.child = node
            else:
                self.error(f"Got {type(base)} instead of a Monad/Dyad.")
            node = func
        return node

    def parse_array(self):
        """Parses an array composed of possibly several simple scalars."""

        self.debug(f"Parsing array from {self.tokens[:self.pos+1]}")
        nodes = []
        while self.token_at.type in [Token.RPARENS, Token.INTEGER, Token.FLOAT]:
            if self.token_at.type == Token.RPARENS:
                self.eat(Token.RPARENS)
                nodes.append(self.parse_statement())
                self.eat(Token.LPARENS)
            else:
                nodes.append(self.parse_scalar())
        nodes = nodes[::-1]
        if not nodes:
            self.error("Failed to parse scalars inside an array.")
        elif len(nodes) == 1:
            node = nodes[0]
        else:
            node = Array(nodes)
        return node

    def parse_scalar(self):
        """Parses a simple scalar."""

        self.debug(f"Parsing scalar from {self.tokens[:self.pos+1]}")
        if self.token_at.type == Token.INTEGER:
            node = Scalar(self.token_at)
            self.eat(Token.INTEGER)
        else:
            node = Scalar(self.token_at)
            self.eat(Token.FLOAT)
        return node

    def parse_function(self):
        """Parses a function possibly monadically operated upon."""

        self.debug(f"Parsing function from {self.tokens[:self.pos+1]}")
        if self.token_at.type in Token.MONADIC_OPS:
            node = MOp(self.token_at, None)
            self.eat(self.token_at.type)
            node.child, base = self.parse_function()
        else:
            base = node = self.parse_f()
        return node, base

    def parse_f(self):
        """Parses a simple one-character function.

        We have to peek forward to decide if the function is monadic or dyadic.
        """

        self.debug(f"Parsing f from {self.tokens[:self.pos+1]}")
        if self.peek() in [Token.RPARENS, Token.INTEGER, Token.FLOAT]:
            node = Dyad(self.token_at, None, None)
        else:
            node = Monad(self.token_at, None)
        self.eat(node.token.type)
        return node

    def parse(self):
        """Parses the whole AST."""
        return self.parse_program()
```

Com estes métodos podemos finalmente analisar um programa APL simples e transformá-lo numa AST. No próximo artigo vamos ver como pegar nessa AST e interpretá-la. Até lá, podes testar este código no teu navegador:

[![run it on Repl.it!](https://repl.it/badge/github/RojerGS/RGSPL)](https://RGSPLpart1.rojergs.repl.run)

Com o link de cima podes ver que a expressão `×⍨ 4.5 - (4 ¯3 5.6)` é analisada e transformada em `MOp(⍨ Monad(× Dyad(- S(4.5) A([S(4), S(-3), S(5.6)]))))` (`A` para nós com vetores multi-dimensionais e `S` para escalares).

# Para o próximo artigo

No próximo artigo desta sério, vamos

 - implementar o padrão de design chamado [_"Visitor pattern"_][visitor-wiki] que vamos usar para interpretar as ASTs. Podes ler a segunda metade do [oitavo artigo da série LSBASI][lsbasi-part8] para ver como o Ruslan Spivak fez;
 - permitir que o código que sabemos interpretar use definições de variáveis;
 - permitir várias expressões separadas por `⋄`.

# Exercícios

Para praticares as tuas capacidades de programador e para garantir que percebeste mesmo o que se está a passar, sugiro que tentes modificar as classes `Tokenizer` e `Parser` para também aceitarem expressões com as funções `⌈` e `⌊`.

Se tiveres coragem suficiente, também podes tentar implementar as alterações que eu disse que vão ser feitas no próximo artigo!

Até à próxima!

##### Todos os artigos desta série:

<ul>
{% for post in taxonomy.findTaxonomy({"tag": ["lsbasi-apl"]}) %}
    <li><a href="{{ post.url }}">{{ post.title }}</a></li>
{% endfor %}
</ul>

[apl-wiki]: https://aplwiki.com/
[apl-wiki-scalar]: https://aplwiki.com/wiki/Scalar
[apl-wiki-array]: https://aplwiki.com/wiki/Array
[apl-wiki-operator]: https://aplwiki.com/wiki/Operator
[apl-wiki-dyad]: https://aplwiki.com/wiki/Dyadic_function
[apl-wiki-monad]: https://aplwiki.com/wiki/Monadic_function
[apl-wiki-high-minus]: https://aplwiki.com/wiki/High_minus
[lsbasi]: https://ruslanspivak.com/lsbasi-part1/
[lsbasi-part8]: https://ruslanspivak.com/lsbasi-part8/
[lsbasi-part7]: https://ruslanspivak.com/lsbasi-part7/
[roj-blog]: https://mathspp.com/blog/creating-programming-language-from-scratch
[apl-orchard]: https://chat.stackexchange.com/rooms/52405/the-apl-orchard
[rgspl-repo]: https://github.com/RodrigoGiraoSerrao/RGSPL
[rgspl1]: https://github.com/RodrigoGiraoSerrao/RGSPL/releases/v0.1
[bnf-wiki]: https://en.wikipedia.org/wiki/Backus%E2%80%93Naur_form
[visitor-wiki]: https://en.wikipedia.org/wiki/Visitor_pattern
