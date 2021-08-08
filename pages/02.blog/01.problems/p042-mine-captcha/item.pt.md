---
title: "Problema #042 – captcha de minas"
metadata:
    description: "Neste problema vamos tentar resolver um puzzle publicado pelo site xkcd."
---

Consegues resolver este mini minesweeper?

===

![grelha do minesweeper 4 por 4 que contém, da esquerda para a direita e de cima para baixo, 2, cinza, 1, cinza, cinza, cinza, 3, cinza, 3, cinza, cinza, cinza, cinza, 1, cinza, 1](thumbnail.png)

# Enunciado do problema

O site [xkcd] publicou o cartoon [#2496][source],
que contém um mini jogo de minesweeper,
e é esse o problema com que vamos trabalhar:
vamos encontrar todas as minas na grelha mostrada em cima.

Para quem não conhece as regras do jogo, aqui estão elas:

 - alguns quadrados da grelha contém minas, outros não;
 - os quadrados que não têm minas contém números, e esses números
indicam quantas minas estão na vizinhança do quadrado com o número
(a vizinhança contém os quadrados diretamente adjacentes, bem como os quadrados cujos vértices se tocam na diagonal);
 - os quadrados cinzentos podem conter números ou minas.

Tens de encontrar todas as minas ao raciocinar sobre os números que estão à mostra.

Podes também jogar minesweeper online ou podes jogar o meu [remake do minesweeper][minesweeper-blog].

!!! Pensa um pouco!

Se precisares de clarificar alguma coisa, não hesites em perguntar na secção de comentários em baixo.


# Submissões

Sabes resolver este problema?
Sê o primeiro na lista das pessoas que resolveram este problema
ao enviares-me a tua solução por [email]!

<!--
Parabéns a todos os que conseguiram resolver o problema e,
em particular, aos que me enviaram as suas soluções:

 - 

Junta-te à comunidade e envia-me a tua solução por [email]!
-->


# Solução

Uma proposta de solução vai ser publicada aqui quando tiverem passado duas semanas desde a publicação deste problema.


Não te esqueças de [subscrever a newsletter][subscribe] para receberes os problemas diretamente na tua caixa de correio.

[email]: mailto:rodrigo@mathspp.com?subject=Resposta%20para%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: https://mathspp.com/subscribe
[source]: https://xkcd.com/2496/
[xkcd]: https://xkcd.com
[minesweeper-blog]: /blog/minesweeper-remake
