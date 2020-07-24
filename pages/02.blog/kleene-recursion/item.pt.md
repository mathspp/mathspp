---
title: Funções recursivas e a definição de Kleene
date: 17-10-2017
slug: recursion-by-kleene
taxonomy:
    category: blogpost
    tag: [mathematics, recursion, computation theory]
---

Neste artigo eu divago um pouco sobre a maneira como um certo matemático definiu o que era uma função recursiva...

===

Em Teoria da Computação, é interessante estudar certas propriedades de funções e as funções que satisfazem essas propriedades. Depois consideramos o conjunto de todas as funções que satisfazem essas propriedades. Um desses conjuntos é o conjunto $R$ das funções recursivas, assim como Kleene as definiu. Para definir $R$, Kleene enumera uma série de funções primitivas que diz estarem em $R$ e depois define operações que mantém as funções em $R$.

Para efeitos do artigo que se segue, vou limitar-me a enumerar essas funções primitivas e essas operações de funções para que quem está a ler saiba quais é que são as _regras do jogo_ (note-se que o que vou listar em baixo é muito parecido com o que se pode encontrar [neste](https://en.wikipedia.org/wiki/%CE%9C-recursive_function) link).

As funções primitivas são:

 - A função constante de aridade 0, uma para cada número natural;
 - A função zero de aridade 1, que devolve 0 para qualquer argumento;
 - A função sucessor de aridade 1, $x \mapsto x + 1$;
 - A função projeção $projection(a, b)$ de aridade $a$, que devolve o $b$-ésimo argumento. Por exemplo, $projection(3, 2)(a, b, c) = b$.

Posto isto, as operações consideradas (que enviam funções de $R$ para outras funções em $R$) são:

 - Composição de funções;
 - Agregação, que representamos com $<>$, e que pode ser aplicada a $n$ funções se todas tiverem a mesma aridade. Por exemplo, $<f, g>(a, b) = (f(a,b), g(a,b))$, desde que $f$ e $g$ tenham aridade 2;
 - Recursão: se a aridade de $g$ é $n + 2$ e a aridade de $f$ é $n$, então $h = rec(f, g)$ está definida de tal forma que $h(X, 0) = f(X), h(X, k+1) = g(X, k, h(X, k))$ (onde $X$ representa os primeiros $n$ argumentos);
 - Minimização: $h = \min(f)$, onde $f$ é uma função com aridade $n + 1$, é uma função com aridade $n$ tal que $h(X) = i \iff f(X, i) = 0$ e se para qualquer $k < i$, $f(X, k) \neq 0$.

Quando aprendi isto, decidi tentar definir algumas funções em termos das funções primitivas e das operações listadas. Decidi implementar essas construções em Python e depois construí algumas funções não primitivas. As operações entre funções podem ser encontradas [aqui](https://github.com/RojerGS/projects/blob/master/kleeneRecursion/basicFunctions.py) e as implementações das funções a partir dessas operações estão [aqui](https://github.com/RojerGS/projects/blob/master/kleeneRecursion/arithmetics.py)

Para o leitor mais interessado, sugiro que tentem construir as seguintes funções não primitivas antes de verem o código. Esta é uma lista completa de todas as funções que implementei:

 - adição de dois números naturais;
 - predecessor (i.e. $x \mapsto x - 1$);
 - zero_sub: $zero\_sub(x, y) = \max(x - y, 0)$;
 - mod_sub: $mod\_sub(x, y) = |x - y|$;
 - a função sinal $sgn(x)$ que devolve $1$ se $x$ é positivo e $0$ caso contrário;
 - $neq(x, y) = 1 \iff x \neq y$ e 0 caso contrário;
 - $eq(x, y) = 1 - neq(x, y)$;
 - $geq(x, y) = 1 \iff x \geq y$, 0 caso contrário;
 - a função "maior", $greater(x, y) = 1 \iff x > y$, 0 caso contrário;
 - $leq(x, y) = 1 \iff x \leq y$ e 0 caso contrário;
 - a função "menor", $less(x, y) = 1 \iff x < y$ e 0 caso contrário;
 - $subtraction(x, y) = x - y \iff x \geq y$, caso contrário está indefinida;
 - $dup(x) = 2x$;
 - multiplicação de dois números naturais;
 - a função fatorial;
 - a função quociente, $quotient(n, d)$ devolve o maior inteiro $k$ tal que $kd \geq n$;
 - a função resto, $remainder(n, d)$ devolve o resto da divisão inteira de $n$ por $d$, i.e. $remainder(n, d) = n - quotient(n, d)\times d$.