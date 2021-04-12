---
metadata:
    description: "Atribuições estruturais são uma funcionalidade de Python que não é usada muitas vezes, mas é bom saber como funciona para quando a oportunidade surge!"
title: "Atribuições estruturais | Pydon't"
---

Usar atribuições estruturais tem o potencial de tornar o código bastante
mais expressivo.
Estas atribuições permitem melhorar a legibilidade do teu código
e protegem-te contra alguns tipos de bugs.
Aprender a usar esta ferramenta também é muito importante para
conseguires aproveitar ao máximo a funcionalidade de
[execução condicional estrutural][pep-634] que vai ser lançada com o Python 3.10.

===

![Código Python a usar uma atribuição estrutural.](thumbnail.png)

(Se és novo aqui e não sabes o que é uma Pydon't, então talvez queiras começar por
ler a [Proclamação das Pydon'ts][manifesto].)


# Introdução

Nesta Pydon't vamos falar de atribuições estruturais:
 - o que são;
 - como funcionam;
 - como usá-las para tornar o código mais legível;
 - como usá-las para ajudar a depurar o código.

Aprender sobre atribuições estruturais também vai ser **muito** útil para quando
se puder usar [execução condicional estrutural][pep-634],
uma funcionalidade que vai ser introduzida na versão 3.10 de Python.


# Atribuições

Antes de mostrar como as atribuições esstruturais funcionam,
vou mostrar rapidamente outras duas funcionalidades das atribuições em Python.


## Atribuições múltiplas

Em Python, a capacidade de escrever atribuições múltiplas permite-te escrever coisas
como

```py
>>> x = 3
>>> y = "hey"
>>> x, y = y, x    # Multiple assignment to swap variables.
>>> x
'hey'
>>> y
3
```

ou

```py
>>> rgb_values = (45, 124, 183)
>>> r, g, b = rgb_values    # Multiple assignment unpacks the tuple.
>>> g
124
```

As atribuições múltiplas fazem com que possas atribuir vários valores a várias
variáveis, desde que tenhas tantos elementos à esquerda do `=` como à direita.


## Atribuições com asterisco

As atribuições com asterisco, de que falei [nesta Pydon't][pydont-starred-assignment],
permitem que escrevamos coisas como

```py
>>> l = [0, 1, 2, 3, 4]
>>> head, *body = l
>>> print(head)
0
>>> print(body)
[1, 2, 3, 4]
>>> *body, tail = l
>>> print(tail)
4
>>> head, *body, tail = l
>>> print(body)
[1, 2, 3]
```

Com atribuições deste estilo, mesmo que não saibamos quantos elementos vão estar
do lado direito, podemos pôr todos numa só variável.


## Atribuições estruturais

As atribuições estruturais são, de certa forma, parecidas com as atribuições múltiplas.
As atribuições múltiplas permitem fazer atribuições de acordo com o comprimento do
objeto iterável que está à direita, e pôr cada elemento numa única variável.
De modo semelhante, atribuições estruturais permitem fazer atribuições de acordo
com a *estrutura* do objeto iterável do lado direito da atribuição; em particular,
se houver iteráveis dentro de outros iteráveis, podes aceder diretamente aos elementos
contidos nesses iteráveis.

Por exemplo, com uma atribuição múltipla *dupla*, podes fazer isto:

```py
>>> colour_info = ("AliceBlue", (240, 248, 255))
>>> name, rgb_values = colour_info
>>> name
'AliceBlue'
>>> r, g, b = rgb_values
>>> g
248
```

Mas se já souberes que queres aceder aos valores RGB,
podes usar uma atribuição estrutural:

```py
>>> colour_info = ("AliceBlue", (240, 248, 255))
>>> name, (r, g, b) = colour_info
>>> name
'AliceBlue'
>>> g
248
```

Repara que as variáveis `r`, `g` e `b` estão agrupadas dentro de parêntesis,
para que a estrutura do lado esquerdo do `=` esteja igual à da variável `colour_info`.
Se nos tivéssemos limitado a escrever `name, r, g, b = colour_info`, então o Python
teria tentado fazer uma atribuição múltipla, e estaria à espera que `colour_info`
tivesse quatro elementos lá dentro:

```py
>>> colour_info = ("AliceBlue", (240, 248, 255))
>>> name, r, g, b = colour_info
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
ValueError: not enough values to unpack (expected 4, got 2)
```

A utilização dos parêntesis em `(r, g, b)` diz ao Python para aceder à estrutura
interna da variável `colour_info`.

Isto é capaz de ficar mais claro se incluírmos o par de parêntesis que costuma ser
omitido:

```py
>>> colour_info = ("AliceBlue", (240, 248, 255))
>>> (name, (r, g, b)) = colour_info
>>> name
'AliceBlue'
>>> g
248
```

Até podemos pôr o valor de `colour_info` do lado direito do sinal de atribuição,
para que seja muito fácil de ver que valor fica em que variável:

```py
>>> (name, (r, g, b)) = ("AliceBlue", (240, 248, 255))
```

!!! Sabias que em Python 2 podias usar atribuições estruturais na definição de
!!! funções?
!!! Por exemplo, o seguinte código era válido em Python 2:
!!! ```py
!!! def print_some_colour_info(name, (r, g, b)):
!!!     print name + " has g value of " + str(g)
!!! 
!!! print_some_colour_info("AliceBlue", (240, 248, 255))  # prints 'AliceBlue has g value of 248'
!!! ```
!!! Esta funcionalidade foi removida com o [PEP 3113][pep-3113].


## Em ciclos

Atribuições estruturais também podem ser usadas nas atribuições implícitas dos ciclos
`for`, tal como vou mostrar nos exemplos a seguir.

As atribuições estruturais, quando bem usadas, melhoram a legibilidade do código –
porque removem operações de indexação supérfluas e porque tornam o código mais
explícito – e também podem ajudar a encontrar alguns erros no código.

Não há nada melhor do que mostrar algum código que corrobore o que estou a dizer.


# Exemplos em código

## Código mais explícito

Dados os valores RGB de uma cor, há uma fórmula simples que podes aplicar para
a transformar num tom de cinzento.
Podemos escrever uma função que recebe os dados de uma cor com a estrutura que
temos visto no exemplo e que calcula o tom de cinzento:

```py
def greyscale(colour_info):
    return 0.2126*colour_info[1][0] + 0.7152*colour_info[1][1] + 0.0722*colour_info[1][2]
```

(A fórmula que estamos a usar,

\[
    0.2126R + 0.7152G + 0.0722B ~ ,
\]

costuma ser o primeiro cálculo numa fórmula um pouco mais complexa, mas esta versão
simplificada serve perfeitamente para o nosso exemplo.)

Agora podemos usar a nossa função:

```py
colour = ("AliceBlue", (240, 248, 255))
print(greyscale(colour))  # prints 246.8046
```

No entanto, podemos ver que a função em cima pode ser melhorada.
A fórmula comprida não é muito elegante.
De facto, se usarmos uma atribuição estrutural para aceder aos valores `r`, `g`
e `b`, então podemos escrever a fórmula de uma forma muito mais explícita:

```py
def greyscale(colour_info):
    name, (r, g, b) = colour_info
    return 0.2126*r + 0.7152*g + 0.0722*b

colour = ("AliceBlue", (240, 248, 255))
print(greyscale(colour))  # still prints 246.8046
```

Claro que os leitores mais astutos (ou desconfiados) podem argumentar
que eu podia ter definido a função `greyscale` de tal forma que recebesse diretamente
os valores `r`, `b` e `b` separados.
E essas pessoas têm razão!
Eu podia ter escrito

```py
def greyscale(r, g, b):
    return 0.2126*r + 0.7152*g + 0.0722*b
```

Mas, por vezes, estamos a escrever código que vai ser integrado com o código de
outras pessoas, e nessas situações podem já existir tipos de dados que são
usados no código ou certas convenções que já estão em uso.
Nesses casos, é mais simples programar de acordo com os padrões já existentes.

Agora imagina que tens uma lista com cores e queres calcular o tom de cinzento.
Podes usar uma atribuição estrutural num ciclo `for` (e até numa “*list
comprehension*”):

```py
colours = [
    ("AliceBlue", (240, 248, 255)),
    ("Aquamarine", (127, 255, 212)),
    ("DarkCyan", (0, 139, 139)),
]
greyscales = [
    round(0.2126*r + 0.7152*g + 0.0722*b, 2) for name, (r, g, b) in colours
]
print(greyscales)  # [246.8, 224.68, 109.45]
```


## Encontrar erros

Tal como disse anteriormente, uma atribuição estrutural também pode ser útil para
encontrar erros no nosso código.
Podemos imaginar, por exemplo, que a lista de cores dos exemplos anteriores têm
sido retiradas automaticamente da internet, com um programa que abre um site
e extrai a informação necessária.

Imaginem agora que esse programa tem um pequeno erro e que, portanto, não está
a funcionar muito bem:
estraiu demasiada informação para dentro da mesma cor:

```py
colours = [
    ("AliceBlue", (240, 248, 255, 127, 255, 212)),
    ("DarkCyan", (0, 139, 139)),
]
```

Se usássemos a função `greyscale` original, com os índices, então
heis o que aconteceria:

```py
def greyscale(colour_info):
    return 0.2126*colour_info[1][0] + 0.7152*colour_info[1][1] + 0.0722*colour_info[1][2]

colours = [
    ("AliceBlue", (240, 248, 255, 127, 255, 212)),
    ("DarkCyan", (0, 139, 139)),
]

print(greyscale(colours[0]))  # 246.8046
```

_No entanto_, se usássemos a versão que usa atribuições estruturais, então
isto aconteceria:

```py
def greyscale(colour_info):
    name, (r, g, b) = colour_info
    return 0.2126*r + 0.7152*g + 0.0722*b

colours = [
    ("AliceBlue", (240, 248, 255, 127, 255, 212)),
    ("DarkCyan", (0, 139, 139)),
]

print(greyscale(colours[0]))  # ValueError: too many values to unpack (expected 3)
```

Atribuições estruturais só funcionam se a estrutura do objeto iterável da direita
coincider com a estrutura das variáveis à esquerda do sinal de atribuição,
logo a tentativa de atribuir os seis números à parte `(r, g, b)` dá um erro.
Ao ver este erro, perceberias que algo de errado se passava no teu código!

Feitas as contas, atribuições estruturais não são algo super comum *mas* é muito
útil saber usá-las.


# Conclusão

Aqui está a conclusão principal deste artigo:

 > “Usa atribuições estruturais para melhorar a legibilidade do teu código
e para ter a certeza que a estrutura das tuas variáveis está certa.”

Esta Pydon't mostrou que:

 - as atribuições em Python têm muitas funcionalidades interessantes;
 - atribuições estruturais podem prevenir a utilização excessiva de índices;
 - atribuições estruturais melhoram a legibilidade do código;
 - alguns erros relacionados com a estrutura das variáveis podem ser apanhados
ao utilizar atribuições estruturais.


Não te esqueças de deixar uma reação a este artigo se gostaste desta Pydon't e
partilha-a com amigos e outros colegas que programem em Python.

Já agora, [subscreve a newsletter][subscribe] para teres a certeza que não te escapa
nem uma única Pydon't!

# Referências (em inglês)

 - PEP 634 -- Structural Pattern Matching: Specification, [https://www.python.org/dev/peps/pep-0634/][pep-634];
 - PEP 3113 -- Removal of Tuple Parameter Unpacking, [https://www.python.org/dev/peps/pep-3113/][pep-3113];
 - Multiple assignment and tuple unpacking improve Python code readability, [https://treyhunner.com/2018/03/tuple-unpacking-improves-python-code-readability/#Using_a_list-like_syntax](https://treyhunner.com/2018/03/tuple-unpacking-improves-python-code-readability/#Using_a_list-like_syntax);
 - Unpacking Nested Data Structures in Python, [https://dbader.org/blog/python-nested-unpacking](https://dbader.org/blog/python-nested-unpacking);
 - W3Schools, HTML Color Names, [https://www.w3schools.com/colors/colors_names.asp](https://www.w3schools.com/colors/colors_names.asp);
 - Wikipedia, Grayscale, Converting color to grayscale, [https://en.wikipedia.org/wiki/Grayscale#Converting_color_to_grayscale](https://en.wikipedia.org/wiki/Grayscale#Converting_color_to_grayscale).


Consultadas pela última vez a 23 de fevereiro de 2021.


[subscribe]: https://mathspp.com/subscribe
[manifesto]: /blog/pydonts/pydont-manifesto
[pydont-starred-assignment]: /blog/pydonts/unpacking-with-starred-assignment
[pep-634]: https://www.python.org/dev/peps/pep-0634/
[pep-3113]: https://www.python.org/dev/peps/pep-3113/
