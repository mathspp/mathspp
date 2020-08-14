# [Brainetry] and [Roj]

Cada um destes repositórios contém um interpretador para uma linguagem de programação.

[Brainetry] é uma linguagem de programação que eu inventei, inspirado nas linguagens `brainf*ck` e `Poetic`.

Em baixo incluí um programa em `Brainetry` como exemplo. Ler o programa deve ser suficiente para perceber o que ele faz:

```
Este programa nunca termina.
Não só nunca termina como também vai imprimir
todos os números naturais, começando no um.
Que tarefa tão simples.
E fi-lo em apenas cinco linhas curtas de código.
```

A linguagem de programação [Roj] nasceu [porque eu estava a escrever um interpretador de Pascal][roj-post] mas a série de artigos que eu estava a seguir não estava completa ainda e por isso o interpretador ficou incompleto. Aqui em baixo podem ver um exemplo de um programa em `Roj`:

```
$ Calcula o factorial do número dado como input $
result = 1;
readint n; $ Ask for user input $
while n > 0 do
    result = result * n;
    n = n - 1;
end;
out result;
```

[Brainetry]: https://github.com/RojerGS/Brainetry
[Roj]: https://github.com/RojerGS/Roj
[roj-post]: https://mathspp.com/blog/creating-programming-language-from-scratch
