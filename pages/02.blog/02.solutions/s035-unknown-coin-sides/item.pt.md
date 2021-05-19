---
redirect: "/blog/problems/unknown-coin-sides#solucao[301]"
metadata:
    description: "Este artigo contém a minha proposta de solução para um dos problemas deste blogue."
title: "Solução #035 – o lado desconhecido das moedas"
---

Este artigo contém a minha solução proposta
para o [Problema #035 – o lado desconhecido das moedas][prob].
Por favor, não leias esta solução se ainda não tentaste
resolver [o problema][prob] a sério.

===

### Submissões

Parabéns a todos os que conseguiram resolver o problema e,
em particular, a

 - Attila K., Hungria;

por ter enviado uma solução correta.


### Solução

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

| Moedas no grupo | Cara | Coroa |
| :- | :-: | :-: |
| $20$ | $k$ | $20 - k$ |
| $n - 20$ | $20 - k$ | $n - 40 + k$ |

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

Se tens alguma questão sobre a minha solução, se encontraste algum erro (woops!) ou se gostavas de partilhar a *tua* solução, deixa um comentário em baixo.
Caso contrário podes só deixar um “upvote”!

[Não te esqueças de subscrever a newsletter][subscribe] para receberes
um problema diretamente no teu email de quinze em quinze dias!

[email]: mailto:rodrigo@mathspp.com?subject=Resposta%20de%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: https://mathspp.com/subscribe
[prob]: ../../problems/{{ page.slug }}
