---
metadata:
    description: "Neste problema vamos calcular quantos cocos é que um grupo de marinheiros conseguiu reunir, ao naufragarem numa ilha deserta."
title: "Problema #027 - pilha de cocos 🥥"
---

Cinco marinheiros e o macaco de estimação naufragam numa ilha deserta, e decidem
ir arranjar cocos, que põem numa pilha.
Desconfiados uns dos outros, durante a noite cada marinheiro vai à pilha retirar
a sua quota parte, sem os outros verem.
Quantos cocos havia no início..?

===

![Uma fotografia com alguns cocos, do utilizador "zibik" do site unsplash.com.](thumbnail.jpg)


# Enunciado do problema

Um grupo de cinco marinheiros e um macaco naufragaram numa ilha.
Depois de passado o choque inicial, decidiram que deviam reunir mantimentos para os próximos dias.
Separaram-se para ir buscar os mantimentos mas só encontraram cocos.
Quando se reuniram ao fim do dia, fizeram uma grande pilha com os cocos e foram dormir.

A meio da noite, o primeiro marinheiro - muito desconfiado dos outros - aproximou-se da pilha e tentou dividir o monte em cinco para ficar já com a sua parte.
Quando fez a divisão, viu que sobrava um coco e decidiu dar esse coco ao macaco; pegou na sua quinta parte dos cocos restantes e foi dormir.

Um pedaço depois aconteceu o mesmo com o segundo marinheiro: este acordou e, desconfiado dos outros, foi buscar já a quinta parte dos cocos encontrados.
Aproximou-se da pilha e, ao fazer a divisão, viu que sobrava um coco, que deu ao macaco.
De seguida pegou na sua quinta parte e foi dormir.

Isto repetiu-se com os outros três marinheiros: todos acordaram desconfiados, foram dividir os cocos e todos eles tiveram de dar um coco que sobrava ao macaco.

Qual o número mínimo de cocos que os marinheiros têm de ter reunido e posto na pilha para que isto seja possível?

!!! Pensa um pouco...

Se precisares de clarificar alguma coisa, não hesites em perguntar na secção de comentários em baixo.


# Solução

Para determinar o número de cocos que os marinheiros conseguiram encontrar vamos
usar um pouco de aritmética modular.

Vamos chamar $x$ ao número de cocos que ficou na pilha depois dos cinco marinheiros
interferirem com a pilha de cocos.
Vamos usar $x$ para escrever uma expressão que represente o número inicial de cocos
e depois vamos usar essa expressão para descobrir o valor de $x$.

Para tal, vamos analisar as alterações que os marinheiros fizeram à pilha de cocos:
o quinto marinheiro pegou num coco, que deu ao macaco, e depois ficou com um quinto
dos cocos restantes, deixando um total de $x$ cocos na pilha.
Isto quer dizer que, quando o quinto marinheiro chegou junto da pilha, a pilha
tinha exatamente

$$
\frac54 x + 1
$$

cocos.
Repetindo o processo de multiplicar por $\frac54$ e somar $1$,
vemos que a pilha tinha

$$
\frac54\left(\frac54 x + 1\right)  + 1 = \frac{25}{16}x + \frac94
$$

cocos quando o quarto marinheiro chegou junto à pilha, o que quer dizer que quando
o terceiro marinheiro foi ver da pilha, esta tinha

$$
\frac54\left(\frac{25}{16}x + \frac94 \right) + 1 = \frac{125}{64}x + \frac{61}{16}
$$

cocos.
Repetindo estas contas mais duas vezes, vemos que a pilha tinha

$$
\frac54\left(\frac{125}{64}x + \frac{61}{16}\right) + 1 =
\frac{625}{256}x + \frac{369}{64}
$$

cocos quando o segundo marinheiro chegou junto dela, e exatamente

$$
\frac54\left(\frac{625}{256}x + \frac{369}{64}\right) + 1 =
\frac{3125}{1024}x + \frac{2101}{256}
$$

cocos quando o primeiro marinheiro foi mexer no monte de cocos, i.e. inicialmente os
marinheiros tinham um monte de cocos com

$$
\frac{3125}{1024}x + \frac{2101}{256} = \frac{3125x + 8404}{1024}
$$

cocos.

O enunciado do problema diz-nos que o número de cocos inicial era um número inteiro
(ou seja, os marinheiros só recolheram cocos inteiros).
Isto significa que $3125x + 8404$ tem de ser um múltiplo de $1024$, para que a
fração em cima seja um número inteiro.
Em notação matemática, isto escreve-se

$$
3125x + 8404 \equiv 0 \mod 1024 ~ .
$$

Repara que $3125 = 53 + 3\times 1024$ e $8404 = 212 + 8\times 1024$.
Se inserirmos estas expressões em $3125x + 8404$, obtemos

$$
53x + 3\times 1024x + 212 + 8\times 1024 ~ ,
$$

que representa um número que devia ser múltiplo de $1024$.
Os termos que estão a ser multliplicados por $1024$ já são múltiplos de $1024$,
logo só precisamos de garantir que $53x + 212$ é um múltiplo de $1024$.
Em notação matemática, este passo intermédio escreve-se

$$
3125x + 8404 \equiv 53x + 212 \mod 1024 ~ .
$$

Isto significa que agora temos uma equação mais simples para resolver:

$$
53x + 212 \equiv 0 \mod 1024
$$

Outra coisa que podemos fazer para simplificar esta equação é reparar que
$53x + 212 = 53(x + 4)$.
Precisamos que $53(x + 4)$ seja um múltiplo de $1024 = 2^{10}$, ou seja,
precisamos que o número $53(x + 4)$ seja divisível por $2$ dez vezes.
Infelizmente, o número $53$ *não* é divisível por $2$ nenhuma vez, logo é o fator
$x + 4$ que tem de ser divisível por $2$ dez vezes.

Agora que sabemos que $x + 4$ é um múltiplo de $2^{10} = 1024$,
e porque sabemos que $x > 0$ porque $x$ é o número de cocos no monte final,
sabemos que $x + 4$ está na lista seguinte:

$$
1024, 2048, 3072, 4096, \cdots
$$

ou, por outras palavras, $x$ está na lista

$$
1020, 2044, 3068, 4092, \cdots
$$

O enunciado do problema pergunta qual é o número *mínimo* de cocos que a pilha podia
ter, portanto temos de escolher o menor número da lista, que é $x = 1020$.
Se $x = 1020$, então o número de cocos no monte inicial é

$$
\frac{3125\times 1020 + 8404}{1024} = 3121 ~ _\blacksquare
$$

Assim, concluímos que $3121$ é a resposta certa para o problema dos cocos.

Este problema tinha uma solução com mais contas do que os problemas que eu costumo
publicar no blogue.
O que é que achaste disto?
Preferes problemas com mais contas ou com menos contas?
Partilha a tua opinião, escrevendo um comentário aqui em baixo.


Não te esqueças de [subscrever a newsletter][subscribe] para receberes os problemas diretamente na tua caixa de correio,
e deixa a tua reação a este problema em baixo.

[subscribe]: https://mathspp.com/subscribe
[sol]: ../../solutions/{{ page.slug }}
