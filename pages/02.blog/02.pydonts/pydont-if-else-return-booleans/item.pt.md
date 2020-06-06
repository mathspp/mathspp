---
title: Py-don't uses expressões condicionais para devolver valores lógicos
slug: if-else-return-booleans
published: false
date: 14-03-2020
taxonomy:
    tag: [pydont, python, programming]
---

Não uses uma expressão `if-else` para devolver `True` ou `False`, i.e. não faças algo como

```py
def my_function():
    # some code here...
    if some_condition:
        return True
    else:
        return False
```

Podes, e provavelmente devias, devolver diretamente o valor de `some_condition`!

===

A versão mais _pythonica_ do código em cima seria

```py
def my_function():
    # some code here...
    return some_condition
```

Isto é mais fácil de explicar com um exemplo. Suponha-se que queres escrever uma função para verificar se uma _string_ começa com uma letra minúscula. Talvez te sintas na tentação de escrever o seguinte código:

```py
def word_starts_lowercase_letter(string):
    letters = "abcdefghijklmnopqrstuvwxyz"
    if string[0] in letters:
        return True
    else:
        return False
```

mas repara que é mais elegante e conciso escrever

```py
def word_starts_lowercase_letter(string):
    letters = "abcdefghijklmnopqrstuvwxyz"
    return string[0] in letters
```

Porque é que haveríamos de querer usar a versão mais curta, em vez da primeira? O meu argumento prende-se com uma particularidade que aprecio muito em Python: bom código Python pode ser lido como uma frase!

`string[0]` pode ser lido como _o primeiro caracter de `string`_, portanto a primeira versão do código diz

!!! Se o primeiro caracter de `string` estiver em `letters`, devolve `True`, caso contrário devolve `False`.

Mas a **segunda versão** do código diz

!!! Devolve se o primeiro caracter de `string` está em `letters` ou não.

Repara que cada leitura é bastante parecida com a versão do código correspondente (lembra-te que [_legibilidade importa_](../zen-of-python "readability counts")!). Dito isto, qual das versões do código é preferível?

Eu invoco a máxima que diz que [_"alinhado" é melhor que indentado_](../zen-of-python "flat is better than nested"); portanto, não vale a pena criar uma estrutura `if-else` para depois devolver o valor da própria expressão condicional. Mais vale devolver a própria expressão condicional!

De seguida incluí algumas funções para exemplificar este [py-don't][pydont]. Experimenta reescrever estas funções para remover as estruturas `if-else` que não são necessárias.

```py
def is_multiple(x, n):
    """Returns True if x is a multiple of n."""
    if x % n == 0:
        return True
    else:
        return False

def is_larger(l, n):
    """Returns True if the list l has more than n elements."""
    if len(l) > n:
        return True
    else:
        return False

def is_lowercase(s):
    """Returns True if the string s is in all lowercase letters."""
    lower = s.lower()
    if lower == s:
        return True
    else:
        return False

def is_missing(needle, haystack):
    """Returns True if the needle is not in the haystack."""
    if needle in haystack:
        return False
    else:
        return True
```

Isto fez sentido? Façam-me saber, deixando um comentário em baixo!

[zen-of-python]: ../pydont-zen-of-python
[pydont]: ../.