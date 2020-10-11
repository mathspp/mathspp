---
metadata:
    description: 'Duas cervejas e duas pilhas de feijões: assim se faz um jogo matemático'
title: 'Problema #006 - pilhas de feijoões'
---

O problema deste artigo foi-me colocado nas Jornadas de Matemática da Faculdade de Ciências da UP, numa sessão de *jogos matemáticos*. O problema é particularmente engraçado porque assenta num jogo que se pode jogar entre duas pessoas.

===

![Uma figura que exemplifica uma jogada válida](beans.png)

### Enunciado do problema

Suponha-se que sobre uma mesa estão dois montes de feijões, um com 19 feijões e outro com 20 feijões. A Ana e o João vão jogar um jogo com esses montes de feijões: cada jogada consiste em retirar $2n$ feijões de um monte e pôr $n$ feijões no outro monte. Assim, na primeira jogada podemos, por exemplo, tirar 10 feijões do monte com $19$ (que agora só tem $9$) e pôr $5$ feijões no monte que tinha $20$ (que agora fica com $20$).

Será que algum dos jogadores consegue garantir a sua vitória?

!!! Pensa um pouco... A minha sugestão seria tentares perceber em que posições é que é claro que um certo jogador já perdeu.

Este jogo tem regras muito simples e é bastante engraçado, vale a pena jogá-lo com alguém só para entender realmente como funciona a dinâmica do jogo. Não precisas de jogar com feijões, podes usar moedas ou cartas ou qualquer outra coisa que tenhas à mão. Decidi incluir aqui em baixo uma pequena janela para que se possa jogar o jogo contra um jogador virtual que não segue nenhuma estratégia em particular, exceto quando, perto do fim, ele reconhece uma jogada vencedora.

Para jogar há que carregar no triângulo por cima do código e escrever as jogadas no terminal. "Escrever uma jogada" corresponde a escrever os novos tamanhos das pilhas depois da alteração que queremos fazer. Por exemplo, para fazermos a jogada descrita no enunciado escrevemos `9, 25`. O computador joga pelo João por defeito; para alterar isto basta escrever `1` em vez de `0` à frente de `GOES_FIRST = `.

<iframe allowfullscreen="true" allowtransparency="true" frameborder="no" height="400px" sandbox="allow-forms allow-pointer-lock allow-popups allow-same-origin allow-scripts allow-modals" scrolling="no" src="https://repl.it/@RojerGS/StackedBeans?lite=true" width="100%"></iframe>

Se precisares de clarificar alguma coisa, não hesites em perguntar na secção de comentários em baixo.

### Solução

Podes encontrar a minha proposta de solução [aqui][sol], para confirmares a tua resposta.

[sol]: ../s/{{ page.slug }}
