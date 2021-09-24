---
metadata:
    description: "Expressões com atribuições foram introduzidas na versão 3.8 do Python e neste artigo mostro como as usar, bem como problemas a evitar."
title: "Expressões com atribuições e o operador morsa := | Pydon't 🐍"
---

O "operador morsa" `:=` pode ser bastante útil, mas se o usares de forma
errada vais acabar por piorar o teu código, em vez de o melhorares.
Usa `:=` para tornar mais linear uma série de `if`s ou para reutilizar
valores intermédios.

===

![Código Python com uma má utilização do operador morsa.](_thumbnail.png)

(Se és novo aqui e não sabes o que é uma Pydon't, então talvez queiras começar por
ler a [Proclamação das Pydon'ts][manifesto].))

# Operador morsa

O operador morsa, que se usa com `:=` (dois pontos e um sinal de igual) foi
introduzido com a versão 3.8 do Python.
O operador morsa é usado em *expressões com atribuições*, o que significa que agora
podemos incluir atribuições dentro de expressões.
Antes da versão 3.8 as únicas atribuições que existiam em Python eram instruções
(statements).

Uma instrução de atribuição atribui um valor a um nome, só.
Uma expressão com uma atribuição permite utilizar imediatamente o valor que acabou
de ser atribuído.
Aqui está um exemplo que demonstra a diferença:

```py
>>> a = 3
>>> print(a)
3
>>> print(b = 3)
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
TypeError: 'b' is an invalid keyword argument for print()
>>> print(b := 3)
3
>>> b
3
```

Tal como visto no [PEP 572][pep572], atribuições com expressões bem utilizadas permitem
escrever código melhor: código que é mais claro e/ou que é mais rápido.

As expressões com atribuições devem ser evitadas quando forem obfuscar o código, mesmo
que a sua utilização permita poupar linhas de código.
Não queres [desrespeitar o Zen do Python][pydont-zen], e o Zen do Python recomenda que
escrevas código legível.

O pedaço de código que se segue é, na minha opinião, uma má utilização de uma
expressão com atribuição:

```py
import sys

if (i := input())[0] == "q" or i == "exit":
    sys.exit()
```

Eu penso que uma melhor alternativa seria

```py
import sys

i = input()
if i[0] == "q" or i == "exit":
    sys.exit()
```

A segunda alternativa (sem o `:=`) é muito mais fácil de ler do que a primeira,
apesar da utilização do `:=` ter poupado uma linha de código.

No entanto, boas utilizações de expressões com atribuições podem

 - tornar o código mais rápido,
 - fazê-lo mais expressivo e legível, e
 - encurtar o código.

# Exemplos em código

Seguem alguns exemplos de boas utilizações de expressões com atribuições.

## Controlar um ciclo while com inicialização

Considera o ciclo seguinte:

```py
inp = input()
while inp:
    eval(inp)
    inp = input()
```

Este código pode ser usado para criar um REPL de Python básico dentro do teu programa
Python, e o REPL termina assim que o input for vazio,
mas repara que o código é ligeiramente repetitivo.
Primeiro, há que inicializar `inp`, já que essa variável é usada na condição do ciclo,
mas no fim do ciclo também há que atualizar `inp` da mesma maneira.

Com uma expressão com atribuição, o código em cima poderia ser reescrito da seguinte
forma:

```py
while inp := input(" >> "):
    eval(inp)
```

Isto não só encurtou o código, como o tornou mais expressivo, ao pôr em evidência
que o input dado pelo utilizador (através da função `input`) é que está a controlar
o ciclo `while`.

## Reduzir ruído visual

Supõe que queres contar o número de zeros com que um número inteiro termina.
Uma maneira fácil de o fazer seria converter o número em texto, calcular o seu
comprimento, e depois subtrair o comprimento do mesmo pedaço de texto depois
de remover os zeros finais.
Poderíamos escrever isto da seguinte forma:

```py
def trailing_zeroes(n):
    s = str(n)
    return len(s) - len(s.rstrip("0"))
```

No entanto, é um pouco incomodativo que uma função tão simples e curta gaste uma
linha inteira com uma atribuição `s = str(n)`, que é algo tão trivial.
O operador morsa permite obviar essa questão:

```py
def trailing_zeroes(n):
    return len(s := str(n)) - len(s.rstrip("0"))
```

A função em cima pode ler-se como “*devolve o comprimento do texto que se obtém
de `n` menos o comprimento desse mesmo texto sem os zeros à direita*”, o que é
bastante simples de compreender.
Na minha opinião, esta utilização de `:=` melhorou a leitura da função, mas isto
é bastante objetivo, portanto estás à vontade para discordar de mim.

## Reutilizar valores intermédios em compreensão de listas

Supõe que estás a escrever uma compreensão de lista com um filtro `if`, mas
tal que o teste do filtro usa um valor que também queres usar para construir
a lista propriamente dita.
Por exemplo, podes ter uma lista de inteiros e podes querer guardar os fatoriais
dos inteiros cujos fatoriais acabam com mais de $50$ zeros.

Podias fazê-lo assim:

```py
from math import factorial as fact

l = [3, 17, 89, 15, 58, 193]
facts = [fact(num) for num in l if trailing_zeroes(fact(num)) > 50]
```

O problema do código anterior é que o fatorial de cada número está a ser calculado
duas vezes, e se os números ficarem grandes, isto pode tornar-se bastante demorado.
Se usares expressões com atribuições, o código pode ficar assim:

```py
from math import factorial as fact

l = [3, 17, 89, 15, 58, 193]
facts = [f for num in l if trailing_zeroes(f := fact(num)) > 50]
```

A utilização de `:=` permitiu reutilizar o cálculo intermédio do fatorial de `num`.

Repara que existem outras duas alternativas relativamente simples e que não usam
expressões com atribuições:

```py
from math import factorial as fact

l = [3, 17, 89, 15, 58, 193]
# Alternative 1
facts = [fact(num) for num in l]
facts = [num for num in facts if trailing_zeroes(num) > 50]
# Alternative 2
facts = [num for num in map(fact, l) if trailing_zeroes(num) > 50]
```

Repara que, apesar de parecerem equivalentes, a segunda alternativa das duas
de cima é mais eficiente quando `l` tem muitos elementos:
a primeira alternativa calcula a lista dos fatoriais *toda*, ao passo que a segunda
alternativa só vai calculando os fatoriais à medida que são necessários.
(Vou escrever sobre isto numa próxima Pydon't, por isso [subscreve][subscribe] para
teres a certeza que não te escapa nada!)

## Simplificar lógica linear

Imagina que chega uma altura no teu código em que tens de escolher uma operação
para aplicar aos teus dados, e há uma série de coisas que gostarias de testar...
Mas queres parar assim que atinges o primeiro sucesso.
Como exemplo muito simples, imagina que tens um pedaço de texto e queres ver se tem
um email ou um número de telemóvel.
Tu estás interessado em extrair o email e, se não houver nenhum, então tentas extrair
o número de telemóvel.
(Para simplificar o exemplo, vamos considerar apenas números de telemóvel com $9$
dígitos e emails `.com` só com letras.)

Poderias escrever algo assim:

```py
import re

string = input("Your contact info: >> ")
email = re.search(r"\b(\w+@\w+\.com)\b", string)
if email:
    print(f"Your email is {email.group(1)}.")
else:
    phone = re.search(r"\d{9}", string)
    if phone:
        print(f"Your phone is {phone.group(0)}.")
    else:
        print("No info found...")
```

Repara que o código em cima tem um `if` dentro de outro, apesar da lógica do programa
ser linear: tentamos várias coisas e paramos assim que uma delas funcionar.
Com expressões com atribuições isto pode ser simplificado:

```py
import re

string = input("Your contact info: >> ")
if email := re.search(r"\b(\w+@\w+\.com)\b", string):
    print(f"Your email is {email.group(1)}.")
elif phone := re.search(r"\d{9}", string):
    print(f"Your phone is {phone.group(0)}.")
else:
    print("No info found...")
```

# Conclusão

Expressões com atribuições permitem atribuir um nome a um valor intermédio numa
expressão, que depois pode ser usado para clarificar um pedaço de código ou para
poupar tempo de execução de um programa, por exemplo.
No entanto, más utilizações de expressões com atribuições podem resultar em código
muito pouco legível, e por isso é necessário usar o sentido crítico quando se está
a ponderar usar uma expressão com atribuição no código.

---

Não te esqueças de deixar uma reação a este artigo se gostaste desta Pydon't e
partilha-a com amigos e outros colegas que programem em Python.

Já agora, [subscreve a newsletter][subscribe] para teres a certeza que não te escapa
nem uma única Pydon't!

# Referências (em inglês)

 - Python 3 Documentation, What's New in Python, What's new in Python 3.8 - Assignment expressions, [https://docs.python.org/3/whatsnew/3.8.html#assignment-expressions](https://docs.python.org/3/whatsnew/3.8.html#assignment-expressions).
 - PEP 572 -- Assignment Expressions, [https://www.python.org/dev/peps/pep-0572][pep572].
 - Real Python, "Assignment Expressions: The Walrus Operator", [https://realpython.com/lessons/assignment-expressions/](https://realpython.com/lessons/assignment-expressions/).

Consultadas a 26 de janeiro de 2021.

[subscribe]: https://mathspp.com/subscribe
[pydont-zen]: /blog/pydonts/pydont-disrespect-the-zen-of-python
[manifesto]: /blog/pydonts/pydont-manifesto
[pep572]: https://www.python.org/dev/peps/pep-0572
