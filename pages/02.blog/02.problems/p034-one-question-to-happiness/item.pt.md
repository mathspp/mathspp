---
title: "Problema #034 – a pergunta da felicidade"
metadata:
    description: "Uma adivinha de lógica: duas portas, uma que te leva à felicidade eterna e outra à tristeza eterna."
---

Há duas portas, uma que te leva à felicidade eterna e outra à tristeza eterna.
Como escolhes a porta certa?

===

![](thumbnail.png "Foto de Hans Eiskonen no site Unsplash.")

# Enunciado do problema

O problema de hoje é um clássico.
Se resolves muitos problemas de lógica, então é provável que já
tenhas ouvido este problema, mas partilho-o aqui à mesma para os outros:

Tens duas portas idênticas à tua frente.
Atrás de uma delas há felicidade eterna, ao passo que atrás da outra
há tristeza eterna.

Para além disso, há duas pessoas que estão por perto e que sabem
o que é que cada porta esconde.
Tu podes fazer uma única pergunta a qualquer uma dessas pessoas.
Contudo, uma das pessoas diz sempre a verdade e a outra mente sempre.

Qual é a pergunta que deves fazer para conseguir determinar qual é
a porta que queres abrir?

!!! Pensa um pouco!

Se precisares de clarificar alguma coisa, não hesites em perguntar na secção de comentários em baixo.


# Submissões

Parabéns a todos os que conseguiram resolver o problema e,
em particular, aos que me enviaram as suas soluções:

 - Jeff W., Estados Unidos da América;
 - António A., Portugal.

(A lista não está ordenada.)


# Solução

Nunca conheci ninguém que me fosse capaz de me manter a par do seu
raciocínio enquanto resolvem este problema pela primeira vez,
por isso vou ter alguma dificuldade em transmitir _como_ é que alguém
poderia chegar à resposta deste problema.
Este é um daqueles problemas em que é mais fácil limitar-me a dar
a resposta e depois mostrar que a resposta está, de facto, certa.

Porque sabemos que há alguém que mente sempre e que a outra pessoa
diz sempre a verdade, temos de tentar arranjar uma maneira de fazer com que
a nossa pergunta “passe” pelas duas pessoas, apesar de só nos ser permitido
fazer uma pergunta a uma das pessoas.
No entanto, podemos fazer uma pergunta hipotética à pessoa A,
algo semelhante a “Se eu tivesse perguntado à pessoa B ...”.
É assim que resolvemos o problema.

A pergunta que devemos fazer é

 > “Se eu tivesse perguntado, à outra pessoa, qual é a porta
 que vai dar à felicidade eterna, para que porta é que a outra pessoa
 teria apontado?”

A pessoa a quem fazemos a pergunta há de indicar uma porta,
e depois nós saímos pela outra.
Vou explicar porquê.

Para facilitar a explicação,
vamos supor que a porta da esquerda é a que traz felicidade eterna.

 - Se fizeres a tua pergunta ao mentiroso, ele vai pensar
 “o honesto vai apontar para a porta da esquerda, por isso eu devia
 apontar para a porta da direita”, logo o mentiroso vai mentir-te
 sobre a resposta honesta da outra pessoa, e portanto aponta para a porta
 da direita.

 - Se dirigires a tua pergunta à pessoa honesta, ela vai pensar
 “o mentiroso vai mentir de certeza e apontar para a porta da direita,
 por isso é para lá que tenho de apontar”, ou seja, a pessoa honesta
 vai dar-te a verdadeira resposta do mentiroso, e portanto vai apontar
 para a porta da direita.

Em qualquer dos casos, a porta que tu queres é a esquerda, i.e.,
a resposta é sempre a porta que tu não queres.
E é assim que resolvias este problema.


Não te esqueças de [subscrever a newsletter][subscribe] para receberes os problemas diretamente na tua caixa de correio,
e deixa a tua reação a este problema em baixo.

[email]: mailto:rodrigo@mathspp.com?subject=Resposta%20para%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: https://mathspp.com/subscribe
[sol]: ../../solutions/{{ page.slug }}
