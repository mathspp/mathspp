---
title: 'Solução #003 - uma briga no Shire'
---

Este artigo contém a minha solução proposta para o [Problema #003 - uma briga no Shire][prob]. Por favor não leias esta solução se ainda não tentaste resolver [o problema][prob] a sério.

===

### Solução

A tarefa do Gandalf termina sempre. Para perceber porquê, pensa nos $H$ Hobbits como pontos e cada par de Hobbits está ligado por uma linha. Essa linha é verde se os dois Hobbits estiverem bem um com o outro (se as suas portas tiverem a mesma cor) e a linha é vermelha se os dois Hobbits estiverem chateados (se as suas portas tiverem cores diferentes). Agora conta o número de linhas vermelhas no boneco que fizeste e chama $R_0$ a esse número, onde o $0$ aparece porque o Gandalf fez $0$ visitas. Depois do Gandalf visitar $t$ Hobbits, $R_t$ representa o número de linhas vermelhas no boneco nessa altura.

Devia ser relativamente simples de perceber que $R_{t+1} \leq R_t$. Isto é verdade porque, quando o Gandalf visita um Hobbit, ele só muda a cor da porta se isso significar que o número de Hobbits amigos aumente, isto é, se o número de linhas verdes aumentar / número de linhas vermelhas diminuir. Isto significa que uma de duas coisas terá de acontecer:

 - Para um dado $k$, $R_k = 0$ e todos os Hobbits são amigos uns dos outros, de novo;
 - Para algum $k$, $R_{k+N} = R_k$, o que significa que o Gandalf visitou todos os Hobbits no Shire e não mudou a cor de uma única porta, o que significa que as suas visitas já não fazem diferença.

De qualquer dos modos, podemos ver que outra ronda de visitas por parte do Gandalf não faria qualquer diferença e portanto o Gandalf pode ir descansar.

Pergunta bónus: consegues arranjar algum valor de $N > 1$ e alguma configuração das cores das portas de modo a que aconteça o segundo caso da lista em cima? Isto é, que as visitas do Gandalf deixem de fazer diferença mas nem todos os Hobbits são amigos uns dos outros? Diz-me o que encontraste na secção de comentários em baixo ;)

[prob]: ../../{{ page.slug }}
