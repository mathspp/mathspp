---
title: "Desenhar fractais"
---

Resolve uma série de problemas que não aparentam estar relacionados
e maravilha-te com a elegância da matemática que rege as suas soluções.

===

# Objetivo

O objetivo deste workshop é mostrar às pessoas o que é que o [conjunto de
Mandelbrot][Mandelbrot] é realmente e como calculá-lo.
Para além disso, também vamos usar Python para criar uma animação do fractal
como mostro aqui em baixo:

![Rendering of the Mandelbrot set.](_example.gif "Representação do conjunto de Mandelbrot.")

O programa que vamos escrever vai ser capaz de fazer zoom numa posição
arbitrária em que cliquemos, vai gravar as imagens geradas e também vai ser
capaz de gravar todos os *frames* gerados, para que consigas criar um GIF como o
que está aqui em cima.

## Teor do workshop

Durante o workshop vou ensinar o que é que o conjunto de Mandelbrot é, do ponto
de vista matemático, e depois vamos transformar essa definição em algo que um
computador consiga calcular.

Vamos trabalhar no nosso programa de forma incremental e vamos ver várias
versões do nosso conjunto de Mandelbrot:

![](_bw_15iters.png "Uma imagem preta e branca de um fractal com baixa resolução.")

![](_gray_50iters.png "Uma imagem preta e branca de um fractal com resolução média.")

![](thumbnail.png "Uma representação colorida de um fractal.")


# Conclusões

Se prestares atenção suficiente e se eu fizer o meu trabalho bem o suficiente,
quando o workshop acabar tu vais ter

 - compreendido como funciona um fractal, do ponto de vista matemático;
 - criado um programa que determina se um ponto pertence ao conjunto de
   Mandelbrot ou não;
 - percebido como criar um programa que demora bastante tempo a concluir mas que
   vai atualizando o utilizador sobre o seu estado interno;
 - criado uma animação progressiva do conjunto de Mandelbrot.


# Feedback

Aqui encontras o feedback de alguns participantes de sessões passadas:

 > “*Super informativo, útil e interessante. Aprendeu-se bastante para o tempo que foi e a disponibilidade de tirar dúvidas a todos (enquanto ainda motiva os restantes para ajudar também) criou um ambiente espetacular.*” – João F.

<!---->

 > “Foi descontraído, divertido e deixaste o pessoal super à vontade (ou seja, só boas vibes!). Explicaste bem o que íamos fazendo e o resultado final foi giro.*” – Inês G.


# Sessões passadas

 - Workshop organizado com o iNIGMA, o núcleo de estudantes de matemática da FCUP, a 11 de dezembro de 2020.


# Links úteis

O código de referência para este workshop está [no meu repositório de workshops
no GitHub][workshops-gh].

Também [escrevi um par de artigos no meu blogue][fractals-blog] sobre fractais e
sobre como desenhá-los.

[workshops-gh]: https://github.com/RodrigoGiraoSerrao/workshops
[Mandelbrot]: /blog/fractals-and-mandelbrot-set
[fractals-blog]: /blog/tag:fractals
