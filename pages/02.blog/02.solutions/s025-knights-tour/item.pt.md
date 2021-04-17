---
metadata:
    description: Este artigo contém a minha proposta de solução para um dos problemas deste blogue.
title: 'Solução #025 - passeio do cavalo'
---

Este artigo contém a minha solução proposta para o [Problema #025 - passeio do cavalo][prob]. Por favor não leias esta solução se ainda não tentaste resolver [o problema][prob] a sério.

===

### Solução

Vamos mostrar que o Bruno (o segundo jogador) consegue ganhar o jogo, sempre.
Para isso, vamos seguir uma estratégia simples: vamos mostrar que,
independentemente do que a Alice fizer, o Bruno consegue *sempre* fazer uma jogada.
Se o Bruno consegue *sempre* fazer uma jogada, e se o jogo é um jogo finito
(o jogo nunca pode durar mais do que $64$ jogadas porque o tabuleiro só tem $64$ casas),
então a Alice perde sempre.

A primeira coisa que fazemos é dividir o tabuleiro em oito retângulos com dimensões
$4 \times 2$, tal como ilustrado na figura em baixo.

![Um tabuleiro de xadrez dividido em oito retângulos de dimensões $4\times 2$.](_chessboard_01.png)

Repara que as casas do tabuleiro estão todas dentro de *algum* retângulo e, o *mais
importante de tudo*, qualquer casa do tabuleiro tem apenas uma outra casa, à distância
de um cavalo, e no mesmo retângulo $4\times 2$.

A título de exemplo, supõe que um cavalo está numa posição tão central quanto possível,
na quarta linha a contar de cima e na quarta coluna a contar da esquerda.
Esse cavalo tem oito movimentos possíveis, mas apenas um desses oito vai aterrar dentro
do retângulo de onde o cavalo partiu.
Isto é ilustrado na figura que se segue, onde cada cavalo translúcido representa um
movimento legal e onde um retângulo foi colorido de forma diferente,
para realçar a posição inicial do cavalo e o único movimento legal que permite ao
cavalo mudar de posição sem mudar de retângulo.

![Um tabuleiro de xadrez com um cavalo na posição d5 e com os movimentos legais assinalados.](_chessboard_02.png)

Este é um padrão muito claro que funciona de forma semelhante para cada um dos oito
retângulos.
A figura seguinte esquematiza os pares de quadrados entre os quais um cavalo pode
saltitar, sem sair do mesmo retângulo.
No exemplo de cima, o cavalo estava numa das posições marcadas com um quadrado e só
poderia saltar para a outra posição marcada com um quadrado.
Se estivesse numa das posições marcadas com um círculo, só poderia saltar para a outra
posição marcada com um círculo (assumindo que o cavalo não quer mudar de retângulo).

![Um tabuleiro de xadrez com alguns pares de posições assinaladas.](_chessboard_03.png)

Reflete um pouco sobre isto.

Agora que entendes o que isto quer dizer, é fácil de ver que o Bruno pode ganhar
sempre!

A Alice começa por escolher uma posição qualquer no tabuleiro.
Sempre que é a vez do
Bruno, tudo o que ele tem de fazer é mexer o cavalo para a única posição legal que
mantém o cavalo dentro do mesmo retângulo.
Esta posição está sempre livre, porque sempre que a Alice joga ela tem de mexer o
cavalo para um par de posições que ainda não foi visitado, e sempre que o Bruno joga
ele garante que o par de posições é completamente visitado.

E é assim que a Alice perde sempre para a estratégia brilhante do Bruno.
Se quiseres, podes testar esta estratégia jogando contra o computador [aqui][game].

Se tens alguma questão sobre a minha solução, se encontraste algum erro (woops!) ou se gostavas de partilhar a *tua* solução, deixa um comentário em baixo.

Já agora, [não te esqueças de subscrever a newsletter][subscribe] para receberes
um problema diretamente no teu email de quinze em quinze dias!

[subscribe]: https://mathspp.com/subscribe
[game]: /games/knights-tour
[prob]: ../../problems/{{ page.slug }}
