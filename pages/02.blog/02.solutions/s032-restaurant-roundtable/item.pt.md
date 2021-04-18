---
metadata:
    description: "Este artigo contém a minha proposta de solução para um dos problemas deste blogue."
title: "Solução #032 - à volta da mesa"
---

Este artigo contém a minha solução proposta
para o [Problema #032 - à volta da mesa][prob].
Por favor, não leias esta solução se ainda não tentaste
resolver [o problema][prob] a sério.

===

### Submissões

Parabéns a todos os que conseguiram resolver o problema e,
em particular, aos que me enviaram as suas soluções:

 - Attila K., Hungria;
 - Filippo M., Itália;
 - Luís S.;
 - André S..

(A lista não está ordenada.)


### Solução

Recebi algumas propostas de solução muito interessantes,
mas o que vou partilhar é uma versão daquela que eu acho a solução
mais elegante.

Sim, é sempre possível rodar a mesa por forma a que pelo menos duas
pessoas fiquem com o pedido certo à sua frente.

Suponhamos que cada pessoa que está sentada à mesa começa por olhar
para a sua esquerda e contar o número de pratos a que o seu pedido
está.

Por exemplo, se as pessoas estivessem sentadas desta maneira:

![](thumbnail.png)

Então as contagens seriam:

![](_roundtable_counts.png)

Se há $n$ pessoas à mesa, então o número de cada pessoa há de ser
um valor inteiro entre $1$ e $n - 1$, inclusivé.
O número de uma pessoa não pode ser $0$ porque isso significaria
que essa pessoa tinha o seu próprio prato à sua frente, e não
pode ser $n$ ou um número ainda maior porque $n$ representa uma volta inteira à mesa.

Assim, cada pessoa tem um número que está dentro do conjunto

$$
\{ 1, 2, \cdots, n-1 \} ~~~,
$$

mas há um total de $n$ pessoas e apenas $n - 1$ opções distintas.
Se aplicarmos o princípio do pombal, isto significa que há pelo menos
duas pessoas cujo número é igual a $d$.
Desta feita, se movermos a mesa $d$ lugares no sentido oposto ao
dos ponteiros do relógio, então essas pessoas passam a ter
o seu pedido à sua frente.


Se tens alguma questão sobre a minha solução, se encontraste algum erro (woops!) ou se gostavas de partilhar a *tua* solução, deixa um comentário em baixo.
Caso contrário podes só deixar um “upvote”!-->

[Não te esqueças de subscrever a newsletter][subscribe] para receberes
um problema diretamente no teu email de quinze em quinze dias!

[email]: mailto:rodrigo@mathspp.com?subject=Resposta%20de%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: https://mathspp.com/subscribe
[prob]: ../../problems/{{ page.slug }}
