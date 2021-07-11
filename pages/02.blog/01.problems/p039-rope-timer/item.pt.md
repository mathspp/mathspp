---
title: "Problema #039 – cronómetro de cordas"
metadata:
    description: "Neste quebra-cabeças tens de usar duas cordas e um isqueiro para cronometrar 45 minutos."
---

Deram-te duas cordas mágicas a que podes pegar fogo e tens de as
usar para cronometrar 45 minutos.
Como é que o fazes?

===

![](thumbnail.png "Fotografia de uma ampulheta de Aron Visuals, Unsplash.")


# Enunciado do problema

Dão-te duas cordas mágicas para as mãos e um isqueiro.
As cordas são mágicas porque elas ardem de uma forma estranha:
cada corda demora exatamente 1h a arder de uma ponta à outra
mas as cordas não ardem a uma velocidade constante.
(Isto quer dizer que o tempo que já passou não tem de ser proporcional
ao comprimento da corda que já ardeu.
Por exemplo, pode acontecer que o primeiro quarto da corda demore 35
minutos a arder, depois o resto quase todo da corda demora 15 minutos
a arder, e a pontinha final precisa de 15 minutos para arder.)

Dadas duas cordas mágicas com estas propriedades,
como é que as podes usar para contar 45 minutos?

!!! Pensa um pouco!

Se precisares de clarificar alguma coisa, não hesites em perguntar na secção de comentários em baixo.


# Submissões

Parabéns a todos os que conseguiram resolver o problema e,
em particular, aos que me enviaram as suas soluções:

 - Christ van W., Países Baixos;
 - Attila K., Hungria;
 - Ashok M., Índia;
 - David H., Estados Unidos;
 - Greg K., Estados Unidos;
 - Shivam T., Estados Unidos;
 - Marco M., Itália;
 - David H., Taiwan;
 - Cody B., Estados Unidos;

(A lista não está ordenada.)

[Envia-me a tua solução][email] se quiseres o teu nome (ou um pseudónimo) nesta secção!


# Solução

Eu acho este problema super interessante!
O que eu acho engraçado é o facto de parecer que não temos grande liberdade nas nossas escolhas,
porque a primeira coisa em que nós pensamos é em cortar as cordas
em pedaços mais pequenos, mas depois o enunciado diz que o tempo
que cada pedaço demora a queimar-se não é proporcional ao seu comprimento,
portanto não há maneira de sabermos em que zonas temos de cortar as cordas.

A nossa única opção é atear as cordas, mas não podemos _só_ fazer isso,
já que cada corda arde por 60 minutos e nós queremos medir 45 minutos.

A chave do problema está em perceber que 45 minutos é $3/4$ de hora,
e $3/4 = 1/2 + 1/4$.
Além disso, $1/4 = (1/2)\times(1/2)$, i.e.,
três quartos de hora é meia hora mais metade de outra meia hora.
Repara que estamos sempre a falar de metades.
Assim, talvez seja uma boa ideia tentar olhar para o problema de outra maneira.
Em vez de tentar usar as cordas para medir um número específico de minutos,
será que conseguimos usar uma corda para medir exatamente metade
do tempo que ela dura?

Por outras palavras, se uma corda demora $x$ minutos a arder,
será que a conseguimos usar para medir $x/2$ minutos?

Pensa um pouco nisto.

Se acenderes as duas pontas da corda ao mesmo tempo,
então a corda vai arder durante metade do tempo!
Portanto, se pegares numa corda de 60 minutos e acenderes as duas pontas,
a corda vai ser totalmente consumida em apenas 30 minutos.
Agora só precisamos de medir os outros 15 minutos,
que seria super simples se tivéssemos uma corda de 30 minutos...
Bastava aplicar a mesma técnica...

Será que consegues “transformar” a segunda corda numa corda de 30 minutos?

Quando pegas fogo às duas pontas da primeira corda ao mesmo tempo,
também podes pegar fogo a uma das pontas da segunda corda.
Assim, quando a primeira corda for consumida,
a segunda corda já terá ardido um total de 30 minutos,
o que significa que ainda vai arder mais 30 minutos.
Nessa altura, basta-te pegar fogo à _outra_ ponta da segunda corda,
como se ela fosse apenas uma corda de 30 minutos.
Fazendo isto, a segunda corda vai extinguir-se passados 15 minutos,
o que te permite medir um total de 45 minutos!


Não te esqueças de [subscrever a newsletter][subscribe] para receberes os problemas diretamente na tua caixa de correio,
e deixa a tua reação a este problema em baixo.

[email]: mailto:rodrigo@mathspp.com?subject=Resposta%20para%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: https://mathspp.com/subscribe
