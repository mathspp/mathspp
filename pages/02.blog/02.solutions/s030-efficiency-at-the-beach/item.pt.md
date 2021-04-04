---
metadata:
    description: "Este artigo contém a minha proposta de solução para um dos problemas deste blogue."
title: "Solução #030 - eficiência na praia"
---


--Este artigo contém a minha solução proposta
para o [Problema #030 - eficiência na praia][prob].
Por favor, não leias esta solução se ainda não tentaste
resolver [o problema][prob] a sério.

===

### Submissões

Tenho de congratular as várias pessoas que me enviaram a solução correta,
nomeadamente o “Todi Liju” que deixou um comentário
[no artigo do problema](http://disq.us/p/2faafjt),
o João F. enviou a solução por email, e mais algumas pessoas (António, David,
Eleonora, ...) também me fizeram chegar as suas soluções.
No entanto, alguns arranjaram soluções demasiado complicadas!


### Solução

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

Se tens alguma questão sobre a minha solução, se encontraste algum erro (woops!) ou se gostavas de partilhar a *tua* solução, deixa um comentário em baixo.
Caso contrário podes só deixar um “upvote”!

[Não te esqueças de subscrever a newsletter][subscribe] para receberes
um problema diretamente no teu email de quinze em quinze dias!

[email]: mailto:rodrigo@mathspp.com?subject=Resposta%20de%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: https://mathspp.com/subscribe
[prob]: ../../problems/{{ page.slug }}
