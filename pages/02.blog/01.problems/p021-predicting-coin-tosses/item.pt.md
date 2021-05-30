---
metadata:
    description: Neste problema, dois amigos encarcerados vão ter de adivinhar o resultado de lançamentos de moedas.
title: 'Problema #021 - prever o lançamento da moeda'
---

A Alice e o Bruno vão ser encarcerados separadamente e os seus destinos dependem das suas capacidades de adivinhação!

===

![Uma moeda enterrada na areia.](coin-in-sand.jpg "Foto do Mark Normand, retirada do site FreeImages")


# Enunciado do problema

A Alice e o Bruno vão ser separados e encarcerados. Todos os dias às 12h, o guarda Carlos vai ter com a Alice e o guarda Duarte vai ter com o Bruno; cada guarda pega na sua moeda de carcereiro (uma moeda com cara e coroa, não necessariamente "equilibrada") e lança-a ao ar, mostrando o resultado ao prisioneiro à sua frente. De seguida, cada prisioneiro tenta adivinhar qual o resultado que saiu à frente do seu colega. Ou seja, a Alice vê o resultado do lançamento do guarda Carlos e tem de adivinhar qual o resultado que saiu ao Bruno e ao guarda Duarte, e vice-versa. Se qualquer um dos prisioneiros acertar, os dois são libertados.

Qual é que é a estratégia que a Alice e o Bruno devem combinar, para que sejam libertos o mais cedo possível. Segundo essa estratégia, qual é o número médio de dias que eles vão permanecer encarcerados?

!!! Pensa um pouco...

Se precisares de clarificar alguma coisa, não hesites em perguntar na secção de comentários em baixo.


# Solução

Existe uma estratégia perfeita que permite que a Alice e o Bruno escapem da prisão no primeiro dia. Se tentaram resolver o problema mas não conseguiram, tentem novamente com esta pista. Procurem uma estratégia perfeita, porque ela existe.

A estratégia perfeita é: a Alice diz um palpite igual ao lançamento em frente dela e o Bruno diz um palpite oposto ao lançamento em frente dele.

Para compreendermos bem porque é que esta estratégia é perfeita, vamos começar por analisar quais são os resultados possíveis dos lançamentos das moedas:

| Carlos | Duarte |
| :-: | :-: |
| Cara | Cara |
| Cara | Coroa |
| Coroa | Cara |
| Coroa | Coroa |
<br />

Como se pode ver pela tabela em cima, existem quatro resultados possíveis. Note-se que o facto das moedas poderem não ser equilibradas não afeta de modo nenhum a tabela em cima. Mas a grande epifania necessária é perceber que os resultados que interessam são só dois, quando as moedas têm resultados iguais ou resultados diferentes:

| Carlos | Duarte | Resultados são... | Quem acerta |
| :-: | :-: | :-: | :-: |
| Cara | Cara | Iguais | Alice |
| Cara | Coroa | Diferentes | Bruno |
| Coroa | Cara | Diferentes | Bruno |
| Coroa | Coroa | Iguais | Alice |
<br />

É só isto! Não é preciso fazer contas chatas, basta ter uma ideia muito boa.

Não te esqueças de [subscrever a newsletter][subscribe] para receberes os problemas diretamente na tua caixa de correio,
e deixa a tua reação a este problema em baixo.

[subscribe]: https://mathspp.com/subscribe
