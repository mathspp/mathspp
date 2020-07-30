---
metadata:
    description: Neste artigo mostro-vos um jogo sobre cores que fiz com uma amiga.
title: 'HueHue: um jogo colorido'
---

**HueHue** é um jogo bastante colorido que eu escrevi com a minha colega [@inesfmarques][ines].

===

![a screenshot of an initial scrambled degradee from the game](a_start.png)

Nós programámos um puzzle que funciona à base de cores, a que chamámos HueHue (a ideia base do jogo não é original!). O objetivo do jogo é simples: começar com uma grelha colorida toda misturada, como na imagem em cima, e organizá-la para criar um degradee, como na imagem seguinte. A única garantia que o jogador tem à partida é que os quatro cantos estão nas suas posições corretas e não vão sair de lá.

![a screenshot of the corresponding final state with the degradee in place](end.png)

As instruções são simples: carregar numa telha com o botão direito troca essa telha pela última telha onde carregámos com o botão esquerdo. Assim, para trocar duas telhas carregamos numa com o botão esquerdo e de seguida na outra com o botão direito do rato.

Ou então arrastam-se as telhas, isso é ainda mais simples.

Quando o degradee fica completo, deixa de ser possível trocar telhas e o nome da janela altera-se. Para sair do jogo há que fechar a janela. Para se mudar o número de telhas na grelha ou o seu tamanho em pixéis basta editar o ficheiro `HueHueConfig.py`.

Também criei um pequeno script que gera degradees aleatórios quando a lógica do jogo já estava implementada, isto com o intuito de testar os degradees. Um exemplo de um degradee aleatório pode ver-se na imagem que se segue:

![a "continuous" degradee from the helper script](degradee.png)

O código do jogo e do gerador aleatório de degradees pode ser obtido [nesta pasta do GitHub][huehuegh].

O que é que farias para melhorar este jogo? Faz-me saber nos comentários aqui em baixo.

[ines]: https://github.com/inesfmarques/
[huehuegh]: https://github.com/RojerGS/minigames/tree/master/huehue