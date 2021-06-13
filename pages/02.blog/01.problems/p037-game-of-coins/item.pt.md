---
title: "Problema #037 - jogo das moedas"
metadata:
    description: "A Alice e o Bruno vão defrontar-se neste jogo simples, mas interessante. Quem vencerá?"
---

A Alice e o Bruno vão defrontar-se neste jogo simples, mas interessante. Quem vencerá?

===

![](thumbnail.png "Fotografia de 𝓴𝓘𝓡𝓚 𝕝𝔸𝕀 no site Unsplash.")

# Enunciado do problema

A Alice e o Bruno sentam-se frente a frente numa mesa circular.
Vão jogar ao “jogo das moedas”!

O “jogo das moedas” é simples:
eles têm acesso a um monte _enorme_ de moedas circulares e todas
do mesmo tamanho e, à vez, pousam uma moeda na mesa.
Essa moeda não se pode sobrepor a nenhuma outra moeda que já esteja
na mesa; tem de ficar completamente assente na mesa.
Perde o primeiro a não ter espaço para colocar uma moeda.

A Alice vai ser a primeira a jogar.

Será que algum dos dois consegue desenvolver uma estratégia vencedora?
Que estratégia seria essa?

!!! Pensa um pouco!

Se precisares de clarificar alguma coisa, não hesites em perguntar na secção de comentários em baixo.


# Submissões

Parabéns a todos os que conseguiram resolver o problema.
Como ninguém me enviou a sua solução, não vou poder listar
vencedores desta vez.


# Solução

A Alice pode ganhar o jogo porque ela é a primeira a jogar.
O que ela tem de fazer é o seguinte:
começa por jogar uma moeda mesmo no centro da mesa e,
a partir daí, basta-lhe fazer jogadas diametralmente opostas
às do Bruno.

Como é que isto funciona?
Na verdade é bastante interessante.
A Alice começa por jogar no centro da mesa para que a mesa passe
a ter a seguinte propriedade:
dada uma posição válida para uma moeda, a posição diametralmente
oposta também é uma posição válida.

Podes pensar nisto assim: quando a Alice faz a primeira jogada
no centro da mesa, é como se estivesse a furar a mesa,
e agora já ninguém pode lá jogar.

Agora é a vez do Bruno jogar, e ele há de jogar uma moeda
num sítio qualquer, que não seja em cima de um buraco.
Por exemplo, ele pode jogar aqui:

![](_bob_first_move.png)

Para responder, o que a Alice faz é determinar qual é a jogada
diametralmente oposta:

![](_alice_first_reply.png)

Agora que os dois jogaram, podes pensar que as suas moedas voltam
a fazer um buraco na mesa:

![](_restored.png)

Agora há três buracos na mesa, em vez de um, mas a propriedade
que a mesa tinha continua a ser verdade:
dada uma posição válida para uma moeda, a posição diametralmente
oposta também é uma posição válida.

Na sua vez, o Bruno há de pôr uma moeda algures:

![](_bob_snd_move.png)

A Alice volta a fazer a mesma coisa, volta a jogar na posição
diametralmente oposta:

![](_alice_snd_reply.png)

E eles continuam a fazer isto, até ao Bruno ficar sem espaço para
jogar.
Nunca vai ser a Alice a ficar sem espaço porque ela reage sempre às
jogadas do Bruno.


Não te esqueças de [subscrever a newsletter][subscribe] para receberes os problemas diretamente na tua caixa de correio,
e deixa a tua reação a este problema em baixo.

[email]: mailto:rodrigo@mathspp.com?subject=Resposta%20para%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: https://mathspp.com/subscribe
