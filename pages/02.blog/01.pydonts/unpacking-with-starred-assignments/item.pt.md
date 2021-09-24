---
metadata:
    description: "Quando estamos a desempacotar uma lista ou um tuplo em Python, é de evitar usar fatias."
title: "Desempacotar com atribuições com asterisco | Pydon't 🐍"
---

Como é que devemos "desempacotar" uma lista ou um tuplo, de modo a separar o primeiro
elemento do resto?
Ou de modo a separar o último elemento do resto?
Não desempacotes com fatias, usa antes atribuições com um asterisco.

===

![Um exemplo de código Python que usa uma atribuição com asterisco.](thumbnail.png)

(Se és novo aqui e não sabes o que é uma Pydon't, então talvez queiras começar por
ler a [Proclamação das Pydon'ts][manifesto].)

# Atribuições com asterisco

(Odeio traduzir termos de programação para português... O termo original é
"starred assignment", caso queiras pesquisar por ele.)

É relativamente comum estarmos a trabalhar com uma lista ou algum outro iterável e
querermos dividir o iterável no primeiro valor e nos *restantes*.
Podemos fazer isto com fatias em Python, mas a melhor maneira é usando uma *atribuição
com asterisco*.

Esta funcionalidade foi introduzida no [PEP 3132 -- "Extended Iterable Unpacking"][pep3132] e permite que façamos o seguinte:

```py
>>> l = [1, 2, 3, 4, 5]
>>> head, *tail = l
>>> head
1
>>> tail
[2, 3, 4, 5]
```

Esta atribuição com asterisco é feita através da utilização de um asterisco `*`
à esquerda do nome de uma variável numa atribuição múltipla, e pondo um iterável
à direita do sinal de igual `=` da atribuição.
Todos os nomes à esquerda recebem um único elemento e o nome com o asterisco recebe
todos os elementos que sobrarem, numa lista:

```py
>>> string = "Hello!"
>>> *start, last = string
>>> start
['H', 'e', 'l', 'l', 'o']
>>> last
'!'
```

Podemos ter mais de duas variáveis à esquerda, mas **apenas uma** pode ter o asterisco:

```py
>>> a, b, *c, d = range(5) # any iterable works
>>> a
0
>>> b
1
>>> c
[2, 3]
>>> d
4
```

Quando usamos uma atribuição com asterisco, o nome com o asterisco pode receber uma
lista vazia,

```py
>>> a, *b = [1]
>>> a
1
>>> b
[]
```

e obtemos um erro se não houver elementos suficientes para atribuir a todos os
nomes que não têm o asterisco:

```py
>>> a, *b = []
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
ValueError: not enough values to unpack (expected at least 1, got 0)
```

# Exemplos em código

Aqui vão alguns exemplos de utilização destas atribuições, em funções.

## `reduce` da biblioteca `functools`

Suponhamos que queremos implementar uma função do estilo da função `reduce` da
biblioteca `functools` (podes ler a documentação da função [aqui][reduce]).

Aqui tens um exemplo da implementação, usando fatias:

```py
def reduce(function, list_):
    """Reduce the elements of the list by the binary function."""

    if not list_:
        raise TypeError("Cannot reduce empty list.")
    value = list_[0]
    list_ = list_[1:]
    while list_:
        value = function(value, list_[0])
        list_ = list_[1:]
    return value

print(reduce(lambda a, b: a + b, range(10))) # 45
```

E agora uma implementação equivalente, usando atribuições com asterisco:

```py
def reduce(function, list_):
    """Reduce the elements of the list by the binary function."""

    if not list_:
        raise TypeError("Cannot reduce empty list.")
    value, *list_ = list_
    while list_:
        val, *list_ = list_
        value = function(value, val)
    return value

print(reduce(lambda a, b: a + b, range(10))) # 45
```

A utilização das atribuições com asterisco fazem com que seja perfeitamente
claro que queremos estar a separar o primeiro elemento da lista do resto
da lista, que é usada mais tarde.

Agora mostramos outro exemplo parecido, mas em que pomos o asterisco no início
da atribuição, e não no fim.

## Dígito de verificação dos cartões de crédito.

O [algoritmo de Luhn][luhn] é usado para calcular dígitos de verificação de coisas
como números de cartões de crédito ou contas bancárias.

Vamos implementar uma função que recebe uma lista de dígitos e que usa o algoritmo
de Luhn para dizer se os dígitos e o dígito de verificação batem certo.
Vamos usar uma atribuição com asterisco para separar o dígito de verificação, que vem
no fim, do resto dos dígitos que temos de processar:

```py
def verify_check_digit(digits):
    """Use the Luhn algorithm to verify the check digit."""

    *digits, check_digit = digits
    weight = 2
    acc = 0
    for digit in reversed(digits):
        value = digit * weight
        acc += (value // 10) + (value % 10)
        weight = 3 - weight # 2 -> 1 and 1 -> 2
    return (9 * acc % 10) == check_digit

# Example from Wikipedia.
print(verify_check_digit([7, 9, 9, 2, 7, 3, 9, 8, 7, 1, 3])) # True
```

Se calhar não é óbvio o que é que o código faz, só de olhar para a função,
mas algo que deve ser bastante claro é que a linha `*digits, check_digit = digits`
divide a lista `digits` no último elemento, e tudo o resto que vem antes.

Como é que implementarias a função em cima, usando apenas índices e fatias?
Talvez assim:

```py
def verify_check_digit(digits):
    """Use the Luhn algorithm to verify the check digit."""

    weight = 2
    acc = 0
    for digit in reversed(digits[:-1]):
        value = digit * weight
        acc += (value // 10) + (value % 10)
        weight = 3 - weight # 2 -> 1 and 1 -> 2
    return (9 * acc % 10) == digits[-1]

# Example from Wikipedia.
print(verify_check_digit([7, 9, 9, 2, 7, 3, 9, 8, 7, 1, 3])) # True
```

Também funciona, mas fica um pouco mais confuso.
Repara que agora temos duas operações de indexação semelhantes, embora uma seja
uma fatia e a outra seja uma indexação de um elemento individual.

No ciclo `for` temos `reversed(digits[:-1])` ao passo que no fim da função temos
`... == digits[-1]`.
Se eu não prestar atenção suficiente, não vou reparar que são diferentes.
Claro que a culpa é *minha* se eu estiver a ler código e não estiver a prestar
atenção, mas quando eu *escrevo* código, prefiro ser o mais claro possível:
quero que o acto de ler o código seja fácil, para que o leitor possa gastar o seu
tempo a compreender os algoritmos.

---

Não te esqueças de deixar uma reação a este artigo se gostaste desta Pydon't e
partilha-a com amigos e outros colegas que programem em Python.
Comenta em baixo com um exemplo de um sítio onde usaste esta Pydon't!

Já agora, [subscreve a newsletter][subscribe] para teres a certeza que não te escapa
nem uma única Pydon't!

# Referências (em inglês)

 - PEP 3132 -- Extended Iterable Unpacking, [https://www.python.org/dev/peps/pep-3132/][pep3132]
 - Python 3.9.1 Documentation, The Python Standard Library, Functional Programming Modules, `functools`, [https://docs.python.org/3/library/functools.html#functools.reduce][reduce] [consulted on the 12th of January of 2021].
 - Luhn Algorithm, Wikipedia, [https://en.wikipedia.org/wiki/Luhn_algorithm][luhn].

[subscribe]: https://mathspp.com/subscribe
[manifesto]: /pt/blog/pydonts/pydont-manifesto
[pep3132]: https://www.python.org/dev/peps/pep-3132/
[reduce]: https://docs.python.org/3/library/functools.html#functools.reduce
[luhn]: https://en.wikipedia.org/wiki/Luhn_algorithm
