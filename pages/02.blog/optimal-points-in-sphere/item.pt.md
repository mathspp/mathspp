---
title: Um algoritmo de descida do gradiente para distribuir pontos sobre uma esfera
---

![Animation of the algorithm](./n6s-2.gif)

===

Neste primeiro artigo vou partilhar um pequeno programa que eu escrevi para "resolver" um problema de optimização em geometria. O motivo pelo qual escrevo _"resolver"_ prende-se com o facto do meu código não ser bestial para resolver o problema nos casos mais interessantes; por outro lado, o meu programa permite que visualizemos o que "se está a passar". Ainda assim, para os casos mais triviais o meu programa deve conseguir encontrar a solução.

Reduzido ao máximo, o problema é: dada uma função de energia cujo valor depende das posições de vários pontos sobre uma esfera, encontrar as posições dos pontos que minimizam essa energia (que depende de um parâmetro extra). É isto. O programa que eu escrevi permite resolver o problema quando o número de pontos é pequeno e permite visualizar o processo de procura de uma solução: no início cria-se uma distribuição de pontos aleatória e depois vamos ajustando os pontos a pouco e pouco, até que se chega a um mínimo da função energia.

O relatório em português está [neste](https://drive.google.com/file/d/0ByBeLS6ciLYVem94djExRGFMTU0/view?usp=sharing) link e as imagens/GIFs/código estão [aqui](https://drive.google.com/file/d/0ByBeLS6ciLYVTjlZRTVRT1NZWGc/view?usp=sharing). A descrição do problema e o contexto em que fiz este projeto estão no início do relatório.