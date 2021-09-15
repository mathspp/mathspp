---
metadata:
    description: Um problema simples que mostra que há sempre alguma ordem no caos.
title: 'Problema #022 - coprimos na multidão'
---

Este simples problema exemplifica um fenómeno interessante: quando estamos a considerar um "universo" grande o suficiente, mesmo que escolhamos partes dele de forma aleatória vamos conseguir encontrar estrutura.

===

![Uma multidão, fotografia de Rob Curran do Unsplash](./crowd.jpg)


# Enunciado do problema

Assume que $n$ é um inteiro tal que $n \geq 2$. Agora considera os números

$$
\{3, 4, \cdots, 2n-1, 2n\}\ .
$$

Mostra que, escolhendo quaisquer $n$ números de entre esses, haverá sempre dois que não têm divisores em comum.

Por outras palavras, mostra que há sempre dois desses $n$ números que são coprimos.

!!! Pensa um pouco...

Se precisares de clarificar alguma coisa, não hesites em perguntar na secção de comentários em baixo.


# Solução

A solução deste problema passa por uma utilização simples do princípio do Pombal.

!!! O *princípio do Pombal* diz que se eu tiver $k$ pombos e os quiser pôr em $n < k$ gaiolas, então terá de haver pelo menos uma gaiola com mais do que um pombo.

Para algum $n \geq 2$, consideremos os $n-1$ pares de inteiros que se seguem:

$$
\{3, 4\}, \{5, 6\}, \cdots, \{2n-1, 2n\}
$$

que, juntos, formam o conjunto $\{3, 4, \cdots, 2n-1, 2n\}$.
Se escolhermos $n$ números deste conjunto (os pombos) e se olharmos para os pares de onde eles vieram (as gaiolas) então vemos que **de certeza** que tirámos os dois inteiros de um dos pares. Esses dois números que vieram do mesmo par são números inteiros consecutivos e, por isso, são coprimos!

Para veres porque é que dois inteiros consecutivos são coprimos, podes ler [esta prova num tweet][tp-coprimes].


Não te esqueças de [subscrever a newsletter][subscribe] para receberes os problemas diretamente na tua caixa de correio,
e deixa a tua reação a este problema em baixo.

[sol]: ../../solutions/{{ page.slug }}
[subscribe]: https://mathspp.com/subscribe
[tp-coprimes]: /blog/twitter-proofs/consecutive-integers-are-coprime
