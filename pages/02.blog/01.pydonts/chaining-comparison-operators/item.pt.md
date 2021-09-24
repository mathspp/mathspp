---
metadata:
    description: "Aprende as nuances do funcionamento do encadeamento de comparações em Python."
title: "Encadeamento de comparações | Pydon't 🐍"
---

As comparações em Python podem ser encadeadas.
Aprende as nuances desta funcionalidade de Python e, em particular,
os casos a *evitar* tais como quando encadeias comparações que não estão alinhadas.

===

![Código Python com comparações encadeadas.](thumbnail.png)

(Se és novo aqui e não sabes o que é uma Pydon't, então talvez queiras começar por
ler a [Proclamação das Pydon'ts][manifesto].)


# Introdução

Nesta Pydon't vamos falar do encadeamento de comparações:

 - como funcionam;
 - utilizações úteis;
 - casos enganadores a evitar.


# Encadeamento de comparações

Uma das coisas que eu mais gosto em Python é que algumas das suas funcionalidades
fazem tanto sentido que tu nem percebes que o que estás a usar é uma funcionalidade,
até que alguém te aponta para o facto de que noutras linguagens isso não funciona.
Um exemplo deste tipo de funcionalidade é o encadeamento de comparações!
Olha para este excerto de código e diz-me se não achas super natural:

```py
>>> a = 1
>>> b = 2
>>> c = 3
>>> if a < b < c:
...     print("Increasing seq.")
... 
Increasing seq.
```

Quando Python vê duas comparações encadeadas, tal como em `a < b < c`,
Python comporta-se mais ou menos como se tivesses escrito `a < b and b < c`,
com a diferença de que `b` só é avaliado uma vez (que é uma nuance relevante
se `b` for uma expressão como uma chamada de uma função).

Na minha opinião, esta funcionalidade faz *imenso* sentido e não me surpreende
que exista.
Na verdade, tenho pena que outras linguagens não tenham esta funcionalidade.

Outro exemplo claro surge quando queremos ver se três valores são iguais:

```py
>>> a = b = 1
>>> c = 2
>>> if a == b == c:
...     print("all same")
... else:
...     print("some are diff")
... 
some are diff
>>> c = 1
>>> if a == b == c:
...     print("all same")
... else:
...     print("some are diff")
... 
all same
```

!!! Sabias que podes encadear um número arbitrário de comparações?
!!! Por exemplo, `a == b == c == d == e` verifica se os cinco valores são
!!! todos iguais, ao passo que `a < b < c < d < e` verifica se formam
!!! uma sequência crescente.


# A ter em atenção

Apesar desta funcionalidade ser algo natural, há um par de coisas com as quais
temos de ter atenção.


## Comparações não transitivas

Em cima vimos que `a == b == c` serve para verificar se `a`, `b` e `c` são todos
iguais.
Como vemos se são todos diferentes?

Se pensaste em escrever `a != b != c`, então caíste na armadilha!

Repara:

```py
>>> a = c = 1
>>> b = 2
>>> if a != b != c:
...     print("a, b, and c all different:", a, b, c)
a, b, and c all different: 1 2 1
```

O problema é que `a != b != c` toma o mesmo valor que `a != b and b != c`,
que verifica se `b` é diferente de `a` e de `c`, mas não garante nada sobre
a relação entre `a` e `c`.

De um ponto de vista matemático, o que se passa aqui é que `!=` não é uma
relação transitiva, i.e. saber a relação entre `a` e `b` e entre `b` e `c` não
diz *nada* sobre o modo como `a` e `c` se relacionam.
Um exemplo de uma comparação transitiva é `==`: se `a == b` e `b == c` então
`a == c`.


## Expressões não determinísticas ou com efeitos secundários

Quando as comparações são encadeadas, como em `a < b < c`, a expressão do meio (`b`)
só é avaliada *uma* vez, ao passo que a versão expandida é `a < b and b < c`,
em que a expressão `b` é avaliada duas vezes.

Se `b` contém uma expressão que tem efeitos secundários ou cujo resultado não
é sempre o mesmo, então as duas expressões não são equivalentes e tens
de pensar no que queres fazer.

Este excerto de código exemplifica um caso em que a expressão do meio tem
efeitos secundários:

```py
>>> def f():
...     print("hey")
...     return 3
... 
>>> if 1 < f() < 5:
...     print("done")
... 
hey
done
>>> if 1 < f() and f() < 5:
...     print("done")
... 
hey
hey
done
```

O excerto seguinte contrasta o resultado da expressão `1 < f() < 0`, que é sempre
falsa do ponto de vista matemático, com a expressão não encadeada
`1 < f() and f() < 0`:

```py
>>> l = [-2, 2]
>>> def f():
...     global l
...     l = l[::-1]
...     return l[0]
>>> if 1 < f() and f() < 0:
...     print("ehh")
...
ehh
```

! O código `l[::-1]` é uma “fatia” (“slice” em inglês) que vira uma lista ao contrário.
! Vou escrever sobre estas operações sobre listas em breve, por isso [mantém-te atento][subscribe]!

Podemos ver, assim, que as versões encadeadas e as não encadeadas não têm de ser
equivalentes.


# Encadeamentos deselegantes

Há certos encadeamentos de comparações que não são tão legíveis.
Claro que isto é subjetivo, mas as utilizações que eu acho melhores
são as que têm operadores "alinhados", como

 - `a == b == c`
 - `a < b <= c`
 - `a <= b < c`

Encadeamentos como

 - `a < b > c`
 - `a <= b > c`
 - `a < b >= c`

já não são tão elegantes.
Uma pessoa poderia argumentar que `a < b > c`, por exemplo, se lê bem como
“verifica se `b` é maior que `a` e `c`”, mas uma pessoa também pode escrever
`max(a, c) < b` ou `b > max(a, c)`.

Pior ainda são encadeamentos como os que se seguem:

 - `a < b is True`
 - `a == b in l`
 - `a in l is True`

Em Python, `is`, `is not`, `in` e `not in` são operadores de comparação,
logo podemos encadea-los com outras comparações.
Isto cria situações estranhas como a seguinte:

```py
>>> a = 3
>>> l = [3, 5]
>>> if a in l == True:
...     print("Yeah :D")
... else:
...     print("Hun!?")
... 
Hun!?
```

Esta é a explicação do que está a acontecer neste exemplo:

 - `a in l == True` é equivalente a `a in l and l == True`;
 - `a in l` dá `True`, _mas_
 - `l == True` dá `False`, logo
 - `a in l == True` é o mesmo que `True and False`, que dá `False`.

Quem escreveu `a in l == True` provavelmente queria dizer `(a in l) == True`,
mas isso tem o mesmo valor que `a in l`.


# Exemplos em código

## Encadeamento de desigualdades

É muito fácil usar esta funcionalidade para criar uma função simples que
garante que um valor está entre um limite mínimo e um limite máximo,
por exemplo

```py
def ensure_within(value, bounds):
    return bounds[0] <= value <= bounds[1]
```

ou, de forma mais explícita,
ao mesmo tempo que garantes que `bounds` tem *exatamente* dois elementos
(vai espreitar o [Pydon't sobre atribuições estruturais][pydont-deep-unpacking]),
podes escrever

```py
def ensure_within(value, bounds):
    m, M = bounds
    return m <= value <= M
```

## Encadeamento de igualdades

Retirado do código da biblioteca [`enum`][enum], partilho aqui um
exemplo de uma função auxiliar (que não é disponibilizada aos utilizadores)
que está definida do seguinte modo:

```py
def _is_dunder(name):
    """Returns True if a __dunder__ name, False otherwise."""
    return (len(name) > 4 and
            name[:2] == name[-2:] == '__' and
            name[2] != '_' and
            name[-3] != '_')
```

Esta função verifica se uma string é o nome de um método dunder ou não.

! “Dunder” (do inglês, “double underscore”) é o nome que damos a alguns métodos
! de classes em Python
! e que permitem que os nossos objetos interajam com certas funcionalidades do Python.
! Chamamos “dunder” a estes métodos porque os seus nomes começam e acabam com `__`.
! Já viste os métodos dunder `__str__` e `__repr__` no
! [Pydon't “str e repr”][pydont-str-repr] e já viste o método dunder
! `__bool__` no [Pydon't “Truthy, falsy e bool”][pydont-truthy-falsy-bool].
! Vou escrever sobre métodos dunder em geral numa Pydon't próxima, por isso
! [subscreve][subscribe] a newsletter para te manteres a par.

A primeira coisa que o código faz é verificar se o início da string é igual ao fim
da string, e vê se esses pedaços são iguais a `"__"`:

```py
>>> _is_dunder("__str__")
True
>>> _is_dunder("__bool__")
True
>>> _is_dunder("_dnd__") 
False
>>> _is_dunder("_______underscores__")
False
```

# Conclusão

Aqui está a conclusão principal deste artigo:

 > “Encadear comparações é algo tão natural que muitas vezes nem percebemos
 > o que estamos a fazer.
 > No entanto, há casos que têm um aspeto enganador e há que ter cuidado.”

Esta Pydon't mostrou que:

 - podemos encadear comparações, e que podemos fazê-lo um número arbitrário de vezes;
 - os encadeamentos não são equivalentes às expressões por extenso se a expressão
do meio produzir efeitos secundários ou se não produzir sempre o mesmo valor;
 - alguns encadeamentos com `is` ou `in` podem ser enganadores.

Não te esqueças de deixar uma reação a este artigo se gostaste desta Pydon't e
partilha-a com amigos e outros colegas que programem em Python.

Já agora, [subscreve a newsletter][subscribe] para teres a certeza que não te escapa
nem uma única Pydon't!

# Referências (em inglês)

  - Python 3 Documentation, The Python Language Reference
[https://docs.python.org/3/reference/expressions.html#comparisons](https://docs.python.org/3/reference/expressions.html#comparisons);
 - Python 3 Documentation, The Python Standard Library, `enum`, [https://docs.python.org/3/library/enum.html][enum];
 - Reddit, comment on "If they did make a python 4, what changes from python 3 would you like to see?",
[https://www.reddit.com/r/Python/comments/ltaf3y/if_they_did_make_a_python_4_what_changes_from/gowuau5?utm_source=share&utm_medium=web2x&context=3](https://www.reddit.com/r/Python/comments/ltaf3y/if_they_did_make_a_python_4_what_changes_from/gowuau5?utm_source=share&utm_medium=web2x&context=3).


Consultadas pela última vez a 1 de março de 2021.

[subscribe]: https://mathspp.com/subscribe
[manifesto]: /blog/pydonts/pydont-manifesto
[pydont-deep-unpacking]: /blog/pydonts/deep-unpacking
[enum]: https://docs.python.org/3/library/enum.html
[pydont-truthy-falsy-bool]: /blog/pydonts/truthy-falsy-and-bool
[pydont-str-repr]: /blog/pydonts/str-and-repr
