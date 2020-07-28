---
metadata:
  description: Neste artigo falo um pouco de uma charada conhecida sobre medir quantidades.
title: Uma charada com baldes de água
---

Será que és capaz de medir $2$L de água se tiveres um balde com $14$L de capacidade e outro com $5$L? Claro que sim!

===

![some gray tin (?) buckets](buckets.jpg "Photo by Carolyn V on Unsplash")

Os passos que deves seguir para medir os $2$L de água são os seguintes:

 - Despeja o balde de $5$L no de $14$L, depois
 - enche o balde de $5$L, depois
 - despeja o balde de $5$L no de $14$L, depois
 - enche o balde de $5$L, depois
 - despeja o balde de $5$L no de $14$L, depois
 - esvazia o balde de $14$L, depois
 - despeja o balde de $5$L no de $14$L, depois
 - enche o balde de $5$L, depois
 - despeja o balde de $5$L no de $14$L, depois
 - enche o balde de $5$L, depois
 - despeja o balde de $5$L no de $14$L, depois
 - enche o balde de $5$L, depois
 - despeja o balde de $5$L no de $14$L

Simples! Ok, talvez esta sequência de jogadas não seja assim tão simpática de encontrar à mão mas este é um problema relativamente conhecido e é bastante interessante: tendo $N$ jarros com capacidades $(c_1, \cdots, c_N)$ e querendo medir $T$ litros, haverá uma sequência de movimentos que o permite?

É óbvio que se a quantidade que queremos medir for maior que a capacidade de qualquer jarro, então não há solução. Por outro lado, só pode haver solução se $T$ for múltiplo do máximo divisor comum das capacidades $c_1, \cdots, c_N$. No entanto, eu não acho óbvio que esta condição seja necessária _e_ suficiente, isto é, não me parece óbvio que se $T$ for múltiplo do máximo divisor comum então o problema seja solúvel. $T$ ser um múltiplo do máximo divisor comum permite-nos encontrar valores $a_i$ inteiros tais que $a_1c_1 + a_2c_2 + \cdots + a_Nc_N = T$ mas não sei se conseguimos gerir a água dentro dos baldes para que todos os passos intermédios sejam possíveis.

Escrever um script que resolva o problema na sua forma geral revelou-se bastante mais simples do que eu estava à espera, bastou ver o problema de outra maneira: em cada momento, a quantidade de água nos jarros pode ser vista como um tuplo $(w_1, \cdots, w_N)$. Se pensarmos dessa maneira, podemos construir um grafo dirigido com vértices $V = \{(w_1,\cdots, w_N) \in \mathbb{N}_0^N | w_i\leq c_i \}$ e há uma aresta do vértice $a = (a_1, \cdots, a_N)$ para o vértice $b = (b_1, \cdots, b_N)$ se eu conseguir ir do estado $a$ para o $b$ esvaziando ou enchendo completamente um dos jarros, ou despejando a água de um jarro $i$ para um jarro $j$.

Vendo o problema desta forma, a pergunta original passa a ser:

_Será que existe um caminho no grafo descrito que comece em $(0, \cdots, 0)$ e termine num vértice em que alguma coordenada tem o valor $T$?_

Para resolver o problema, implementei o [algoritmo de procura em largura](https://pt.wikipedia.org/wiki/Busca_em_largura) e vou construindo apenas as partes do grafo de que vou precisando. Isto também garante que a resposta que o script encontra é a mais curta possível.

A implementação está [neste script](https://github.com/RojerGS/projects/blob/master/misc/bucketSolver.py) que pode ser encontrado no GitHub. Alternativamente, podem experimentar correr o script no widget disponível em baixo, embora eu recomende que o façam [diretamente no repl.it](https://repl.it/@RojerGS/PoisedRepentantIndianjackal). No topo do script há duas variáveis, `T` e `buckets`, que contêm respetivamente o valor alvo e as capacidades dos baldes. Estas variáveis podem ser alteradas para resolver problemas com outras configurações.

<iframe allowfullscreen="true" allowtransparency="true" frameborder="no" height="400px" sandbox="allow-forms allow-pointer-lock allow-popups allow-same-origin allow-scripts allow-modals" scrolling="no" src="https://repl.it/@RojerGS/WaterBuckets?lite=true" width="100%"></iframe>

Consegues arranjar um valor de `T` e um conjunto de capacidades `buckets` que seja possível mas bastante difícil de resolver?

Partilha as tuas descobertas comigo (e com todos) na secção de comentários em baixo.
