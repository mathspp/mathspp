---
metadata:
    description: Este artigo contém a minha proposta de solução para um dos problemas deste blogue.
title: 'Solução #022 - coprimos na multidão'
---

Este artigo contém a minha solução proposta para o [Problema #022 - coprimos na multidão][prob]. Por favor não leias esta solução se ainda não tentaste resolver [o problema][prob] a sério.

===

### Solução

A solução deste problema passa por uma utilização simples do princípio do Pombal.

!!! O *princípio do Pombal* diz que se eu tiver $k$ pombos e os quiser pôr em $n < k$ gaiolas, então terá de haver pelo menos uma gaiola com mais do que um pombo.

Para algum $n \geq 2$, consideremos os $n-1$ pares de inteiros que se seguem:

$$
\{3, 4\}, \{5, 6\}, \cdots, \{2n-1, 2n\}
$$

que, juntos, formam o conjunto $\{3, 4, \cdots, 2n-1, 2n\}$.
Se escolhermos $n$ números deste conjunto (os pombos) e se olharmos para os pares de onde eles vieram (as gaiolas) então vemos que **de certeza** que tirámos os dois inteiros de um dos pares. Esses dois números que vieram do mesmo par são números inteiros consecutivos e, por isso, são coprimos!

Para veres porque é que dois inteiros consecutivos são coprimos, podes ler [esta prova num tweet][tp-coprimes].

Se tens alguma questão sobre a minha solução, se encontraste algum erro (woops!) ou se gostavas de partilhar a *tua* solução, deixa um comentário em baixo.

[tp-coprimes]: /blog/twitter-proofs/consecutive-integers-are-coprime
[prob]: ../../problems/{{ page.slug }}
