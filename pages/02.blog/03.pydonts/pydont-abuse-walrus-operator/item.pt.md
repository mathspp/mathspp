---
metadata:
    description: Neste py-don't vou falar do operador := e das suas não-utilizações.
title: Py-don't abuses do operador `:=`
---

Não uses o _"operador da morsa"_ `:=` de forma retorcida!

```py
import sys

if (i := input())[0] == "q" or i == "exit":
    sys.exit()
```

Este pedaço de código interrompe o programa se o _input_ começar com `"q"` ou se o _input_ for `"exit"`. Mas será que tem bom aspeto?

===

Na altura em que isto foi escrito, o operador `:=` foi introduzido há relativamente pouco tempo (podem ler sobre a introdução deste operador na linguagem na [PEP 572][pep-572]). Isto quer dizer que as pessoas ainda se estão a habituar ao operador e a tentar perceber como o usar de forma eficaz. Reitero que o objetivo do operador `:=` está muito bem explicado na [PEP 572][pep-572] e que também encontram lá bons exemplos de utilização do operador `:=`.

Uma versão mais _pythonica_ do código em cima seria

```py
import sys

i = input()
if i[0] == "q" or i == "exit":
    sys.exit()
```

Este excerto de código usa mais uma linha mas faz com que o código seja muito mais legível. Não se esqueçam que [legibilidade importa](../zen-of-python "readability counts") e que [mais vale bonito do que feio](../zen-of-python "beautiful is better than ugly")!.

Pessoalmente, acho que o operador `:=` é extremamente útil neste tipo de situações: queremos escrever um ciclo `while` em que a guarda do ciclo e o corpo do ciclo usam a mesma variável _e_ essa variável tem de ser inicializada de alguma maneira. Por exemplo, vamos escrever um ciclo simples que recebe uma linha de texto do utilizador, verifica se essa linha é vazia e, se não for, executa-a. Sem o operador `:=` eu escreveria algo como

```py
inp = input()
while inp:
    eval(inp)
    inp = input()
```

ou

```py
while True:
    inp = input()
    if not inp:
        break
    eval(inp)
```

mas nenhuma destas alternativas é melhor que a que se segue, bastante mais curta, expressiva e elegante:

```py
while (inp := input()):
    eval(inp)
```

De seguida encontram alguns exemplos de utilização do operador `:=` retirados e/ou adaptados da [PEP 572][pep-572].

#### Encontrar uma "testemunha" de algo numa lista

```py
import random
random.seed(0)  # for reproducibility

ints = [random.randint(0, 100) for _ in range(100)]
if any((witness := elem) % 10 == 0 for elem in ints):
    print(f"{witness} is the first multiple of 10 in the list!")
else:
    print("No multiples of 10 found.")
```

Este excerto de código imprime `100 is the first multiple of 10 in the list!` porque atravessa a lista aleatória (`[49, 97, 53, 5, 33, 65, 62, 51, 100, 38, ...]`) e procura um múltiplo de 10. O primeiro múltiplo encontrado é o `100`.

#### Manter um histórico de alterações numa "list comprehension"

```py
import random
random.seed(0)  # for reproducibility

some_list = [random.randint(-100, 100) for _ in range(50)]
total = 0
partial_sums = [total := total + elem for elem in some_list]
print(partial_sums)
```

Este excerto de código imprime `[-2, 92, 99, 9, -25, ...]` que são as $5$ primeiras somas parciais da lista `some_list`, cujos valores são `[-2, 94, 7, -90, -34, ...]`.

#### Reutilizar valores caros de calcular

```py
def fib(n):
    """This could be made more efficient in many different ways!"""
    if n <= 1:
        return n
    else:
        return n * fib(n-1)

n = 17
print(f"Fib {n} is {(f:=fib(n))} and fib {n+1} is {(n+1)*f}")
```

Este excerto imprime `Fib 17 is 355687428096000 and fib 18 is 6402373705728000`, que corresponde aos valores de $17!$ e $18! = 18 \times 17!$.

---

Se encontrares uma situação em que queres mesmo usar o operador `:=` mas ele não assentar bem com o resto do código envolvente... Então talvez não seja boa ideia usares o operador `:=`! [Não desrespeites o _Zen of Python_][zen-of-python]!

Isto fez sentido? Façam-me saber, deixando um comentário em baixo!

[zen-of-python]: ../zen-of-python
[pep-572]: https://www.python.org/dev/peps/pep-0572
[pydont]: ../.