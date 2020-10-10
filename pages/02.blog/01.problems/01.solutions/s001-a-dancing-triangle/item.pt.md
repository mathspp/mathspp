---
title: 'Solução #001 - um triângulo dançante'
---

Este artigo contém a minha solução proposta para o [Problema #001 - um triângulo dançante][prob]. Por favor não leias esta solução se ainda não tentaste resolver [o problema][prob] a sério.

===

### Solução

A resposta à pergunta é _não_, não há maneira de fazer o nosso triângulo _"crescer"_ e fazer com que os lados passem a ter o dobro do tamanho. Para vermos porque é que isso não é possível, vamos mostrar que a área de um triângulo não muda quando aplicamos a transformação que defini no início. Se fizermos isso - se mostrarmos que a área do triângulo não altera durante as transformações - então mostramos que o nosso triângulo não pode ficar com lados duas vezes maiores, porque isso faria com que a área quadriplicasse.

Seja $[ABC]$ um triângulo e suponhamos, sem perda de generalidade, que vamos aplicar a transformação a $C$ (para facilitar a visualização, imaginem que $[AB]$ é a base do triângulo e que está na horizontal). Sabemos que $C$ vai ser movido para um ponto $C'$ que fica numa linha que passa em $C$ e que é paralela a $[AB]$. Também sabemos que a fórmula para a área de um triângulo é $\frac{b \times h}{2}$ onde $b$ é o comprimento da base do triângulo (que no nosso caso é $[AB]$) e onde $h$ é a altura do triângulo. Ora, mudar $C$ para $C'$ claramente não muda a base... e na verdade também não muda a altura! A altura $h$ do triângulo $[ABC]$ é o comprimento do segmento de reta que:
 1. passa em $C$
 2. é perpendicular a $[AB]$
 
E a altura $h'$ do triângulo $[ABC']$ é o comprimento do segmento de reta que passa em $C'$ e é perpendicular a $[AB]$. Mas $C$ e $C'$ estão sobre uma linha que é paralela a $[AB]$, o que faz com que $h$ e $h'$ sejam iguais; por outras palavras, a altura não mudou e portanto a área do triângulo também não mudou. QED.

[prob]: {{ page.url|to_problem }}
