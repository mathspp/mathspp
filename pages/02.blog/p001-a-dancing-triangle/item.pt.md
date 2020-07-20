---
title: "Problema #001 - um triângulo dançante"
date: 06-11-2017
slug: a-dancing-triangle
taxonomy:
    category: blogpost
    tag: [problem, geometry, mathematics, invariant]
---

O formato deste artigo vai ser um pouco diferente do que tenho feito até agora, e vai ser a primeira de várias publicações nestes moldes: vou introduzir um pequeno problema _"de matemática"_ e depois vou partilhar uma possível solução.

===

![Uma esquematização do processo explicado em baixo](dancing_triangle_scheme.png)

### Enunciado do problema

Seja $[ABC]$ um triângulo qualquer. Vamos agora definir uma transformação que podemos aplicar ao triângulo, e que tem como efeito mover um dos vértices do triângulo e deixar os outros dois vértices fixos. Para aplicar a transformação, há que começar por escolher o vértice que se vai mover (vamos supor que queremos mover o vértice $C$). Agora consideramos a linha reta que passa por $C$ e que é paralela a $[AB]$ e escolhemos um ponto $C'$ nessa reta. O nosso triângulo passa a ser $[ABC']$. Esta transformação pode ser aplicada quantas vezes quisermos, aos vértices que quisermos.<br />
Será que há alguma maneira de, com esta transformação, fazer com que o nosso triângulo cresça e os lados fiquem todos com o dobro do tamanho? Como/porquê?

!!! Pensem um pouco...

### Solução

A resposta à pergunta é _não_, não há maneira de fazer o nosso triângulo _"crescer"_ e fazer com que os lados passem a ter o dobro do tamanho. Para vermos porque é que isso não é possível, vamos mostrar que a área de um triângulo não muda quando aplicamos a transformação que defini no início. Se fizermos isso - se mostrarmos que a área do triângulo não altera durante as transformações - então mostramos que o nosso triângulo não pode ficar com lados duas vezes maiores, porque isso faria com que a área quadriplicasse.

Seja $[ABC]$ um triângulo e suponhamos, sem perda de generalidade, que vamos aplicar a transformação a $C$ (para facilitar a visualização, imaginem que $[AB]$ é a base do triângulo e que está na horizontal). Sabemos que $C$ vai ser movido para um ponto $C'$ que fica numa linha que passa em $C$ e que é paralela a $[AB]$. Também sabemos que a fórmula para a área de um triângulo é $\frac{b \times h}{2}$ onde $b$ é o comprimento da base do triângulo (que no nosso caso é $[AB]$) e onde $h$ é a altura do triângulo. Ora, mudar $C$ para $C'$ claramente não muda a base... e na verdade também não muda a altura! A altura $h$ do triângulo $[ABC]$ é o comprimento do segmento de reta que:
 1. passa em $C$
 2. é perpendicular a $[AB]$
 
E a altura $h'$ do triângulo $[ABC']$ é o comprimento do segmento de reta que passa em $C'$ e é perpendicular a $[AB]$. Mas $C$ e $C'$ estão sobre uma linha que é paralela a $[AB]$, o que faz com que $h$ e $h'$ sejam iguais; por outras palavras, a altura não mudou e portanto a área do triângulo também não mudou. QED.