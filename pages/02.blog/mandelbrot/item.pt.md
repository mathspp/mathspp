---
title: Fractais e o conjunto de Mandelbrot
---

Sempre achei piada ao conceito de fractal. São objetos muito elegantes, têm uma noção de infinito embutida neles e, ainda para mais, não fazem sentido (onde é que já se ouviu falar de _auto-semelhança_?). Como é que eu poderia não os apreciar..?

===

![O conjunto de Mandelbrot](./mandelbrot.png)

Apesar de eu achar alguma piada a fractais, durante muito tempo não os comprendia realmente porque eu não sabia como definir rigorosamente um fractal.
Claro que eu sabia desenhar alguns, como por exemplo o [floco de neve](https://en.wikipedia.org/wiki/Koch_snowflake) ou o [triângulo de Sierpinski](https://en.wikipedia.org/wiki/Sierpinski_triangle),
mas desenhar um fractal é muito diferente de saber definir um...

Claro que me senti iluminado depois de ver [este](https://www.youtube.com/watch?v=NGMRB4O922I) vídeo do canal do Numberphile; um vídeo sobre o conjunto de Mandelbrot, o fractal que está desenhado
no início deste artigo. Afinal de contas, este fractal é obtido através de uma pequena fórmula que é aplicada muitas vezes de seguida e depois verificamos se a sequência que criamos
é limitada ou se cresce indefinidamente. Recomendo a visualização do vídeo!

Para decidirmos se um número complexo $c$ pertence ao fractal ou não (ou seja, se pertence ou não ao _conjunto_ de Mandelbrot), definimos $f(x) = x^2 + c$ e depois olhamos para
a sucessão $c, f(c), f(f(c)), f(f(f(c))), \cdots$. Se o valor absoluto destes números continuar a crescer indefinidamente, então $c$ não pertence ao conjunto.
Por exemplo, $0$ pertence ao conjunto porque $f(0) = 0, f(f(0)) = 0$ e por aí fora, logo não está a ter lugar nenhum crescimento desmesurado.

Depois de aprender a criar o conjunto de Mandelbrot, decidi aplicar esse conhecimento com Python. Primeiro criei [este](https://drive.google.com/open?id=0ByBeLS6ciLYVWm9yMldrVE1GVDg)
programa mas percebi rapidamente que tinha um problema um pouco desagradável: há medida que vamos fazendo zoom, o fractal perde "definição" e começa a ficar arredondado, o que
**não** é suposto acontecer. O problema tinha a ver com o modo como eu verificava se o módulo da sucessão crescia indefinidamente: eu estava a criar a sequência e, independentemente
do nível de zoom que já tivessemos, eu gerava sempre o mesmo número de termos da sucessão. No entanto, quando fazemos zoom e estamos próximos da fronteira do conjunto, precisamos
de um número maior de termos para perceber se a sucessão "explode" ou não.

Numa tentativa de corrigir isso, criei uma [segunda versão](https://drive.google.com/open?id=0ByBeLS6ciLYVOU9SdGQzdTI5Ylk) do programa que desenha o fractal,
e desta vez vou criando mais termos da sucessão há medida que vamos fazendo mais zoom. Isto faz com que, por um lado, o boneco não fique tão arredondado tão depressa; por outro lado,
quanto mais zoom fazemos, mais tempo o programa demora a fazer o desenho.

Os programas foram escritos em Python 3 e usei pygame (provavelmente a versão 1.9.2) para desenhar o conjunto. Podem encontrar um executável Windows [aqui](https://drive.google.com/open?id=0ByBeLS6ciLYVc09ZQllMcW94R2s),
que está dentro do ficheiro comprimido. O programa abre uma janela $600 \times 600$ com o fractal. Carregar no boneco faz zoom à volta do ponto onde se carregou.