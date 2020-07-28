O conjunto preenchido de Julia é um fractal bastante giro que faz lembrar o [conjunto de Mandelbrot](https://mathspp.com/pt/blog/mandelbrot)!

===

![Um screenshot do conjunto preenchido de Julia de um programa feito em Python 3](juliaset2.png)

(Pode ser interessante leres [este artigo](https://mathspp.com/blog/mandelbrot) sobre o conjunto de Mandelbrot, já que está relacionado com este artigo!)

Só há um conjunto de Mandelbrot, ao passo que o "conjunto preenchido de Julia" é, na verdade, um conceito que se pode aplicar a várias funções diferentes. Neste contexto, e à semelhança do conjunto de Mandelbrot, para construir um conjunto de Julia começamos por escolher um número complexo $z_0 \in \mathbb{C}$. Depois de fazermos esta escolha, consideramos a função $f(z) = z^2 + z_0$ e dizemos que um ponto pertence ao conjunto de Julia se a aplicação repetida da função $f$ a esse ponto diverge para infinito. De um modo formal, o conjunto de Julia que estou a descrever é o conjunto:

\[
    \{ z \in \mathbb{C}: \lim_{n \to \infty} |f^n(z)| = \infty \} \subset \mathbb{C}
\]

A captura de ecrã no topo do artigo mostra um conjunto de Julia produzido para um ponto $z_0$ qualquer que eu escolhi. Os pontos a preto são os que pertencem ao conjunto. Os pontos coloridos foram coloridos tal como expliquei no [artigo](https://mathspp.com/pt/blog/mandelbrot) sobre o conjunto de Mandelbrot. Fiz uma outra captura de ecrã, que incluo aqui:

![Uma outra captura de ecrã do meu programa para criar conjuntos de Julia](juliaset.png)

tirados de um [pequeno programa](https://github.com/RojerGS/projects/tree/master/fractals) em Python 3 que eu escrevi com pygame. Clicando num ponto qualquer do ecrã faz com que o programa crie o conjunto de Julia relativo ao ponto clicado. Usar as setas para a esquerda e para a direita permite-nos rever os conjuntos já criados e carregar na tecla do espaço apaga o conjunto que estiver a ser visualizado na altura. Um programa executável para Windows está disponível [aqui](https://github.com/RojerGS/projects/blob/master/fractals/juliaSet.rar).