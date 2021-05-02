---
metadata:
    description: "Este artigo contém a minha proposta de solução para um dos problemas deste blogue."
title: "Solução #034 – a pergunta da felicidade"
---

Este artigo contém a minha solução proposta
para o [Problema #034 – a pergunta da felicidade][prob].
Por favor, não leias esta solução se ainda não tentaste
resolver [o problema][prob] a sério.

===

### Submissões

Parabéns a todos os que conseguiram resolver o problema e,
em particular, aos que me enviaram as suas soluções:

 - Jeff W., Estados Unidos da América;
 - António A., Portugal.

(A lista não está ordenada.)

### Solução

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

I have been told that there is a very interesting solution to this problem
that asks a fundamentally different question,
but for the life of me I can't figure any other solutions out,
so if you _do_ know a different solution, feel free to comment it down in the comments!


Se tens alguma questão sobre a minha solução, se encontraste algum erro (woops!) ou se gostavas de partilhar a *tua* solução, deixa um comentário em baixo.
Caso contrário podes só deixar um “upvote”!

[Não te esqueças de subscrever a newsletter][subscribe] para receberes
um problema diretamente no teu email de quinze em quinze dias!

[email]: mailto:rodrigo@mathspp.com?subject=Resposta%20de%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: https://mathspp.com/subscribe
[prob]: ../../problems/{{ page.slug }}
