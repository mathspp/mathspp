---
title: Py-don't uses inteiros em vez de valores lógicos
metadata:
    description: "Neste py-don't vou falar do que é que 'Truthy' e 'Falsy' são em Python."
---

Se quiseres criar um ciclo `while` infinito, não faças isto:

```py
while 1:
    print("Parem, por favor..!")
```

O Python tem _built-ins_ para os valores lógicos, não há necessidade de os substituir por números inteiros!

===

A versão mais _pythonica_ deste código seria

```py
while True:
    print("Parem, por favor..!")
```

As pessoas talvez escrevam a primeira versão porque têm já conhecimentos de C, onde seria preciso escrever algo como

```c
while (1) {
    printf("Stop this, please!");
}
```

ou então porque os objetos em Python podem ser tratados como valores lógicos implicitamente, o que significa que valores como números inteiros, _strings_ e listas podem ser usadas nas condições dos `if`s e `while`s.

Há que relembrar que [_explícito é melhor que implícito_][zen-of-python "Explicit is better than implicit."] e não estamos a ganhar nada por estar a usar esta conversão implícita, portanto podemos perfeitamente não a usar.

Esta funcionalidade que converte objetos em valores lógicos implicitamente, por vezes referida como o valor de _truthiness_ (que advém de _truth_) de um objeto, não foi criada para que possamos substituir as constantes `True` e `False`. Quando queremos escrever um valor lógico específico, devemos usar `True` ou `False`.

Em baixo inclúo algumas linhas de código com alguns objetos comuns e os respetivos valores que são _Truthy_ e _Falsy_:

```py
# 0 is Falsy, all other ints are Truthy so this prints from -10 to 9, except 0
for i in range(-10, 10):
    if i:
        print(i)

# The empty string is Falsy, all other strings are Truthy
s = ""
if s:
    print(s)
s = "sad ddfa fda fda"
if s:
    print(s)

# The empty list is Falsy, all other lists are Truthy
l = []
if l:
    print(l)
l = [0, ""]
if l:
    print(l)
```

Um exemplo de utilização _pythonica_ desta capacidade implícita de conversão pode ser, por exemplo, para processarmos elementos de uma lista enquanto a lista não está vazia:

```py
l = [67, 2, 7, 4, 8]
while l:
    last = l.pop()
    if last % 2:
        l.append(last - 1)
        l.append(last - 3)
    print(last, end=" ")

# outputs "8 4 7 4 6 2 67 64 66 "
```

Isto fez sentido? Façam-me saber, deixando um comentário em baixo!

[zen-of-python]: ../pydont-zen-of-python