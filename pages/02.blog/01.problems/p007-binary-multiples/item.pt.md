---
title: 'Problema #007 - múltiplos binários'
---

Será verdade que qualquer número inteiro tem um múltiplo que pode ser escrito só com $0$s e $1$s?

===

![Captura de ecrã de um monitor preto com 0s e 1s em branco](binary_img.jpg)


# Enunciado do problema

Seja $k \in \mathbb{Z}$ um número inteiro. Será que existe um número inteiro $n$ tal que $n$ é um múltiplo de $k$ e tal que $n$ é escrito só com $0$s e $1$s?

Por exemplo, se $k = 2$ poderíamos ter $n = 10$.

!!! Pensa um pouco...

Se precisares de clarificar alguma coisa, não hesites em perguntar na secção de comentários em baixo.


# Solução

A resposta é _sim_, qualquer inteiro $k$ tem um "múltiplo binário" $n$. Para mostrar que isto é verdade, vamos construir $n$ a partir de $k$.

Vamos assumir que $k$ é positivo e vamos considerar os seguintes $k$ números inteiros:

\[
    \big\{ 1, 11, 111, \cdots, \underbrace{1\cdots1}_{k\ 1\text{s}} \big\}
\]

(estes números podem ser formalmente definidos como o conjunto $\{c_i\}_{i = 1}^k$ em que $c_1 = 1$ e $c_{i+1} = 10*c_i + 1$).

Posto isto, apenas uma de duas coisas podem acontecer. Ou um dos números $c_i$ é um múltiplo de $k$ (caso em que não temos de fazer mais nada) ou então nenhum dos $c_i$ é múltiplo de $k$. No caso em que nenhum dos $c_i$ é múltiplo de $k$, passamos a considerar o resto da divisão de cada $c_i$ por $k$:

\[
    \{ c_1\ \text{mod}\ k, c_2\ \text{mod}\ k, \cdots, c_k\ \text{mod}\ k \} \subseteq \{ 1, \cdots, k - 1 \}
\]

Podemos dizer que o conjunto do resto da divisão dos $c_i$ (o conjunto da esquerda) está contido no conjunto da direita porque nenhum dos restos é $0$. Se fosse, então um dos $c_i$ seria múltiplo de $k$.

Agora reparem que o conjunto da esquerda é construído a partir de $k$ inteiros $c_i$ diferentes, ao passo que o conjunto da direita só tem $k - 1$ elementos. Neste caso, o [princípio do pombal][pigeonhole-principle-wiki] garante que há pelo menos dois $c_i$, $c_j$ diferentes que estão a ser transformados no mesmo elemento do conjunto da direita, i.e. $c_i \equiv c_j \ \text{mod}\ k$. Assumindo que $j > i$ isto significa que $c_j > c_i$ e, em particular:

\[
    \begin{cases}
        c_j - c_i \equiv 0\ \text{mod}\ k \\
        c_j - c_i = \underbrace{1\cdots 1}_{j-i\ 1\text{s}} \underbrace{0\cdots 0}_{i\ 0\text{s}}
    \end{cases}
\]

Portanto $n = c_j - c_i$ é um "múltiplo binário" de $k$.

Se $k$ for negativo, fazemos o processo descrito para $-k$. Se $k = 0$, então $n = 0$.


## Exemplo

Se $k = 4$, consideramos $c_1 = 1$, $c_2 = 11$, $c_3 = 111$, $c_4 = 1111$ e vemos que nenhum destes números é múltiplo de $4$.

Se calcularmos o resto da divisão de cada um destes números por $4$, obtemos

\[
    \begin{cases}
        1 \equiv 1\ \text{mod}\ 4 \\
        11 \equiv 3\ \text{mod}\ 4 \\
        111 \equiv 3\ \text{mod}\ 4 \\
        1111 \equiv 3\ \text{mod}\ 4
    \end{cases}
\]

e vemos que, por exemplo, $c_3 \equiv c_2\ \text{mod}\ 4$, o que implica que $c_3 - c_2 = 100 \equiv 0\ \text{mod}\ 4$.


Não te esqueças de [subscrever a newsletter][subscribe] para receberes os problemas diretamente na tua caixa de correio,
e deixa a tua reação a este problema em baixo.

[email]: mailto:rodrigo@mathspp.com?subject=Resposta%20para%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: https://mathspp.com/subscribe
