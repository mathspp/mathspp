---
metadata:
    description: "Um problema simples que tem por base uma ida à praia."
title: "Problema #030 - eficiência na praia"
---

Estás a apanhar um belo banho de sol quando decides ir falar com uns
amigos que estão noutro chapéu, mas primeiro queres ir molhar
os pés na água.
Qual é a maneira mais eficiente de fazer isto?

===

![](thumbnail.png "Versão editada da fotografia de Alex Perez no Unsplash")


# Enunciado do problema

O enunciado é tal e qual como leste em cima.
Estás na areia e precisas de te deslocar para outro ponto, mas primeiro
tens de passar pela borda da água, que podes assumir que define uma linha
reta:

![](_straight_shoreline.png "Borda da água representada por uma linha reta")

Como é que escolhes o teu caminho por forma a andares o menos possível
(porque a areia está mesmo quente!)?

!!! Pensa um pouco...

Se precisares de clarificar alguma coisa, não hesites em perguntar na secção de comentários em baixo.


# Submissões

Parabéns a todos os que conseguiram resolver o problema e,
em particular, aos que me enviaram as suas soluções:

 - “Todi Liju”, Portugal;
 - João F., Portugal;
 - António, Portugal;
 - David, Portugal;
 - Eleonora, Portugal.

(A lista não está ordenada.)


# Solução

A solução mais simples é a seguinte:

![](_solution.png "Traça a linha que liga um chapéu à reflexão do outro ao longo da linha do mar.")


Basta refletir um dos chapéus ao longo da linha do mar e traçar a linha reta que liga
a reflexão ao outro chapéu.
Esta linha toca na linha do mar, portanto satisfaz a restrição imposta no enunciado
do problema.
Quando chegamos à linha do mar, em vez de continuarmos a andar para dentro do mar,
viramo-nos e andamos em linha reta até ao chapéu destino.

Isto funciona porque a distância de um ponto da linha do mar ao chapéu na areia é
igual à distância desse mesmo ponto à reflexão do chapéu.


Não te esqueças de [subscrever a newsletter][subscribe] para receberes os problemas diretamente na tua caixa de correio,
e deixa a tua reação a este problema em baixo.

[email]: mailto:rodrigo@mathspp.com?subject=Resposta%20para%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: https://mathspp.com/subscribe
[sol]: ../../solutions/{{ page.slug }}
