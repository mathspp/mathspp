---
metadata:
    description: "Este artigo contém a minha proposta de solução para um dos problemas deste blogue."
title: "Solução #031 - formigas num tubo"
---

Este artigo contém a minha solução proposta
para o [Problema #031 - formigas num tubo][prob].
Por favor, não leias esta solução se ainda não tentaste
resolver [o problema][prob] a sério.

===

### Solução

Este problema pode parecer bastante confuso se tentarmos imaginar todas as possíveis
interações/choques entre um número arbitrário de formigas dentro do tube descrito.
No entanto, a única coisa que nos interessa é o tempo que cada formiga demora
a sair de dentro do tubo, e não o número de vezes que as formigas chocam umas com
as outras!

Imagina que duas formigas, $A$ e $B$, estão a andar na direção uma da outra,
e chocam num ponto qualquer.
Vamos supor que $t_A$ é o tempo que a formiga $A$ demoraria a sair do tubo se fosse
a _única_ formiga no tubo, e de forma semelhante, $t_B$ é o tempo que a formiga $B$
demoraria a sair do tubo se estivesse sozinha dentro do tubo.
Quando as formigas $A$ e $B$ chocam e se viram, então a formiga $A$ passa a estar
virada para onde a $B$ estava e passa a ter à sua frente o caminho que a formiga $B$ ia fazer,
e portanto agora o tempo que a formiga $A$ vai demorar a sair do tubo é $t_B$.
Da mesma forma, quando a formiga $B$ fica virada para onde a $A$ estava, o tempo
que a formiga $B$ vai demorar passa a ser $t_A$.

Quando duas formigas chocam, elas trocam os tempos respetivos que elas iam demorar para sair
do tubo se pudessem andar sempre em frente.
Isto mostra que as formigas chocarem umas com as outras não faz com que elas gastem mais
tempo _no total_, só faz com que as formigas troquem o papel de quem anda mais e de quem anda menos.


Se tens alguma questão sobre a minha solução, se encontraste algum erro (woops!) ou se gostavas de partilhar a *tua* solução, deixa um comentário em baixo.
Caso contrário podes só deixar um “upvote”!

[Não te esqueças de subscrever a newsletter][subscribe] para receberes
um problema diretamente no teu email de quinze em quinze dias!

[email]: mailto:rodrigo@mathspp.com?subject=Resposta%20de%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: https://mathspp.com/subscribe
[prob]: ../../problems/{{ page.slug }}
