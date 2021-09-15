---
title: "Problema #036 - um enorme triângulo pequeno"
metadata:
    description: "Consegues encontrar um triângulo enorme que também é pequeno?"
---

Consegues encontrar um triângulo enorme que também é pequeno?

===

![](thumbnail.png)

# Enunciado do problema

Será que existe um triângulo cuja área é superior à da superfície terrestre
(que tem aproximadamente 510 milhões km²)
mas cuja soma das três alturas não passa de 1cm?

!!! Pensa um pouco e [envia-me a tua solução][email]!

Eu vi este problema no Facebook, [num grupo do núcleo de estudantes de matemática da FCT][nucm-fb].

Se precisares de clarificar alguma coisa, não hesites em perguntar na secção de comentários em baixo.


# Solução

Sim! Há um triângulo que satisfaz estas restrições.

Seja $[ABC]$ o triângulo com lados $a$, $b$, $c$,
e que tem alturas respetivas $h_a$, $h_b$, $h_c$.

Assim sendo, a área do triângulo é

$$
\frac{ah_a}2 = \frac{bh_b}2 = \frac{ch_c}2 ~~~.
$$

Das igualdes em cima, concluímos que

$$
\begin{cases}
ah_a = ch_c \iff h_a = \frac{ch_c}a \\
bh_b = ch_c \iff h_b = \frac{ch_c}b
\end{cases} ~~~.
$$

Porque estamos a falar de um triângulo, sabemos que $c < a + b$.
Isto, por sua vez, significa que podemos retirar $c$ das igualdades
anteriores:

$$
h_a = \frac{ch_c}a \leq \frac{(a + b)h_c}{a} ~~~.
$$

Se, ainda para mais, o triângulo $[ABC]$ for isósceles com
$a = b$, obtemos

$$
h_a \leq 2h_c ~~~,
$$

e, de forma semelhante,

$$
h_b \leq 2h_c ~~~.
$$

Agora, tudo o que nos falta é somar as três alturas do triângulo:

$$
h_a + h_b + h_c \leq 2h_c + 2h_c + h_c = 5h_c ~~~.
$$

Concluímos, assim, que o nosso triângulo isósceles em $a$ e $b$
é tal que a soma das suas três alturas é menor que $5h_c$,
em que $h_c$ é a altura relativa ao lado diferente dos outros dois.
Assim, o que nós podemos fazer é criar um triângulo “comprido”
(ou seja, com um valor de $c$ grande)
e que seja “baixo”
(ou seja, com um valor de $h_c$ pequeno),
e que satisfaça as restrições do enunciado.
De facto, o raciocínio que fizémos demonstra que existem triângulos
com áreas arbitrariamente grandes e tais que a soma das suas alturas
seja arbitrariamente pequena.

Faz sentido?


Não te esqueças de [subscrever a newsletter][subscribe] para receberes os problemas diretamente na tua caixa de correio,
e deixa a tua reação a este problema em baixo.

[email]: mailto:rodrigo@mathspp.com?subject=Resposta%20para%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: https://mathspp.com/subscribe
