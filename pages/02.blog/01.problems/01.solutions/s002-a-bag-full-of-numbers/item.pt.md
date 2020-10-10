---
metadata:
    description: Neste artigo tenho um problema interessantíssimo para te desafiar...
title: 'Problema #002 - um saco cheio de números'
---

Dois amigos estavam aborrecidos e decidiram jogar um jogo! Um jogo matemático com um saco de papel, claro.

===

![a photo of a paper bag](paperbag.jpg "Photo by B S K from FreeImages")

### Enunciado

O João e a Maria têm um saco cheio de números inteiros. Na verdade, o saco tem $10^{10^{10}}$ inteiros, cada um escrito num cartão plastificado. Para além do mais, a soma desses $10^{10^{10}}$ inteiros é $0$. À vez, o João e a Maria vão fazer o seguinte:

 - Retirar dois cartões do saco, suponhamos que com os números $a$ e $b$;
 - Pôr no saco um cartão novo com o número $a^3 + b^3$.

Será que há alguma configuração inicial/conjunto de jogadas para os quais, depois de $10^{10^{10}} - 1$ turnos, o saco contenha apenas um cartão plastificado com o número $73$?

!!! Pensa um bocado... e mais importante, tenta mesmo resolver o problema! Deixa um comentário para me contar como correu ;)

!!!! **Pista**: a resposta é "não"... Porquê?

!!!! **Pista**: procura uma invariante do jogo! Ou seja, procura alguma propriedade do jogo que _nunca_ mude com as jogadas do João e da Maria...

### Solução

(Se encontrares algum problema na solução, por favor faz-me saber na secção de comentários.)

Não, não há nenhuma configuração inicial nem nenhum conjunto de jogadas que nos permita terminar com o número $73$ no saco. Repara que $x \equiv x^3 \mod 2$. Isto é, $x$ tem a mesma paridade que $x^3$. Vamos assumir que, num dado momento, os números no saco têm soma $S$. Vou mostrar que a paridade da soma total não altera quando removemos dois números $a$ e $b$ e juntamos o número $a^3 + b^3$; isto é, a _invariante_ de que eu falei é a paridade da soma total dos números no saco:

\[
    x \equiv x^3 \mod 2 \implies S \equiv S - a - b + a^3 + b^3 \iff S + a + b \equiv S + a^3 + b^3 \mod 2
\]

Portanto não podemos acabar apenas com o $73$ no saco, já que a soma inicial é par e $73$ é ímpar.

Questão bónus: encontra uma solução que ainda funcione caso eu diga que quero acabar com $2$, $74$ ou $308$ no saco. Repara que a minha solução já não funciona porque estes números são pares.

Conseguiste resolver este problema? Achaste fácil?

[p002]: https://mathspp.com/pt/blog/problems/bag-full-of-numbers
