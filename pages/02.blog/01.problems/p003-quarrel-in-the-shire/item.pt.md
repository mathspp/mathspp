---
title: 'Problema #003 - uma briga no Shire'
---

O Gandalf tem de ir ajudar alguns Hobbits mas a tarefa não parece ter fim... Dás-lhe uma ajudinha?

===

![a picture of the Shire](shire.jpg)

Neste artigo encontras mais um problema e uma proposta de solução. Se encontrares algum erro ou se tiveres uma solução diferente, faz-me saber na secção de comentários em baixo!


# Enunciado

O Shire é um sítio muito aprazível onde $N$ Hobbits vivem em perfeita harmonia. Ou viviam, até que um Hobbit decidiu tornar-se decorador de exteriores e convenceu alguns dos seus amigos a pintar as suas portas num tom de roxo muito na moda (as portas eram todas amarelas até à data).

Da noite para o dia a harmonia em que os Hobbits viviam desapareceu e a discórdia entre Hobbits com portas de cores diferentes instalou-se.

Preocupado, o sábio Gandalf apressou-se a chegar ao Shire para tentar ajudar os Hobbits. Isto foi o que ele decidiu fazer: ele vai visitar cada Hobbit, por ordem alfabética. Ao visitar um Hobbit, o Gandalf vai mudar a cor da porta dele se houver mais Hobbits zangados com ele do que Hobbits que estão bem com ele. Depois de visitar todos os Hobbits uma vez, o Gandalf vai voltar a visitá-los a todos pela mesma ordem, e depois outra vez, e outra vez, ..., repetindo este processo até que uma ronda inteira de visitas já não faça diferença.

Será que a tarefa do Gandalf tem sempre um fio, independentemente de $N$ e da trapalhada de cores que os Hobbits criem? Ou será que há valores de $N$ e maneiras de pintar as portas do Shire de modo a que o Gandalf entre num ciclo infinito de remodelações exteriores?

!!! Pensa um pouco... Pega numa folha de papel e simula algumas configurações!

Se precisares de clarificar alguma coisa, não hesites em perguntar na secção de comentários em baixo.

!!!! **Pista**: a tarefa do Gandalf nunca é infinita.

!!!! **Pista**: tenta encontrar um semi-invariante; uma quantidade que só se altera num certo sentido, e que permita concluir que o Gandalf há de descansar eventualmente.


# Solução

A tarefa do Gandalf termina sempre. Para perceber porquê, pensa nos $H$ Hobbits como pontos e cada par de Hobbits está ligado por uma linha. Essa linha é verde se os dois Hobbits estiverem bem um com o outro (se as suas portas tiverem a mesma cor) e a linha é vermelha se os dois Hobbits estiverem chateados (se as suas portas tiverem cores diferentes). Agora conta o número de linhas vermelhas no boneco que fizeste e chama $R_0$ a esse número, onde o $0$ aparece porque o Gandalf fez $0$ visitas. Depois do Gandalf visitar $t$ Hobbits, $R_t$ representa o número de linhas vermelhas no boneco nessa altura.

Devia ser relativamente simples de perceber que $R_{t+1} \leq R_t$. Isto é verdade porque, quando o Gandalf visita um Hobbit, ele só muda a cor da porta se isso significar que o número de Hobbits amigos aumente, isto é, se o número de linhas verdes aumentar / número de linhas vermelhas diminuir. Isto significa que uma de duas coisas terá de acontecer:

 - Para um dado $k$, $R_k = 0$ e todos os Hobbits são amigos uns dos outros, de novo;
 - Para algum $k$, $R_{k+N} = R_k$, o que significa que o Gandalf visitou todos os Hobbits no Shire e não mudou a cor de uma única porta, o que significa que as suas visitas já não fazem diferença.

De qualquer dos modos, podemos ver que outra ronda de visitas por parte do Gandalf não faria qualquer diferença e portanto o Gandalf pode ir descansar.

Pergunta bónus: consegues arranjar algum valor de $N > 1$ e alguma configuração das cores das portas de modo a que aconteça o segundo caso da lista em cima? Isto é, que as visitas do Gandalf deixem de fazer diferença mas nem todos os Hobbits são amigos uns dos outros? Diz-me o que encontraste na secção de comentários em baixo ;)


Não te esqueças de [subscrever a newsletter][subscribe] para receberes os problemas diretamente na tua caixa de correio,
e deixa a tua reação a este problema em baixo.

[email]: mailto:rodrigo@mathspp.com?subject=Resposta%20para%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: https://mathspp.com/subscribe
