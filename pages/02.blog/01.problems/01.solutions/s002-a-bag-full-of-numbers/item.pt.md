---
metadata:
    description: Neste artigo partilho a solução do P#002 do meu blogue.
title: 'Solução #002 - um saco cheio de números'
---

Este artigo contém a minha solução proposta para o [Problema #002 - um saco cheio de números][prob]. Por favor não leias esta solução se ainda não tentaste resolver [o problema][prob] a sério.

===

![a photo of a paper bag](paperbag.jpg "Photo by B S K from FreeImages")

### Solução

(Se encontrares algum problema na solução, por favor faz-me saber na secção de comentários.)

Não, não há nenhuma configuração inicial nem nenhum conjunto de jogadas que nos permita terminar com o número $73$ no saco. Repara que $x \equiv x^3 \mod 2$. Isto é, $x$ tem a mesma paridade que $x^3$. Vamos assumir que, num dado momento, os números no saco têm soma $S$. Vou mostrar que a paridade da soma total não altera quando removemos dois números $a$ e $b$ e juntamos o número $a^3 + b^3$; isto é, a _invariante_ de que eu falei é a paridade da soma total dos números no saco:

\[
    x \equiv x^3 \mod 2 \implies S \equiv S - a - b + a^3 + b^3 \iff S + a + b \equiv S + a^3 + b^3 \mod 2
\]

Portanto não podemos acabar apenas com o $73$ no saco, já que a soma inicial é par e $73$ é ímpar.

Questão bónus: encontra uma solução que ainda funcione caso eu diga que quero acabar com $2$, $74$ ou $308$ no saco. Repara que a minha solução já não funciona porque estes números são pares.

Conseguiste resolver este problema? Achaste fácil?

[prob]: {{ page.base_url|to_problem }}
