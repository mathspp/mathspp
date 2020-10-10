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

Podes encontrar a minha proposta de solução [aqui][sol], para confirmares a tua resposta.

[sol]: ../s/{{ page.slug }}
