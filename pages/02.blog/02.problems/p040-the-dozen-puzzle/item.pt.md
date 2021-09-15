---
title: "Problema #040 – o puzzle da dúzia"
metadata:
    description: "Três números diferentes são atribuídos a três amigos. Consegues descobrir quem recebeu que número?"
---

Três números diferentes são atribuídos a três amigos.
Consegues descobrir quem recebeu que número?

===

![](thumbnail.png "Fotografia de Kelly Neil no Unsplash")

# Enunciado do problema

A Diana pensou em três números inteiros positivos diferentes
e distribuiu-os pelos seus amigos: a Ana, o Bruno e o Carlos.
A Diana ainda os informou de que os três números, somados, dão 12,
e que o Carlos recebeu o maior número de todos.

Depois, a Diana perguntou aos amigos se sabiam quais eram os números
que os outros tinham recebido, e o Bruno disse “Eu sei!”, ao passo
que a Ana e o Carlos se mantiveram em silêncio.
Depois do Bruno ter dito que sabia os números dos outros,
a Ana e o Carlos pensaram mais um pouco e depois também confirmaram
que já sabiam os números de todos os outros.

Que número é que recebeu cada um dos amigos?

!!! Pensa um pouco!

Se precisares de clarificar alguma coisa, não hesites em perguntar na secção de comentários em baixo.


# Submissões

Parabéns a todos os que conseguiram resolver o problema e,
em particular, aos que me enviaram as suas soluções:

 - Ashok M., Índia;
 - David H., Taiwan;
 - Attila K., Hungria;
 - Jason P., Estados Unidos;
 - “Starsmer”, Estados Unidos;

Junta-te à comunidade e envia-me a tua solução por [email]!


# Solução

Para o Bruno saber os números da Ana e do Carlos,
o número do Bruno tem de ser grande o suficiente para que só
haja um valor possível para o número do Carlos.

Se o Bruno tivesse o número 6, então o Carlos teria de ter pelo menos
o 7 e isso faria com que os números dos dois valessem 13,
que já é mais que 12 – a soma total dos três números.
No entanto, se o número do Bruno for 5, então o Bruno sabe que o Carlos
só pode ter um 6
(se o Carlos tivesse 7 ou mais, então o Bruno e o Carlos, juntos,
teriam 12 ou mais)
e portanto a Ana tem de ter o 1.

Assim, concluímos que o Bruno pode adivinhar os números dos outros
dois amigos se tiver o 5.

Do ponto de vista da Ana, ter o 1 não lhe dá informação suficiente
sobre os números do Bruno e do Carlos: eles poderiam ter um 2 e um 9,
ou um 3 e um 8, por exemplo.

Do ponto de vista do Carlos, ter o 6 também não lhe diz o suficiente
sobre os números da Ana e do Bruno, porque eles poderiam ter um 2 e um 4,
ou um 1 e um 5, por exemplo.
Para não falar do facto de que o Carlos não teria maneira de saber
qual dos outros dois é que tem o maior dos dois números.

Depois do Bruno dizer que sabe os números de todos,
então os outros dois podiam deslindar o raciocínio do Bruno
e concluir que também sabem os números dos outros.

Uma solução alternativa passaria por listar todas as combinações
possíveis e procurar pela combinação que atribui um número único ao Bruno.
Por outras palavras, poderíamos atravessar os números todos de 1 a 12 e perguntar
“se o Bruno tivesse este número, de quantas maneiras diferentes
é que poderíamos atribuir números à Alice e ao Carlos?”.


Este problema foi retirado [desta publicação no Reddit][source],
e foi aqui partilhado com permissão.

<!-- v -->
Não te esqueças de [subscrever a newsletter][subscribe] para receberes os problemas diretamente na tua caixa de correio,
e deixa a tua reação a este problema em baixo.
<!-- ^ -->

[email]: mailto:rodrigo@mathspp.com?subject=Resposta%20para%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: https://mathspp.com/subscribe
[source]: https://www.reddit.com/r/puzzles/comments/o62ddq/dozen_total_puzzle/
