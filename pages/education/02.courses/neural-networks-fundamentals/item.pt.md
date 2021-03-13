---
title: "Fundamentos de redes neuronais"
---

Aprende como funcionam as redes neuronais e implementa uma de raíz.

===

![](thumbnail.png?cropZoom= "Photo by JJ Ying on Unsplash.")


# Objetivo

Este curso tem por objetivo levar os participantes a perceberem
melhor como é que as redes neuronais funcionam.

Hoje em dia é muito fácil usar redes neuronais em qualquer
linguagem de programação,
já que existem variados pacotes/plugins/bibliotecas que permitem
interagir com modelos de aprendizagem automática.
Esta democratização do acesso aos modelos
de aprendizagem automática faz com que as pessoas já não tenham
de *compreender* os modelos para os conseguir usar.
Isto não é necessariamente algo mau, mas se o teu objetivo é
perceber melhor como é que as redes neuronais funcionam, então
implementares uma rede de raíz é capaz de te ensinar mais do que
se te limitares a reutilizar código de terceiros.


# Pré-requisitos

Este curso pode ser dado em três sessões de 90 minutos se os
participantes tiverem confortáveis com uma linguagem de programação
(não necessariamente a linguagem que vai ser usada no curso)
e se tiverem uma ideia básica do que são redes neuronais.

Para além disso, assume-se que os participantes já foram expostos
a álgebra matricial e ao conceito de derivada.

Naturalmente, estes pré-requisitos podem deixar de ser pré-requisitos
se a duração do curso for extendida.


# Linguagem de programação

O curso pode ser dado numa qualquer linguagem de programação
que seja decidida de antemão.
As minhas duas recomendações pessoais são que se utilize Python
ou APL, duas linguagens bastante indicadas para o tipo de código
que escrevemos durante o curso.


# Programa curricular

O programa do curso é o seguinte:

 1. conceito de neurónio, camada (de neurónios) e rede neuronal;
 2. funções de ativação e propagação de informação;
 3. performance da rede neuronal e funções objetivo;
 4. treino da rede e retroalimentação;
 5. reconhecimento de dígitos manuscritos.

Eu escrevi uma série de artigos que seguem o programa curricular
do curso, para que te possas preparar para as sessões
(ou para que possas rever o que fizeste).
Os artigos também guiam o leitor na implementação da rede em Python.
Os pontos 1. e 2. são cobertos no artigo [“Introdução”][nnfwp-intro], o ponto 3.
em [“Erro”][nnfwp-network-loss], o ponto 4.
em [“Retroalimentação”][nnfwp-backprop] e o ponto 5.
em [“MNIST”][nnfwp-mnist].

O programa do curso pode ser extendido, por exemplo
se discutirmos mais funções de ativação e funções objetivo
(bem como as suas utilizações) ou se fizermos a experiência
das redes professor e aluno.


# Feedback de participantes

Aqui encontras algumas das coisas que participantes do curso
disseram:

 > “[...] Foi muito interessante ver tudo em ação.
 > E o ambiente era muito confortável.” ― Laura F.

<!---->

 > “[Os pontos fortes do curso foram] o aprofundamento do
 > meu conhecimento sobre redes neuronais.
 > Como funcionam, e a estrutura e a matemática por detrás delas.” ― Anónimo.

<!---->

 > “A dinâmica e a empatia geradas durante o curso foram excelentes.” ― Gonçalo R.


Estás à vontade para [me enviares um email][emailme]
caso queiras que eu dê este curso a um grupo de pessoas
que tu representas.


# Links úteis

 - Série de artigos no blogue que seguem o programa do curso:
   - [“Introdução”][nnfwp-intro]
   - [“Erro”][nnfwp-network-loss]
   - [“Retroalimentação”][nnfwp-backprop]
   - [“MNIST”][nnfwp-mnist]
 - O repositório que contém uma implementação de referência, em Python: [https://github.com/RojerGS/education/tree/master/neural-networks-fundamentals-with-python](https://github.com/RojerGS/education/tree/master/neural-networks-fundamentals-with-python)
 - Série curta (e excelente) do 3blue1brown sobre redes neuronais:
[https://www.youtube.com/playlist?list=PLZHQObOWTQDNU6R1_67000Dx_ZCJB-3pi](https://www.youtube.com/playlist?list=PLZHQObOWTQDNU6R1_67000Dx_ZCJB-3pi)


[nnfwp-intro]: /blog/neural-networks-fundamentals-with-python-intro
[nnfwp-network-loss]: /blog/neural-networks-fundamentals-with-python-network-loss
[nnfwp-backprop]: /blog/neural-networks-fundamentals-with-python-backpropagation
[nnfwp-mnist]: /blog/neural-networks-fundamentals-with-python-mnist
[3b1b-series]: https://www.youtube.com/playlist?list=PLZHQObOWTQDNU6R1_67000Dx_ZCJB-3pi
[mailme]: mailto:rodrigo@mathspp.com
