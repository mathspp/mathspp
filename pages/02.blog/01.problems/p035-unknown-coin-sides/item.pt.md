---
title: "Problema #035 – o lado desconhecido das moedas"
metadata:
    description: "Um quebra-cabeças algorítmico em que só tens de virar moedas."
---

Este é um puzzle algorítmico em que só tens de virar moedas.

===

![](thumbnail.png "Foto de Michael Longmire no site Unsplash.")

# Enunciado do problema

Estás dentro de uma sala às escuras, sentado a uma mesa.
A mesa à tua frente está cheia de moedas que tu sentes onde estão,
apesar de não saberes se estão com o lado _cara_ virado para cima,
se com o lado _coroa_.
No entanto, sabes que há exatamente 20 moedas com a _cara_ virada
para cima. Todas as outras moedas têm a _coroa_ virada para cima.

O teu objetivo é dividir as moedas em dois grupos,
de tal forma que cada grupo de moedas tenha o mesmo número de moedas
com a face _cara_ virada para cima.

As duas únicas coisas que podes fazer são

 - arrastar as moedas, em cima da mesa, de um lado para o outro (para dividires as moedas em dois grupos); e
 - virar a moeda ao contrário para passar a ter a outra face virada
 para cima.

Lembra-te que a sala está _tão_ escura que não consegues ver as faces
das moedas, nem consegues usar o teu tato para perceber qual é
a face que está virada para cima.

!!! Pensa um pouco!

Se precisares de clarificar alguma coisa, não hesites em perguntar na secção de comentários em baixo.

Este problema foi partilhado comigo pelo @TodiLiju,
que de tempos a tempos comenta estes problemas com as suas
propostas de solução.


# Submissões

Parabéns a todos os que conseguiram resolver o problema e,
em particular, a

 - Attila K., Hungria;

por me ter enviado uma solução correta.


# Solução

Há várias coisas que não sabemos, neste problema,
por isso não vale a pena focarmo-nos nisso.
Concentremo-nos no facto de que sabemos que há um total
de $20$ moedas com o lado “cara” virado para cima.

Se $20$ é o único número a que temos acesso,
porque é que não experimentamos fazer um grupo
com $20$ moedas, e deixamos todas as outras no outro grupo?
Fazendo isto,
 - chamando $n$ ao número total de moedas disponíveis, e
 - chamando $k$ ao número de moedas com o lado “cara” virado para cima no grupo de $20$ moedas,
então a tabela seguinte resume o estado em que estamos:

| Moedas no grupo | | Cara | | Coroa |
| :- | :- | :- | :- | :- |
| $20$ | | $k$ | | $20 - k$ |
| $n - 20$ | | $20 - k$ | | $n - 40 + k$ |

O que nós queríamos é que os dois números na coluna “cara” fossem iguais,
ou seja, queríamos que os dois grupos tivessem o mesmo número de moedas
com o lado “cara” virado para cima.
Neste momento, o que temos são $k$ moedas com “cara” para cima no grupo
de $20$ moedas, ao passo que o grupo com $n - 20$ moedas tem $20 - k$
moedas com o lado “cara” virado para cima.
No entanto, se virarmos _todas_ as moedas do grupo com $20$ moedas,
então vamos fazer com que todas essas moedas troquem o seu lado virado
para cima, o que faz com que passemos a ter $20 - k$ moedas com a “cara”
virada para cima, que é igual ao outro grupo, tal como se pretendia.

Em suma, isto é o que é necessário fazer:

 1. pegar em $20$ moedas quaisquer;
 2. virar essas $20$ moedas.


Não te esqueças de [subscrever a newsletter][subscribe] para receberes os problemas diretamente na tua caixa de correio,
e deixa a tua reação a este problema em baixo.

[email]: mailto:rodrigo@mathspp.com?subject=Resposta%20para%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: https://mathspp.com/subscribe

