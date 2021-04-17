---
metadata:
    description: Este artigo contém a minha proposta de solução para um dos problemas deste blogue.
title: 'Solução #023 - adivinha o polinómio'
---

Este artigo contém a minha solução proposta para o [Problema #023 - adivinha o polinómio][prob]. Por favor não leias esta solução se ainda não tentaste resolver [o problema][prob] a sério.

===

### Solução

A melhor estratégia funciona com apenas dois passos para *qualquer* polinómio.

Se ainda não conseguiste resolver este problema, tenta outra vez agora que já
sabes que há uma estratégia que funciona em dois passos.
É mais fácil descobrir uma estratégia que funcione num número fixo de passos
do que tentar inventar uma estratégia do zero.

Vamos supor que

$$
p(n) = \sum_{k = 1}^N c_k n^k
$$

é o polinómio secreto, onde $N$ é o grau (desconhecido) do polinómio e os coeficientes
$c_k$, $k < N$ são inteiros não negativos.

A primeira coisa a fazer é perguntar por $p(1)$, porque isso nos dá um valor máximo
para cada coeficiente:

$$
p(1) = \sum_{k = 1}^N c_k
$$

e todos os $c_k$ são não-negativos, logo temos a certeza que para qualquer $i$,

$$
c_i \leq \sum_{k = 1}^N c_k = p(1) ~ .
$$

Agora que sabemos que nenhum coeficiente é maior que $p(1)$, vamos definir
$b = p(1) + 1$ e perguntar por $p(b)$.
Se escrevermos o resultado de $p(b)$ em base $b$, então os dígitos de $p(b)$ na base
$b$ são os coeficientes $c_k$.
Isto funciona porque $b$ é maior que qualquer um dos coeficientes $c_k$, logo perguntar
pelo valor de $p(b)$ dá

$$
p(b) = \sum_{k = 1}^N c_k b^k ~ ,
$$

que é a forma de escrever $p(b)$ em base $b$, por definição.

Vou mostrar agora um par de exemplos, para que isto fique mais claro.
Se já tiveres percebido bem como isto funciona, então podes saltar o resto
do artigo e deixar o teu feedback em relação ao artigo, ou com um emoji ou com um
comentário!


### Exemplos

 1. Pedimos o valor de $p(1) = 1$.
 2. Definimos $b = p(1) + 1 = 2$.
 3. Pedimos o valor de $p(b) = p(2) = 8$.
 4. Escrevemos $p(b) = 8$ em base $b = 2$, que dá $1000_2$.
 5. Separamos os coeficientes em $1, 0, 0, 0$, resultando em $c_3 = 1$
e $c_2 = c_1 = c_0 = 0$.

**Example 2**: suppose $p(n) = x^3 + 2x$.

 1. Pedimos o valor de $p(1) = 3$.
 2. Definimos $b = p(1) + 1 = 4$.
 3. Pedimos o valor de $p(b) = p(4) = 72$.
 4. Escrevemos $p(b) = 72$ em base $b = 4$, que dá $1020_4$.
 5. Separamos os coeficientes em $1, 0, 2, 0$, resultando em $c_3 = 1$, $c_1 = 2$
e $c_2 = c_0 = 0$.

**Example 3**: suppose $p(n) = 4x^2 + 2x + 3$.

 1. Pedimos o valor de $p(1) = 9$.
 2. Definimos $b = p(1) + 1 = 10$.
 3. Pedimos o valor de $p(b) = p(10) = 423$.
 4. Escrevemos $p(b) = 423$ em base $b = 10$, que dá $423_{10}$!
 5. Separamos os coeficientes em $4, 2, 3$ resultando em $c_2 = 4$, $c_1 = 2$
e $c_0 = 3$.

Espero que tudo tenha feito mais sentido com estes exemplos!
Se precisares de converter algum número para outra base, podes sempre
[pedir ao WolframAlpha][wa-convert].

Se tens alguma questão sobre a minha solução, se encontraste algum erro (woops!) ou se gostavas de partilhar a *tua* solução, deixa um comentário em baixo.

[prob]: ../../problems/{{ page.slug }}
[wa-convert]: https://www.wolframalpha.com/input/?i=72+in+base+4
