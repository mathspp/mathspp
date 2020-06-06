---
title: Py-don't faças a contração de comparações diferentes
slug: chain-different-comparison-operators
date: 15-03-2020
published: false
taxonomy:
    tag: [pydont, python, programming]
---

Não faças a contração de comparações que não sejam iguais, i.e. evita coisas como:

```py
def first_argument_is_larger(a, b, c):
    return b < a > c
```

Quando as comparações não são iguais é melhor separá-las e usar a disjunção lógica `and`.

===

A versão mais _pythonica_ do código em cima seria

```py
def first_arg_is_larger(a, b, c):
    """Returns True if a is larger than b and c."""
    return a > b and a > c  # or b < a and c < a
```

Uma pessoa pode sentir-se tentada a escrever a primeira versão porque o Python permite que possamos fazer a contração de comparações sucessivas, o que faz com que as seguintes expressões sejam código Python válido:

```py
a < b < c   # instead of a < b and b < c
a == b == c # instead of a == b and b == c
a <= b < c  # instead of a <= b and b < c
```

mas não nos devemos esquecer que [_legibilidade importa_](../zen-of-python "readability counts")! Quando as comparações que queremos contrair não são todas iguais, o nosso código deixa de ser elegante e torna-se um pouco mais confuso. Um bom exemplo é a expressão `a != b != c`; alguém um pouco menos atento pode achar que estamos a verificar se as três variáveis `a`, `b` e `c` são todas diferentes. No entanto, a expressão é equivalente a `a != b and b != c`. Isto motra que se $a = c$ e $a \neq b$, a expressão `a != b != c` é `True` mas não é verdade que as três variáveis são todas diferentes.

Quem argumentar que, ao não contraírmos as comparações, estamos a sacrificar performance porque a expressão contraída tem menos operações... Desengane-se! Abre um terminal qualquer, corre os comandos seguintes e compara os resultados:

```bash
python -m timeit "0 < 1 < 2"
python -m timeit "0 < 1 and 1 < 2"
```

Estes dois comandos estão a usar a biblioteca [`timeit`][timeit] do Python para calcular quanto tempo o Python demora a executar cada pedaço de código. Os resultados que eu obtive mostraram que a versão que não está contraída é $\approx 10\%$ mais rápida. Até é provável que, nos bastidores, o Python esteja a converter a versão contraída para a versão não contraída e só depois é que a avalia.

Concluindo, se podemos tornar o nosso código um pouco mais legível **e ao mesmo tempo** mais eficiente, porque é que não haveríamos de o fazer?

Isto fez sentido? Façam-me saber, deixando um comentário em baixo!

[zen-of-python]: ../pydont-zen-of-python
[pydont]: ../.
[timeit]: https://docs.python.org/3/library/timeit.html#module-timeit