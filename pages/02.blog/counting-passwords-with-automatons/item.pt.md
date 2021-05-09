---
metadata:
    description: "Neste artigo vamos usar autómatos finitos para contar 698,438,863,898,480,640 passwords num par de milissegundos."
show_call_to_action: false
title: "Contar passwords com autómatos"
---

Neste artigo vamos usar autómatos finitos para contar
698,438,863,898,480,640 passwords num par de milissegundos.

===

 > Muito obrigado à Inês Guimarães, que [traduziu este artigo para português][traducao-gh].

# Contar é fácil

Algures durante a nossa aprendizagem matemática, foram-nos colocadas as mais variadas questões de contagem, do tipo

 > “_Se tens 5 pares de calças e 6 t-shirts, quantas combinações de roupa diferentes consegues obter?_”

Estas questões são questões _combinatórias_, sendo que a combinatória é o ramo da matemática que se ocupa da contagem de objetos. 
(Esta é uma definição redutora, mas vamos aceitá-la por enquanto.)

As perguntas desta natureza vão-se tornando progressivamente mais complicadas à medida que a nossa educação matemática avança, e aprendemos até alguns métodos que nos permitem responder a estas questões mais complexas.

Combinatória nunca foi o meu forte, logo, para muitos problemas de combinatória, eu ia escrevendo pequenos programas de computador que faziam as contagens por mim.
Na maior parte dos casos, eu aplicava o método mais rudimentar de todos: 
pedir ao computador para listar _todas as alternativas possíveis_ e contá-las.

Esta abordagem funcionava em problemas simples como o que exibimos acima, e.g.:

```py
>>> jeans = ["blue 1", "blue 2", "blue 3", "light", "dark"]
>>> tshirts = ["red", "green", "white", "pink", "yellow", "brown"]
>>> count = 0
>>> for j in jeans:
...     for t in tshirts:
...         print(j, t)
...         count += 1
...  
blue 1 red
blue 1 green
# ...
dark brown
>>> count
30
```

Contudo, tal estratégia falha redondamente quando somos confrontados com problemas mais complexos, como

 > "_Quantas passwords com comprimento entre 8 e 10 (inclusive) existem,
 se uma password tiver de conter pelo menos uma letra minúscula, uma letra maiúscula e um algarismo?_"


# Contar é difícil

Bem, toda a gente sabe que a resposta à pergunta anterior é 698,438,863,898,480,640, mas como podemos instruir um computador para contar estas possibilidades?
(Para chegar a esta resposta, assumimos que vão ser utilizados os 26 caracteres latinos, maiúsculos e minúsculos, e os 10 dígitos arábicos entre zero e nove.)

Podemos pedir ao computador para gerar todas as combinações possíveis de letras/dígitos e depois apenas contar aquelas que constituem passwords válidas.
Isto funcionaria, certo?
Sim, funcionaria, mas o programa apenas iria devolver uma resposta passados
[pouco mais de 27 anos](https://www.wolframalpha.com/input/?i=%2862%5E8+%2B+62%5E9+%2B+62%5E10%29%2F%281000000000%29+seconds+to+years)
_SE_ o nosso computador conseguisse listar mil milhões de combinações por segundo,
algo que ultrapassa a capacidade da maior parte dos computadores domésticos (senão de todos!). Isto mostra que estes métodos mais rudimentares não fornecem uma boa solução, pelo que temos de executar uma abordagem mais inteligente.

O próximo passo no teu processo de pensamento poderá ser o mesmo que o meu:
ok, em vez de gerarmos _todas_ as combinações possíveis, vamos simplesmente
gerar _todas_ as passwords.
Ok, isso de facto acelera o processo, e agora tínhamos de esperar
[apenas 22 anos](https://www.wolframalpha.com/input/?i=698438863898480640%2F1000000000+seconds+to+years)
por uma resposta, o que significa que poupamos 5 anos! 
Bom trabalho!
Ainda assim, devo mencionar que, de modo a pouparmos estes 5 anos, teríamos de escrever um programa de computador que seria mais complexo, porque agora não podíamos simplesmente listar todas as combinações de caracteres.


# Princípio da inclusão-exclusão

Se nós tivéssemos efetivamente de resolver este problema em específico, ou outro semelhante, com lápis e papel, provavelmente recorreríamos ao [princípio da inclusão-exclusão][wiki-iep], que é uma técnica que nos ajuda a resolver problemas de contagem.

Lamentavelmente, como já disse, combinatória nunca foi o meu forte. Além disso, pode ser bastante chato aplicar o princípio da inclusão-exclusão, uma vez que precisamos de calcular e manusear muitos valores intermédios. Nós _podíamos_ implementar o princípio da inclusão-exclusão mas, sendo _completamente sincero com vocês_, eu nem sequer considerei essa possibilidade até começar a escrever este artigo, algo que só aconteceu _após_ eu ter escrito o programa que utiliza autómatos.

De qualquer modo, o princípio da inclusão-exclusão torna-se muito feio muito rapidamente, e acredito que resultaria num programa menos elegante, ou menos flexível.


# Máquinas de estados finitas

Se não vamos implementar o princípio da inclusão-exclusão, então o que vamos nós fazer? Autómatos chamados à receção!
Um autómato não é nada mais do que um modelo matemático de uma máquina que segue um conjunto pré-determinado de instruções automaticamente. Por estarmos a lidar com um número finito de passos e operações, temos em mãos um autómato finito, também apelidado por máquina de estados finita.

Presta atenção por um segundo, enquanto esboço o nosso plano de uma forma abstrata:
vamos construir um autómato que constrói passwords válidas e, depois, iremos utilizá-lo para contar quantas passwords existem sem ter de gerar concretamente todas essas passwords.

Para tornar o processo mais prático e visual, vamos pensar num autómato como um desenho com círculos e setas entre os círculos. Cada bolinha representa um estado e as setas representam as ações que podemos tomar para nos movermos de um estado para outro. No nosso exemplo de construção de passwords, uma parte do nosso autómato pode ser representada assim:

![](_automaton.svg "Parte de um autómato que gera passwords.")

Cada círculo representa uma porção de uma password que estamos a construir e cada seta representa um tipo de caracter que podemos acrescentar a essa password. A imagem acima mostra que temos uma password já com dois caracteres: "he". As três setas apontam para "heL", "he1" e "hel", com rótulos "maiúscula", "algarismo" e "minúscula", respetivamente, indicando assim o tipo de caracter que acabámos de acrescentar à nossa password.


## Generalizando os estados

Isto é semelhante àquilo que pretendemos construir, tirando o facto de não querermos que os círculos reflitam exatamente os caracteres utilizados até ao momento. Queremos fabricar um autómato mais geral. Se tivéssemos um círculo para cada segmento inicial de uma password, estaríamos numa situação ainda pior do que aquela que apresentámos inicialmente. Não, aquilo que queremos é apenas registar _quantos caracteres de cada tipo_ foram utilizados até ao momento. 

No nosso caso, cada estado do autómato refletirá apenas o número de letras minúsculas, letras maiúsculas e dígitos empregues até então. Com esta abordagem, podemos abstrair as passwords específicas que estamos a gerar e focar-nos apenas na estrutura da password, que é aquilo que realmente importa. Assim sendo, vamos representar os estados do nosso autómato como triplos, contendo

 1. o número de letras minúsculas utilizadas;
 2. o número de letras maiúsculas utilizadas; e
 3. o número de algarismos utilizados.

Recordando a última figura, aqui está a versão atualizada:

![](_triples_automaton.svg "Autómato anterior, usando triplos abstratos em vez de passwords específicas.")

Com esta mudança, o nosso autómato tem agora a capacidade de representar muitas mais passwords, porque o canto inferior esquerdo representa _qualquer_ sequência que contém duas letras minúsculas, por exemplo "ab", "fo" ou "zz", e também "he", que foi o estado específico que se encontrava no autómato anterior.

A ideia é que podemos considerar esta representação genérica e expandi-la, de modo a expressar todo o processo de construção de uma password como uma sequência de operações, em que cada "operação" é "acrescentar um caracter de um tipo específico". Se adicionarmos mais alguns passos à figura anterior, poderíamos obter algo deste género:

![](_triples_automaton_larger.svg "Uma versão expandida do autómato anterior, com mais estados.")

A azul claro e a branco, temos todos os estados que podemos atingir em dois passos, assumindo que começamos no estado que está no canto inferior esquerdo da figura. Os estados representados com menos opacidade são alguns dos que surgiriam em etapas posteriores.


## Comprimento de uma password

É importante observarmos que, nesta nova representação dos estados, para determinarmos o comprimento da password construída até ao momento, apenas temos de somar todos os números que constam no triplo. Por exemplo, o estado $(2, 3, 4)$ representa passwords com comprimento $2+3+4 = 9$, que contêm 2 letras minúsculas, 3 letras maiúsculas e 4 dígitos.


## Contar caminhos

Se os estados representam a estrutura das passwords, se as setas representam a ação de "acrescentar um caracter", e se o que queremos é contar quantas passwords existem, então basta contarmos quantas passwords é que o autómato consegue gerar. Por outras palavras, apenas temos de percorrer o autómato, seguindo as setas, e contar de quantas formas podemos ir de certos estados até outros.

Por exemplo, se começarmos com duas letras minúsculas, de quantas formas podemos completar a nossa password de modo a que, no final, ela possua duas letras minúsculas, uma letra maiúscula e um algarismo? Por outras palavras, de quantas maneiras diferentes podemos ir do estado $(2, 0, 0)$ ao estado $(2, 1, 1)$ no nosso autómato?

A resposta corresponde a percorrer dois caminhos distintos no autómato:

![](_walking_automaton.svg "Destaque dos caminhos entre $(2, 0, 0)$ e $(2, 1, 1)$.")

Para nos deslocarmos de $(2, 0, 0)$ a $(2, 1, 1)$, podemos fazer uma de duas coisas:

 1. podemos primeiro acrescentar uma letra maiúscula (i.e., ir para $(2, 1, 0)$) e depois um dígito; ou
 2. podemos primeiro acrescentar um dígito (i.e., ir para $(2, 0, 1)$) e depois uma letra maiúscula.

Se gerarmos esta estrutura com algum código, podemos depois recorrer a um algoritmo simples para percorrer o autómato e determinar quantos caminhos existem para fabricar uma password.


## Multiplicadores de caminhos

Agora, percorrer o autómato apenas nos diz de quantas formas podemos acrescentar diferentes tipos de caracteres à nossa password. Por exemplo, já vimos que para ir de uma password com duas letras minúsculas para uma password que adicionalmente possui um dígito e uma letra maiúscula, podemos tomar dois caminhos:
`du` e `ud`, o que significa que podemos primeiro acrescentar um algarismo e depois uma letra maiúscula ou inverter este processo.

! Vamos usar `u` para representar uma qualquer letra maiúscula, `l` para letras minúsculas e `d` para dígitos.

No entanto, para contarmos o número _total_ de passwords, queremos mais informação do que apenas o número de formas para construir a estrutura da password. Sim, parece relevante saber que `ddul`, `duld`e `ludd` são algumas das maneiras de obter passwords com dois dígitos e uma letra de cada tipo, mas quantas passwords com essa estrutura existem? `ddul` engloba passwords como "35Tv" ou "10Xz", mas quantas dessas existem?

Tal como vamos verificar, uma vez conhecida a estrutura de uma password e, em particular, a partir do momento em que sabemos a ordem pela qual os tipos de caracteres surgem, torna-se _muito_ fácil efetuar a contagem desejada. De facto, porque é que não tentamos determinar quantas passwords existem com estrutura `ddul`?
Bem, temos 10 algarismos, 26 letras minúsculas e 26 letras maiúsculas, pelo que a resposta é

$$
\texttt{ddul} \rightarrow 10 \times 10 \times 26 \times 26 = 67600 ~~~.
$$

Podemos encarar este processo como a associação de um multiplicador a cada seta: cada seta é associada a um _tipo_ de caracter, e a quantidade de caracteres desse tipo é algo que sabemos a priori, e.g., sabemos que há 10 dígitos. Deste modo, podemos emparelhar cada aresta com o seu multiplicador e contabilizar todos esses multiplicadores quando percorremos o autómato. Isto permirtir-nos-á contar o número total de passwords enquanto percorremos o autómato, ao invés de termos de recolher toda a informação estrutural para efetuar os cálculos posteriormente.


## Estados terminais

Existe apenas uma última coisa que precisamos de ter em consideração antes de efetivamente implementarmos este algoritmo, que é a noção de _estado terminal_. 
Os _estados terminais_ são os estados do autómato que representam o término de uma sequência de operações. Se estamos a construir uma password com comprimento 10, por exemplo, o autómato iria parar em estados como $(10, 0, 0)$ ou $(5, 5, 0)$, porque estes representam passwords de comprimento 10 e não pretendemos acrescentar mais caracteres.

Para o nosso objetivo, porém, pretendemos algo ligeiramente diferente. Teoricamente, os estados terminais são tais que o autómato _deveria_ parar ao alcançá-los. Mas para contar passwords válidas, vamos antes considerar estados em que o autómato _poderia_ parar.
O objetivo desta abordagem é capturar o facto de que passwords mais curtas podem ser estendidas a passwords mais longas que permanecem válidas: isto é, tanto os estados destas passwords mais curtas como das passwords estendidas são relevantes ao contar passwords, logo queremos considerar todos estes estados terminais.

Isto acaba por ser equivalente à definição formal, mas é mais fácil para nós trabalhar neste contexto, logo não estamos a fazer assim _tanta_ batota.


# Implementação

!!! Todo o código encontra-se disponível [neste repositório do GitHub][gh-repo].

Agora vem a parte divertida, que é traduzir todas estas ideias em código. Se quiseres fazê-lo por ti próprio, pára de ler agora e atira-te de cabeça. Caso as minhas explicações tenham sido suficientemente claras, deves ser capaz de escrever o programa sozinho. Se algo não ficou claro, sente-te à vontade para pedir clarificações na secção dos comentários.


## Separação em dois programas

Se precisares de um empurrãozinho extra, deixo aqui uma panorâmica geral do código que iremos escrever.

Vamos dividir o processo em dois programas distintos:

 1. implementar um autómato genérico capaz de contar de quantas formas o autómato mencionado pode ser percorrido; e
 2. escrever algum código para gerar os estados relacionados com restrições específicas das passwords que pretendemos contar. 


## Autómato

Para o autómato genérico, eis aquilo de que necessitamos: 

 - os estados são tuplos;
 - um dicionário irá conter a lista de estados e setas relevantes:
   - cada estado surge numa entrada do dicionário;
   - cada entrada é uma lista e cada elemento da lista corresponde a uma das setas que _saem_ desse estado;
   - cada seta contém uma sequência que representa todos os caracteres associados com o tipo de seta em questão e o estado alcançado ao percorrê-la;
 - o multiplicador associado a cada aresta é o comprimento da sequência com os caracteres;
 - teremos uma lista com os estados terminais;

Lembram-se daquela figura que mostrámos em que os estados eram triplos? 

![](_triples_automaton.svg "Os estados $(2, 0, 0)$, $(3, 0, 0)$, $(2, 1, 0)$ e $(2, 0, 1)$, ligados por três setas.")

A título ilustrativo, eis o dicionário contendo as transições de estados, como acabámos de descrever, em Python:

```py
import string

state_transitions = {
    (2, 0, 0): [
        (string.ascii_uppercase, (2, 1, 0)),
        (string.digits, (2, 0, 1)),
        (string.ascii_lowercase, (3, 0, 0)),
    ],
    (2, 1, 0): [],
    (2, 0, 1): [],
    (3, 0, 0): [],
}
```

Repara que utilizamos `string.xxx`, que são sequências de caracteres contendo todos aqueles que nos interessam:

```py
>>> import string
>>> string.ascii_uppercase
'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
>>> string.ascii_lowercase
'abcdefghijklmnopqrstuvwxyz'
>>> string.digits
'0123456789'
```

Agora que tomámos consciência da informação que tem de ser armazenada, podemos criar o esqueleto na nossa classe `Automaton`:

```py
class Automaton:
    """Class that represents a finite state automaton."""
    def __init__(self, state_transitions, terminal_states):
        """Initialises a finite state automaton.

        `state_transitions` is a dictionary representing the state transitions
        and `terminal_states` is a container that holds all the terminal states.
        """

        self._state_transitions = state_transitions
        self._terminal_states = terminal_states

    def is_terminal(self, state):
        """Returns whether `state` is a terminal state for the automaton or not."""
        return state in self._terminal_states

    def count_terminal_paths(self, state):
        """Counts how many paths go from `state` to any terminal state."""
        pass
```

## Contar caminhos

Para contar caminhos, tudo o que precisamos de fazer é percorrer o autómato. Caso tenhas prestado atenção suficiente e estejas familiarizados com algoritmos em grafos, deves ter reparado que provavelmente podes utilizar o teu algoritmo favorito de pesquisa em grafos na tarefa que temos em mãos.

Queremos contar todos os caminhos válidos no autómato que começam em `origin` e acabam num estado terminal:

 - olhamos para todos os estados em `state_transitions[origin]`,
 pois esses são diretamente acessíveis a partir de `origin`;
 - contamos recursivamente quantos caminhos nos levam destes estados aos estados terminais; 
 - temos em consideração os multiplicadores das setas;
 - acrescentamos `1` caso o estado em que estamos seja também um estado terminal válido. 

O facto de descrevermos este algoritmo de forma recursiva torna simples a sua implementação:

```py
class Automaton:
    # ...

    def count_terminal_paths(self, state):
        """Counts how many paths go from `state` to any terminal state."""
        acc = int(self.is_terminal(state))
        for actions, next_state in self._state_transitions.get(state, []):
            acc += len(actions) * self.count_terminal_paths(next_state)
        return acc
```


## Recursividade ramificada

Contudo, é preciso ter cuidado!
Caso tenhas andado a ler os meus [Pydon'ts (dicas de programação em Python)][pydonts],
então deves saber que convém [prestar atenção à recursividade][pydont-recursion].
Em particular, como existem muitas setas que podem acabar num mesmo sítio, estamos perante um caso de [recursividade ramificada][pydont-recursion-branching]
nas nossas mãos (pensa na recursividade que ocorre quanto implementamos a sequência de Fibonacci: acabamos por recalcular muitos valores).
Como queremos que o nosso programa seja eficiente, algo que podemos fazer é armazenar os resultados intermédios à medida que os obtemos. Isto vai ser a diferença entre termos um programa que corre instantaneamente e um programa que demora imenso tempo a exibir os resultados.

Para armazenarmos os resultados, podemos escrever o nosso próprio _cache decorator_ ou utilizar algo disponível na biblioteca standard, como `functools.lru_cache`, mas no nosso caso é uma boa ideia embutir esta cache na nossa classe:

```py
class Automaton:
    """Class that represents a finite state automaton."""
    def __init__(self, state_transitions, terminal_states):
        # ...
        self._count_terminal_paths_cache = {}

    # ...

    def count_terminal_paths(self, state):
        """Counts how many paths go from `state` to any terminal state."""
        if state not in self._count_terminal_paths_cache:
            acc = int(self.is_terminal(state))
            for actions, next_state in self._state_transitions.get(state, []):
                acc += len(actions) * self.count_terminal_paths(next_state)
            self._count_terminal_paths_cache[state] = acc
        return self._count_terminal_paths_cache[state]
```


## Testar o autómato

Podemos fazer um teste básico a esta função. Vamos usar esta classe para contar a quantidade de passwords com comprimento entre 1 e 3 que são formadas pelas letras "a" e "b". À mão, concluímos que há 2 passwords válidas de comprimento 1,

 1. a
 2. b

4 passwords válidas de comprimento 2,

 1. aa
 2. ab
 3. ba
 4. bb

e 8 passwords válidas de comprimento 3,

 1. aaa
 2. aab
 3. aba
 4. abb
 5. baa
 6. bab
 7. bba
 8. bbb

o que perfaz um total de 14 passwords admissíveis. 

Recorrendo ao nosso programa, teríamos de implementar as transições de estados para criar uma instância da classe `Automaton` e depois invocar a função que faz a parte pesada por nós:

```py
if __name__ == "__main__":
    transitions = {
        0: [("ab", 1)],
        1: [("ab", 2)],
        2: [("ab", 3)],
        3: [],
    }
    terminal_states = [1, 2, 3]
    automaton = Automaton(transitions, terminal_states)
    print(automaton.count_terminal_paths(0))
```

Estamos muito, muito próximos de ter tudo a correr sem problemas! Tudo o que resta é sermos capazes de construir o autómato que codifica todas as passwords válidas.

As nossas passwords vão ter um comprimento superior a 3 e mais do que um tipo de caracter válido, logo não podemos escrever o autómato à mão (ok, _podemos_ mas não o _vamos_ fazer, ele vai ter milhares de estados). 

Vamos então pôr mãos à obra e começar por criar um script vazio que irá importar a nossa classe `Automaton` e contar passwords.

## Próximos estados possíveis

Dado o estado $(2, 0, 0)$, quais são os próximos estados possíveis para a nossa password?
Bem, é só uma questão de adicionarmos 1 ao número em cada índice, o que representa acrescentar um caracter desse tipo. Por isso, numa única linha, conseguimos escrever uma função que recebe um estado e retorna todos os próximos estados possíveis:

```py
def generate_next_pwd_states(s):
    return [s[:i] + (num+1,) + s[i+1:] for i, num in enumerate(s)]
```

E aqui está um exemplo:

```py
>>> generate_next_pwd_states((2, 0, 0))
[(3, 0, 0), (2, 1, 0), (2, 0, 1)]
```

Esta é uma peça importante do puzzle, mas como é que vamos emparelhar todos os estados com as setas relevantes? Por outras palavras, como é que vamos construir o dicionário que representa os estados e como nos podemos movimentar entre eles?

## Implementar as transições de estados

A resposta é: escrever uma função!

Vamos ter uma função que recebe os tipos de caracteres que estão disponíveis e gera todos os estados que representam passwords _até um certo comprimento_. Esta função vai também gerar todas as setas relevantes, e depois retornar tudo no dicionário de que necessitamos para a classe `Automaton`. 

Para fazer isto, vamos manter uma fila com todos os estados que sabemos que _existem_ mas ainda precisam de ser acrescentados ao dicionário. Inicializamos a fila com um estado que consiste apenas em zeros, e depois tentamos esvaziá-la ao processar todos os estados que nela constam. Dirigimo-nos à fila e processamos cada estado que encontramos:

 - se o estado representa passwords que são mais curtas do que o comprimento máximo, utilizamos a função acima para descobrir todos os próximos estados possíveis;
 - acrescentamos este estado como uma chave do dicionário, com o seu valor a ser a lista de tipos de caracteres emparelhados com os estados resultantes. 
Exemplificando com $(2, 0, 0)$, isto tomaria $(2, 0, 0)$
e produziria a entrada

```py
(2, 0, 0): [
    (string.ascii_uppercase, (2, 1, 0)),
    (string.digits, (2, 0, 1)),
    (string.ascii_lowercase, (3, 0, 0)),
]
```

no dicionário;
 - acrescentar todos estes novos estados à fila se eles ainda não se encontrarem lá.

A implementação deste passo é razoavelmente evidente:

```py
def generate_state_transitions(classes, max_length):
    queue = [(0,) * len(classes)]
    state_transitions = {}

    while queue:
        state, *queue = queue
        if sum(state) < max_length:
            next_states = generate_next_pwd_states(state)
        else:
            next_states = []
        state_transitions[state] = list(zip(classes, next_states))
        for state_ in next_states:
            if state_ not in queue:
                queue.append(state_)

    return state_transitions
```

!!! Se não souberes o que é que `state, *queue = queue` faz,
!!! então estás a perder uma ferramenta fantástica do Python 
!!! que nos permite desempacotar sequências de uma forma muito limpa
!!! ([mais informação aqui][pydont-star]).
!!! Já agora, `zip` é uma ferramenta em Python relativamente simples que é
!!! muitas vezes esquecida, por isso [clica aqui][pydont-zip] se precisares
!!! de recordar como é que ela funciona.

Exibimos de seguida um exemplo do seu uso, construindo as transições básicas para as passwords curtas formadas apenas pelas letras "a" e "b":

```py
>>> generate_state_transitions(["ab"], 3)                      
{(0,): [('ab', (1,))], (1,): [('ab', (2,))], (2,): [('ab', (3,))], (3,): []}
```

Se reformatarmos este dicionário, obtemos 

```py              
{
    (0,): [('ab', (1,))],
    (1,): [('ab', (2,))],
    (2,): [('ab', (3,))],
    (3,): [],
}
```

que é basicamente aquilo que já tínhamos, exceto que agora o nosso programa gera _sempre_ tuplos e o exemplo que fiz à mão utilizava inteiros em vez de tuplos com um só elemento, porque eu sou descuidado :/

Agora que já temos as transições de estados, resta-nos identificar os estados terminais antes de podermos instanciar a nossa classe `Automaton`. 


## Identificar os estados terminais

Aquilo que temos de fazer agora é percorrer o dicionário que nos diz como é que as transições de estados funcionam e identificar todos os estados que são relativos a passwords válidas.

Para fazer isso, escrevemos uma função que recebe um predicado e produz uma lista em compreensão com todos os estados terminais. Um "predicado" é uma função booleana que recebe um estado e devolve `True` se esse estado corresponder a uma estrutura admissível para uma password.

Se estivermos à procura de passwords cujo comprimento varia entre 8 e 10 e que contêm pelo menos uma letra minúscula, uma letra maiúscula e um algarismo, a nossa função predicado é tão simples quanto:

```py
valid_pwd = lambda s: 8 <= sum(s) <= 10 and all(s)
```

Recorda que `sum(s)` é o comprimento da password
que está a ser representada por esse tuplo, 
e empregamos `all(s)` para verificar se _todas_ as posições do tuplo do estado
são pelo menos 1:

```py
>>> all((2, 0, 0))
False
>>> all((2, 0, 1))
False
>>> all((2, 1, 1))
True
```

Finalmente, a função que identifica todos os estados terminais cabe novamente numa linha:

```py
def gather_terminal_states(state_transitions, is_valid_pwd):
    return [s for s in state_transitions if is_valid_pwd(s)]
```


# Contar passwords

Com todo o código já escrito (que não é assim tanto, honestamente) podemos agora contar todas as 698,438,863,898,480,640 passwords que têm comprimento entre 8 e 10, contendo pelo menos uma letra minúscula, uma letra maiúscula e um algarismo:

```py
import string, time
from automaton import Automaton

def generate_next_pwd_states(s):
    return [s[:i] + (num+1,) + s[i+1:] for i, num in enumerate(s)]

def generate_state_transitions(classes, max_length):
    queue = [(0,) * len(classes)]
    state_transitions = {}

    while queue:
        state, *queue = queue
        if sum(state) < max_length:
            next_states = generate_next_pwd_states(state)
        else:
            next_states = []
        state_transitions[state] = list(zip(classes, next_states))
        for state_ in next_states:
            if state_ not in queue:
                queue.append(state_)

    return state_transitions

def gather_terminal_states(state_transitions, is_valid_pwd):
    return [s for s in state_transitions if is_valid_pwd(s)]


if __name__ == "__main__":
    # Configure the password:
    classes = [
        string.ascii_uppercase,
        string.ascii_lowercase,
        string.digits,
    ]
    MIN_LENGTH = 8
    MAX_LENGTH = 10
    is_valid_pwd = lambda s: MIN_LENGTH <= sum(s) <= MAX_LENGTH and all(s)

    state_transitions = generate_state_transitions(classes, MAX_LENGTH)
    terminal_states = gather_terminal_states(state_transitions, is_valid_pwd)

    automaton = Automaton(state_transitions, terminal_states)
    start = time.time()
    print(automaton.count_terminal_paths((0,) * len(classes)))
    elapsed = time.time() - start
    print(f"Counted in {round(elapsed, 3)}s.")
```

Este script produz o seguinte output:

```
 > python .\count_passwords.py
698438863898480640
Counted in 0.004s.
```

É bastante impressionante, hun?
Passámos de 22 anos para 0.004 segundos.
Reduzimos o tempo de execução por um fator aproximadamente igual a 173.4 **mil milhões**!

## Verificar que está correto

O que foi feito é bem surpreendente, mas neste ponto eu não estava completamente seguro de que o programa estava a fazer a contagem corretamente, por isso decidi fazer um teste empírico não completamente trivial.

Fiz uma pesquisa online e encontrei duas questões no [math.stackexchange.com][math-se] em que se pretende calcular o número de passwords satisfazendo algumas restrições. Para uma delas, temos acesso à resposta que vem no manual, e para ambas existem pessoas capacitadas a responder (com explicação) à pergunta, portanto, podemos analisar essas explicações para verificar se as respostas obtidas estão corretas.

A primeira pergunta foi aquela que exibi neste artigo. A outra diz [mais ou menos][math-se-q2] o seguinte:

 > "_Quantas passwords existem com comprimento entre 6 e 8, 
 formadas por letras maiúsculas e algarismos, e contendo pelo menos um algarismo?_" 

Esta questão vem acompanhada da solução do manual, que é 2,684,483,063,360, e também de uma justificação detalhada em que se obtém esse mesmo valor.

Qual será a resposta que o nosso programa devolve? Como escrevemos um programa razoavelmente genérico, é muito fácil adaptá-lo a esta nova situação:

```py
if __name__ == "__main__":
    # Solve https://math.stackexchange.com/q/2452401/329832,
    # which should give 2,684,483,063,360

    # Configure the password:
    classes = [
        string.ascii_uppercase,
        string.digits,
    ]
    MIN_LENGTH = 6
    MAX_LENGTH = 8
    # Predicates:
    predicates = [
        lambda s: MIN_LENGTH <= sum(s) <= MAX_LENGTH,   # valid length?
        lambda s: s[1],                                 # has a digit?
    ]
    is_valid_pwd = lambda s: all(pred(s) for pred in predicates)

    state_transitions = generate_state_transitions(classes, MAX_LENGTH)
    terminal_states = gather_terminal_states(state_transitions, is_valid_pwd)

    automaton = Automaton(state_transitions, terminal_states)
    start = time.time()
    print(automaton.count_terminal_paths((0,) * len(classes)))
    elapsed = time.time() - start
    print(f"Counted in {round(elapsed, 4)}s.")
```

Observa que agora eu desdobrei a função `is_valid_pwd` numa série de predicados mais pequenos, para tornar ainda mais fácil o processo de contar diferentes tipos de passwords.

Além disso, verificar se a password possui pelo menos um algarismo é o mesmo que verificar qual é o valor no índice 1 do estado (porque os dígitos ocorrem na posição 1 da lista `classes`), e fazemos isso através de `lambda s: s[1]`.

Correndo este código, obtemos

```
 > python .\count_passwords.py 
2684483063360
Counted in 0.0013s.
```

Com estas duas verificações, estou razoavelmente confiante que o programa está operacional.


# Teste de robustez

Para o nosso teste de robustez final, queremos determinar quantas passwords diferentes existem com comprimento entre 8 e 20, contendo pelo menos um caracter de cada um dos seguintes grupos de caracteres:
 - letras minúsculas;
 - letras maiúsculas;
 - algarismos; e
 - símbolos especiais ``!"#$%&'()*+,-./:;<=>?@[\]^_`{|}~``.

A configuração agora é:

```py
if __name__ == "__main__":
    # Configure the password:
    classes = [
        string.ascii_lowercase,
        string.ascii_uppercase,
        string.digits,
        string.punctuation,
    ]
    MIN_LENGTH = 8
    MAX_LENGTH = 20
    # Predicates:
    predicates = [
        lambda s: MIN_LENGTH <= sum(s) <= MAX_LENGTH,   # valid length?
        all,
    ]
```

e isto produz

```
 > python .\count_passwords.py
2613279260982103214130338931095048847360
Counted in 1.2325s.
```

Eu não sei ler este número, mas se fôssemos listar todas estas passwords, a um ritmo de mil milhões por segundo, [necessitaríamos de $8.287 \times 10^{22}$ anos](https://www.wolframalpha.com/input/?i=2613279260982103214130338931095048847360%2F1000000000+seconds+in+years).
Este valor é $6 \times 10^{12}$ vezes superior à idade atual do universo. Eu não consigo sequer imaginar estes números... Vamos todos concordar que eles são _GRANDES_.

# Código final

Se quiseres espreitar o código final, estás à vontade para visitar [este repositório no GitHub][gh-repo].

[wiki-iep]: https://en.wikipedia.org/wiki/Inclusion%E2%80%93exclusion_principle
[gh-repo]: https://github.com/RojerGS/projects/tree/master/automatons
[pydonts]: /blog/pydonts
[pydont-recursion]: /blog/pydonts/watch-out-for-recursion
[pydont-recursion-branching]: https://mathspp.com/blog/pydonts/watch-out-for-recursion#branching-overlap
[pydont-zip]: /blog/pydonts/zip-up
[pydont-star]: /blog/pydonts/unpacking-with-starred-assignments
[math-se]: https://math.stackexchange.com
[math-se-q2]: https://math.stackexchange.com/q/2452401/329832
[traducao-gh]: https://github.com/mathspp/mathspp/pull/56
