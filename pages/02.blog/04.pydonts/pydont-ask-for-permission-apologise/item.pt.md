---
metadata:
    description: "Há muitas situações em que é melhor usar um try para lidar com um erro do que usar um if para o tentar evitar."
title: "Pydon't peças permissão, mas sim perdão"
---

Em Python, se queres fazer uma operação que pode dar um erro, há muitas situações
em que "pedir desculpa é melhor que pedir permissão".
Isto significa que deves utilizar um bloco `try` para capturar o erro, em vez de
usares um `if` para impedir que o erro aconteça.

===

![Código Python que compara a metodologia EAFP e LBYL](thumbnail.png)

(Se és novo aqui e não sabes o que é uma Pydon't, então talvez queiras começar por
ler a [Proclamação das Pydon'ts][manifesto].))

# EAFP e LBYL

"EAFP" é uma sigla inglesa que significa "Easier to Ask for Forginess than Permission",
que quer dizer que "é mais fácil pedir perdão do que pedir permissão".
EAFP é uma maneira de estruturar código que pode dar erros.
EAFP é mais ou menos o oposto do "LBYL", outra sigla inglesa que quer dizer
"olha antes de saltares".

LBYL significa que começamos por verificar se uma determinada operação pode ser
executada com sucesso, e só depois de termos essa garantia é que executamos
a operação.
Por exemplo, se quiserem pedir um inteiro positivo ao utilizador, com um valor
por defeito igual a 1, podem escrever o código

```py
print("Escreve um inteiro positivo (1 é o valor por defeito):")
s = input(" >> ")
if s.isnumeric():
    n = int(s)
else:
    n = 1
```

(No código em cima, usamos o método `str.isnumeric` para ver se a string é um inteiro
positivo válido. Experimenta correr o código `print(str.isnumeric.__doc__)` na tua
consola Python.)

Se escreveres código EAFP, primeiro tentas executar a operação que queres mesmo fazer.
Se utilizares um bloco `try`, podes garantir que, caso a tua operação falhe, consegues
lidar com isso.
No nosso exemplo, isso quer dizer que tentamos converter a string para um número
inteiro e, caso isso falhe, então definimos o valor por defeito:

```py
print("Escreve um inteiro positivo (1 é o valor por defeito):"))
s = input(" >> ")
try:
    n = int(s)
except ValueError:
    n = 1
```

Usamos um `except ValueError` porque se tentarmos converter para inteiro, uma string
que não contém um inteiro, é um `ValueError` que obtemos:

```py
>>> int("345")
345
>>> int("3.4")
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
ValueError: invalid literal for int() with base 10: '3.4'
>>> int("asdf")
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
ValueError: invalid literal for int() with base 10: 'asdf'
```

# EAFP em vez de LBYL?

Escrever código que segue o estilo EAFP pode trazer várias vantagens, e eu enumero
algumas agora, ilustrando-as com exemplos de código.

## Evitar redundância desnecessária

Às vezes, código escrito com um estilo EAFP permite evitar redundâncias ou cálculos
desnecessários.
Imagina que tens um dicionário de onde queres extrair o valor associado com uma
determinada chave que pode não existir.

Com o estilo LBYL, escreverias algo do estilo:

```py
d = {"a": 1, "b": 42}
print("A que chave queres aceder?")
key = input(" >> ")
if key in d:
    print(d[key])
else:
    print(f"Não encontro a chave '{key}'")
```

Quando a chave que tu queres existe, o código acede duas vezes ao dicionário:
primeiro vemos se `key` existe dentro do dicionário, e depois então retiramos o valor
associado a essa chave.
Isto é mais ou menos o mesmo que abrir uma caixa para ver se está vazia, e fechá-la.
Depois, se a caixa não estiver vazia, então voltas a abri-la e retiras o seu conteúdo
cá para fora.
Farias algo assim na vida real?

Com código EAFP, podes abrir a caixa e retirar o que está lá dentro à primeira:

```py
d = {"a": 1, "b": 42}
print("A que chave queres aceder?")
key = input(" >> ")
try:
    print(d[key])
except KeyError:
    print(f"Não encontro a chave '{key}'")
```

Este estilo de operação é tão comum que até existe um método, `dict.get`, que está
alinhado com a linha de pensamento EAFP.
O método `dict.get` pode ser usado para aceder ao valor associado a uma determinada
chave, mas ao mesmo tempo obter um valor por defeito caso a chave não exista no
dicionário:

```py
d = {"a": 1, "b": 42}
print("A que chave queres aceder?")
key = input(" >> ")
print(d.get(key, None))
```

Tenta correr o código em cima e escreve chaves que não estejam no dicionário `d`.
Vais ver que, nesses casos, o código imprime `None` na consola.

## EAFP pode ser mais rápido

Se tu souberes que os erros são muito raros, então EAFP é mais rápido:
só tens de executar uma operação (a que te interessa mesmo) em vez de duas
(a verificação e a operação de interesse, propriamente dita).

A título de exemplo, vamos olhar para o código do exemplo anterior, e vamos usar
o módulo `timeit` para ver qual dos dois pedaços de código é mais rápido, *se*
soubermos que a string *pode* ser convertida num número inteiro:

```py
>>> import timeit
>>> eafp = """s = "345"
... try:
...     n = int(s)
... except ValueError:
...     n = 0"""
>>> timeit.timeit(eafp)
0.1687019999999393
```

Aqui definimos `s` com um valor legal para que o tempo que eu demoro a escrever
um número inteiro na consola não seja contabilizado na cronometragem do código.
Para além disso, a função `timeit` do módulo `timeit` está a correr o código
[imensas vezes](https://docs.python.org/3/library/timeit.html#timeit.timeit)
e eu não quero ter de escrever um milhão de números inteiros na consola.

Agora compara aquele valor com o tempo do código LBYL:

```py
>>> lbyl = """s = "345"
... if s.isnumeric():
...     n = int(s)
... else:
...     n = 0"""
>>> timeit.timeit(lbyl)
0.30682630000001154
```

O código LBYL é quase duas vezes mais lento.
Se conseguires fazer com que a operação de interesse só falhe muito raramente,
então consegues poupar tempo se escreveres código EAFP.

## LBYL pode resultar em erro à mesma

Quando o teu código interage com o ambiente, por exemplo porque acede à Internet
ou porque interage com o sistema operativo, então pode acontecer que, depois de
fazeres a verificação mas *antes* de executares a tua operação, a tua operação
já não possa ser executada sem resultar em erro.

Por exemplo, imagina que tens código que lê alguns ficheiros.
Obviamente só podes ler ficheiros que existam, portanto uma tentativa LBYL
de ler um ficheiro arbitrário seria algo como

```py
import pathlib

print("Que ficheiro queres ler?")
filepath = input(" >> ")
if pathlib.Path(filepath).exists():
    with open(filepath, "r") as f:
        contents = f.read()
    # Do something with the contents.
else:
    print("Woops, esse ficheiro não existe!")
```

Se o teu código estiver num computador que pode ser acedido por vários utilizadores,
ou se houver outros programas a interagir com os ficheiros do computador, pode
acontecer que o teu `if` encontre o ficheiro, e que portanto avalie a condição
como `True`, mas depois um agente externo pode apagar o ficheiro e a tua tentativa
de abrir o ficheiro com o `with` falha, resultando num erro que termina o teu
programa.
Se estiveres a escrever código para um programa que **não pode** falhar, tens de
ter isto em conta.
Ou, então, se por algum motivo houver código demorado a correr entre a condição
do `if` e a operação importante.

Se usares uma alternativa EAFP, o código ou lê o ficheiro ou não lê, mas ambos
os casos são geridos de forma apropriada:

```py
print("Que ficheiro queres ler?")
filepath = input(" >> ")
try:
    with open(filepath, "r") as f:
        contents = f.read()
except FileNotFoundError:
    print("Woops, esse ficheiro não existe!"))
else:
    # Do something with the contents.
    pass
```

O `else` a seguir ao `try` serve para garantir que só corres o código que trabalha
com a variável `contents` se conseguires ler o ficheiro.
(Mais à frente hei de escrever uma Pydon't sobre isto!)

## Prevenção contra vários tipos de falhas

Se quiseres executar uma operação que pode falhar de várias formas diferentes,
pode ser bastante mais fácil enumerar os vários erros em vez de usar um `if`
enorme para garantir todas as condições necessárias à operação.

Por exemplo, se quiseres utilizar uma função externa que pode resultar
em vários erros diferentes, é bastante fácil escrever um bloco `try` elegante e
que cobre todos os casos necessários.

Imagina que queres usar uma função que recebe uma string, que representa um inteiro,
e que devolve o seu inverso, mas a pessoa que escreveu essa função não valida o
argumento: limita-se a pegar na string, a convertê-la num inteiro com `int` e
depois divide 1 por esse número:

```py
def get_inverse(num_str):
    return 1 / int(num_str)
```

Se quiseres usar esta função depois de pedir um valor ao utilizador,
podes antever que o utilizador não escreva realmente um inteiro ou que, então,
escreva o número 0, que resulta num erro do tipo `ZeroDivisionError`.
Podes resolver isto com o código que se segue, alinhado com o princípio EAFP:

```py
print("Escreve um inteiro:")
s = input(" >> ")
try:
    print(get_inverse(s))
except ValueError:
    print("Eu pedi um inteiro!")
except ZeroDivisionError:
    print("0 não tem inverso!")
```

Como farias isto, se seguisses o princípio LBYL?
Talvez escrevesses

```py
print("Escreve um inteiro:")
s = input(" >> ")
if s.isnumeric() and s != "0":
    print(get_inverse(s))
elif not s.isnumeric():
    print("Eu pedi um inteiro!")
else:
    print("0 não tem inverso!")
```

Mas agora estás a usar a função `isnumeric` duas vezes.
E `isnumeric` nem sequer funciona para números negativos.
E se o utilizador escrever `" 3"`?
`isnumeric` também falha neste caso, mas `" 3"` pode ser convertido em `3` com
a função `int`.
E se o utilizador escrever `"000"`? Isto ainda é `0`...
Acho que já percebeste o meu ponto de vista.

# Conclusão

O princípio EAFP é uma boa alternativa ao princípio LBYL.
Em certas situações, como as descritas em cima, até é melhor.
Quando estás a escrever código, tenta considerar os pontos a favor e contra
as duas alternativas e, em particular, não te esqueças de considerar a alternativa
EAFP!

O princípio EAFP não é o melhor de todos *em todas as situações*, mas é um princípio
que leva a código legível e eficiente!

---

Não te esqueças de deixar uma reação a este artigo se gostaste desta Pydon't e
partilha-a com amigos e outros colegas que programem em Python.
Tens algum exemplo de uma situação em que código EAFP fica melhor que LBYL?
Ou um caso em que EAFP não era, de todo, a melhor solução?
Partilha connosco nos comentários!

Já agora, [subscreve a newsletter][subscribe] para teres a certeza que não te escapa
nem uma única Pydon't!

# Referências (em inglês)

 - PEP 463 -- Exception-catching expressions, [https://www.python.org/dev/peps/pep-0463/](https://www.python.org/dev/peps/pep-0463/)
 - Python 3 Documentation, The Python Standard Library, Debugging and Profiling, `timeit`, [https://docs.python.org/3/library/timeit.html][timeit].
 - Python 3 Documentation, The Python Tutorial, Errors and Exceptions, [https://docs.python.org/3/tutorial/errors.html](https://docs.python.org/3/tutorial/errors.html).
 - Microsoft Devblogs, Idiomatic Python: EAFP versus LBYL, [https://devblogs.microsoft.com/python/idiomatic-python-eafp-versus-lbyl/](https://devblogs.microsoft.com/python/idiomatic-python-eafp-versus-lbyl/).
 - Stack Overflow, "What is the EAFP principle in Python?", [https://stackoverflow.com/questions/11360858/what-is-the-eafp-principle-in-python](https://stackoverflow.com/questions/11360858/what-is-the-eafp-principle-in-python).
 - Stack Overflow, "Ask forgiveness not permission - explain", [https://stackoverflow.com/questions/11360858/what-is-the-eafp-principle-in-python](https://stackoverflow.com/questions/11360858/what-is-the-eafp-principle-in-python).

Consultadas a 19 de janeiro de 2021.

[subscribe]: https://mathspp.com/subscribe
[manifesto]: /blog/pydonts/pydont-manifesto
[timeit]: https://docs.python.org/3/library/timeit.html
