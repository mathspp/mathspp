---
metadata:
    description: Este artigo expõe um problema relativo à charada com baldes de água e mostra que nem sempre a charada tem solução.
title: "Problema #004 - solubilidade da charada dos baldes"
---

[Neste artigo][wbr post] eu falei de uma charada com baldes com água. Agora desafio-te a mostrares que há situações em que a charada é _impossível_ de resolver!

===

![A grayscale image with 3 buckets](buckets.jpg "Photo by Nils Schirmer on Unsplash")

### Enunciado

Tens $n$ baldes e cada um tem capacidade para $c_i$ litros de água, $i = 1, \cdots, n$. Queres mexer nos baldes de maneira tal que consegues fazer com que um balde tenha exatamente $t$ litros de água, sabendo que as três coisas que podes fazer são:

 - encher completamente o balde $i$ para que ele passe a ter $c_i$ litros de água;
 - esvaziar completamente o balde $i$ para que ele passe a ter $0$ litros de água;
 - passar água do balde $i$ para o balde $j$, até que o balde $i$ fique vazio ou o balde $j$ fique cheio, o que quer que aconteça primeiro.

Prova que, se $t$ não for um múltiplo do maior divisor comum dos vários $c_i$, $i = 1, \cdots, n$ então é impossível que um balde contenha exatamente $t$ litros de água.

Por exemplo, se os baldes tiverem capacidades $4$ e $6$ e $t = 3$, então não há sequência de movimentos que permita ter exatamente $3$ litros de água num dos baldes, já que o maior divisor comum de $4$ e $6$ é $\texttt{mdc}(4, 6) = 2$ e $3$ _não_ é um múltiplo de $2$.

!!! Pensa um pouco... e tenta resolver o problema! Pega numa folha e num lápis e puxa pela cabeça!

### Solução

Uma solução possível passa por usar um bom invariante que se aplica à quantidade de água que cada balde contém em qualquer altura. Para facilitar a minha explicação, vamos dizer que o máximo divisor comum dos $c_i$, $i = 1, \cdots, n$ é $\texttt{mdc}(c_1, \cdots, c_n) = d$ e vamos dizer ainda que a quantidade de água que o balde $i$ tem se chama $w_i$. O que eu vou mostrar é que, independentemente das jogadas que façamos, $w_i$ é _sempre_ um múltiplo de $d$ para qualquer $i$ (que se escreve $d | w_i$ e que se lê _"$d$ divide $w_i$"_).

No início todos os baldes estão vazios, o que significa que temos $w_1 = \cdots = w_n = 0$ e $0$ é um múltiplo de $d$ portanto começamos bem. Agora vou mostrar que as três jogadas existentes preservam a propriedade $d | w_i\ \forall i$.

 - Esvaziar o balde $i$: isto significa que $w_i = 0$ e $d | 0$ portanto está tudo bem;
 - Encher o balde $i$: isto significa que $w_i = c_i$ mas, por definição, $d$ é _um_ divisor de $c_i$ portanto é claro que temos $d | c_i$;
 - Mudar água do balde $i$ para o balde $j$, até que o balde $i$ fique vazio ou o balde $j$ fique cheio, o que quer que aconteça primeiro: antes de mudarmos a água do $i$ para o $j$ temos que $d | w_i$ e $d | w_j$, logo podemos escrever $w_i = k_i d$, $w_j = k_j d$ onde $k_i, k_j$ são números inteiros. Vamos ter de analizar a quantidade de água que vai ficar em cada balde, consoante o balde $i$ acabe por ficar vazio antes de encher o balde $j$ ou não:
   - se o balde $i$ ficar vazio, então $w_i = 0$ e $w_j = (k_i d) + (k_j d) = (k_i + k_j) d$; $d | 0$ e $d | (k_i + k_j) d$ portanto tudo continua a ser múltiplo de $d$;
   - se o balde $j$ ficar cheio, então $w_j = c_j$ e $d | c_j$ portanto estamos bem; falta apenas ver quanta água ficou no balde $i$ e ver se essa quantidade é múltipla de $d$ ou não. O balde $j$ tinha $k_j d$ litros de água e agora tem $c_j$, portanto o balde $i$ passou-lhe $c_j - k_j d$ litros de água. Se o balde $i$ _tinha_ $k_i d$ litros de água, então agora tem $w_i = k_i d - (c_j - k_j d)$. Mas isto ainda é um múltiplo de $d$ porque $c_j$ também o é: podemos escrever $c_j = kd$ onde $k$ é um número inteiro, o que nos permite escrever $w_i = k_i d - (c_j - k_j d) = d(k_i - k + k_j)$ e mostrar que o novo valor de $i$ também é múltiplo de $d$!

Acabei de mostrar que, independentemente do que façamos, a quantidade de água em cada balde é sempre múltipla de $d$, logo se $t$ _não_ for um múltiplo de $d$ então é impossível fazer com que um balde tenha exatamente $t$ litros de água...

O que achas desta solução? A tua é semelhante? Partilha connosco na secção de comentários em baixo.

[wbr post]: ../../water-buckets