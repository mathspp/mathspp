---
metadata:
    description: Este artigo contém a minha proposta de solução para um dos problemas deste blogue.
title: 'Solução #027 - pilha de cocos'
---

Este artigo contém a minha solução proposta para o [Problema #027 - pilha de cocos][prob]. Por favor não leias esta solução se ainda não tentaste resolver [o problema][prob] a sério.

===

### Solução

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

Se tens alguma questão sobre a minha solução, se encontraste algum erro (woops!) ou se gostavas de partilhar a *tua* solução, deixa um comentário em baixo.
Caso contrário podes só deixar um “upvote”!

Já agora, [não te esqueças de subscrever a newsletter][subscribe] para receberes
um problema diretamente no teu email de quinze em quinze dias!

[subscribe]: https://mathspp.com/subscribe
[prob]: ../../problems/{{ page.slug }}
