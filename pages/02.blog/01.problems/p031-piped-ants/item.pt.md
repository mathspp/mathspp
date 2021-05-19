---
title: "Problema #031 - formigas num tubo"
---

Estão imensas formigas dentro de um tubo bastante estreito,
e as formigas estão sempre a chocar umas com as outras
e a virarem-se para o lado contrário.
Quanto tempo vão demorar a sair do tubo?

===

![](thumbnail.png "Fotografia de Lorenz Lippert do site Unsplash.")


# Enunciado do problema

Imagina que tens um tubo estreito, e que esse tubo tem a largura
exata de uma formiga, de tal forma que uma formiga consegue
andar dentro do tubo mas é impossível haver duas formigas lado a lado.
Supõe, ainda, que uma formiga demora exatamente 1h a atravessar o tubo.

Vou pôr várias formigas dentro do tubo que vão começar a andar em linha reta,
e cada formiga pode estar virada para uma ponta ou outra.
Quando duas formigas se encontram frente a frente viram-se no sentido
contrário, já que não conseguem passar uma ao lado da outra.
Quando uma formiga chega ao fim do tubo, a formiga sai do tubo.

Qual é que o menor intervalo de tempo que garante que todas as formigas
já saíram do tubo?

!!! Pensa um pouco!


# Solução

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


Não te esqueças de [subscrever a newsletter][subscribe] para receberes os problemas diretamente na tua caixa de correio,
e deixa a tua reação a este problema em baixo.

[email]: mailto:rodrigo@mathspp.com?subject=Resposta%20para%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: https://mathspp.com/subscribe
[sol]: ../../solutions/{{ page.slug }}
