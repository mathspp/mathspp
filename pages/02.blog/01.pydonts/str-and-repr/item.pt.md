---
metadata:
    description: "Ao contrário do que às vezes se pensa, há diferenças muito claras entre str e repr em Python!"
title: "str e repr | Pydon't 🐍"
---

Os métodos `str` e `repr` do Python podem ser parecidos, mas não são iguais.
Usa `str` sempre que precisares de representações bonitas de objetos e usa
`repr` para depurares código.
Seguindo a mesma lógica, deves ter estes casos típicos de utilização em mente
quando as tuas classes implementam os métodos `__str__` e `__repr__`.

===

![Código Python que mostra um esqueleto de uma classe com as assinaturas dos métodos __str__ e __repr__.](thumbnail.png)

(Se és novo aqui e não sabes o que é uma Pydon't, então talvez queiras começar por
ler a [Proclamação das Pydon'ts][manifesto].))

# `str` e `repr`

Python tem dois mecanismos que permitem converter um objeto em texto para que possas
olhar para ele e imprimi-lo.
Os dois mecanismos de que falo são a classe `str` e a função `repr`.

Costuma haver confusão quanto à diferença de propósito destes dois mecanismos, mas a
diferença é simples e clara.
A classe `str` é usada para quando queres converter algo para o tipo de dados de texto
ou para quando precisas de uma representação textual do teu objeto que seja legível,
tipicamente para mostrar a um utilizador do teu código.
Por outro lado, a função `repr` é usada para criar uma representação *unívoca* do
seu argumento.

Os utilizadores finais geralmente usam `str` porque precisam de imprimir texto bonito,
ao passo que programadores geralmente usam `repr` para conseguirem depurar o seu código
em condições e para saberem exatamente para o que é que estão a olharr.
Por exemplo, repara na seguinte sessão interativa:

```py
>>> print(3)
3
>>> print("3")
3
>>> 3
3
>>> "3"
'3'
```

A função `print` chama a classe `str` no seu argumento antes de o imprimir, fazendo com
que tanto o inteiro `3` como o texto `"3"` sejam impressos da mesma maneira:
não tens como saber se o objeto original era um inteiro ou texto.
De seguida, vês que se te limitares a escrever `3` ou `"3"` na sessão interativa,
o REPL devolve uma representação unívoca do objeto:
consegues distinguir o caso em que o objeto original era um inteiro do caso em que
o objeto original era texto.
A função `repr` também é usada quando tens objetos dentro de um "contentor", como uma
lista ou um dicionário, porque estes tipos de dados geralmente definem o seu
comportamento de `str` à custa do seu comportamento `repr`, tal como podes verificar
se leres o [PEP 3140][pep3140] ou se olhares para a seguinte sessão:

```py
>>> [3, "3"]
[3, '3']
>>> print([3, "3"]) 
[3, '3']
>>> str([3, "3"]) == repr([3, "3"])
True
```

# Os Métodos Dunder `__str__` e `__repr__`

É provável que queiras especificar como é que os teus objetos devem ser impressos
quando defines as tuas próprias classes em Python, já que o comportamento por defeito
do Python não é particularmente útil:

```py
>>> class A:
...     pass
... 
>>> a = A()
>>> print(a)
<__main__.A object at 0x012DF640>
>>> a
<__main__.A object at 0x012DF640>
```

Se quiseres que os teus objetos sejam impressos de forma decente, vais ter que
implementar os métodos dunder `__str__` e `__repr__` (*dunder* vem de *double
underscore*), e as tuas implementações devem ter em mente os casos em que `str` e
`repr` são utilizadas:
a implementação de `__str__` deve produzir uma representação bonita e legível do teu
objeto, ao passo que o método `__repr__` deve produzir uma representação unívoca do
objeto, e preferencialmente uma expressão que possa ser usada para o reconstruir.

! Se não estás confortável com os "métodos mágicos" (os métodos dunder) de Python,
! então talvez queiras [subscrever][subscribe] a newsletter, eu vou escrever mais
! sobre o assunto em breve.
! Até lá, talvez queiras [espreitar a documentação de Python](https://docs.python.org/3/reference/datamodel.html#data-model).

Quando implementas as tuas próprias classes, sugiro que comeces com o método 
`__repr__`, já que o método `__str__` vai usar o método `__repr__` por defeito,
mas se só implementares o método `__str__` então `__repr__` continua a não ser
nada útil.

Se só implementares `__str__`:

```py
>>> class A:
...     def __str__(self):
...             return "A"
... 
>>> a = A()
>>> a
<__main__.A object at 0x01600760>
>>> print(a)
A
```

Se só implementares `__repr__`:

```py
>>> class A:
...     def __repr__(self):
...             return "A"
... 
>>> a = A()
>>> a
A
>>> print(a)
A
```

# Exemplos em código

## `datetime`

O módulo `datetime` de Python permite-te trabalhar com datas de forma fácil.
Uma data simples pode ser criada da seguinte forma:

```py
>>> import datetime
>>> date = datetime.datetime(2021, 2, 2)
```

Agora que temos uma data do tipo `datetime.datetime` na variável `date`, podemos
comparar a sua `repr` com a sua `str`:

```py
>>> print(repr(date))
datetime.datetime(2021, 2, 2, 0, 0)
>>> print(str(date))
2021-02-02 00:00:00
```

Podemos ver que `repr(date)` pode ser usado para recriar o objeto:

```py
>>> date == datetime.datetime(2021, 2, 2, 0, 0)
True
>>> date == eval(repr(date))
True
```

Por outro lado, `str(date)` cria uma representação bonita da data com que estamos
a trabalhar.
Repara que, depois de usarmos `str` na nossa variável, nem somos capazes de dizer
que estávamos a trabalhar com um objeto do tipo `datetime.datetime`.

## Ponto 2D

Um exemplo em que vais querer implementar os métodos mágicos `__str__` e `__repr__` é
se estiveres a implementar uma classe que represente pontos bidimensionais,
por exemplo porque vais trabalhar com imagens, ou jogos, ou mapas, ...

Ignorando qualquer outro método que queiras implementar na tua classe, o teu código
poderia ter este aspeto:

```py
class Point2D:
    """A class to represent points in a 2D space."""

    def __init__(self, x, y):
        self.x = x
        self.y = y

    def __str__(self):
        """Provide a good-looking representation of the object."""
        return f"({self.x}, {self.y})"

    def __repr__(self):
        """Provide an unambiguous way of rebuilding this object."""
        return f"Point2D({repr(self.x)}, {repr(self.y)})"

p = Point2D(0, 0) # the origin.
print(f"To build the point {p} in your code, try writing {repr(p)}.")
```

Se executares este código então vais ver que a mensagem
`To build the point (0, 0) in your code, try writing Point2D(0, 0).`
vai ser impressa.
O utilizador final é bem capaz de estar habituado a trabalhar com pontos
bidimensionais, e portanto chega-lhe a representação usual `(x, y)`.
No entanto, durante a depuração do código, o prefixo `Point2D` é super útil
porque te ajuda a distinguir um tuplo de objetos do tipo `Point2D`.

# Conclusão

Quando implementares as tuas próprias classes, é provável que queiras implementar
o método mágico `__repr__`, bem como o método `__str__` se precisares de imprimir
objetos para um utilizador final ver.
`__str__` e `str` são usados para criar texto legível e bonito, ao passo que o
propósito de `__repr__` e `repr` é criar representações unívocas dos teus objetos.

---

Não te esqueças de deixar uma reação a este artigo se gostaste desta Pydon't e
partilha-a com amigos e outros colegas que programem em Python.

Já agora, [subscreve a newsletter][subscribe] para teres a certeza que não te escapa
nem uma única Pydon't!

# Referências (em inglês)

 - Python 3 Documentation, The Python Language Reference, Data model, __repr__ and __str__, [https://docs.python.org/3/reference/datamodel.html#object.__repr__](https://docs.python.org/3/reference/datamodel.html#object.__repr__).
 - Python 3 Documentation, The Python Standard Library, Built-in Functions, [https://docs.python.org/3/library/functions.html](https://docs.python.org/3/library/functions.html).
 - Python 3 Documentation, The Python Standard Library, Built-in Types, str, [https://docs.python.org/3/library/stdtypes.html#str](https://docs.python.org/3/library/stdtypes.html#str).
 - PEP 3140 -- str(container) should call str(item), not repr(item), [https://www.python.org/dev/peps/pep-3140/][pep3140].
 - Stack Overflow, "Purpose of Python's __repr__", [https://stackoverflow.com/questions/1984162/purpose-of-pythons-repr](https://stackoverflow.com/questions/1984162/purpose-of-pythons-repr).
 - dbader.org, "Python String Conversion 101: Why Every Class Needs a “repr”", [https://dbader.org/blog/python-repr-vs-str](https://dbader.org/blog/python-repr-vs-str).

Consultadas pela última vez a 2 de fevereiro de 2021.

[subscribe]: https://mathspp.com/subscribe
[manifesto]: /blog/pydonts/pydont-manifesto
[pep3140]: https://www.python.org/dev/peps/pep-3140/
