---
metadata:
    description: Este artigo contém a minha proposta de solução para um dos problemas deste blogue.
title: 'Solução #021 - prever o lançamento da moeda'
---

Este artigo contém a minha solução proposta para o [Problema #021 - prever o lançamento da moeda][prob]. Por favor não leias esta solução se ainda não tentaste resolver [o problema][prob] a sério.

===

### Solução

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

Se tens alguma questão sobre a minha solução, se encontraste algum erro (woops!) ou se gostavas de partilhar a *tua* solução, deixa um comentário em baixo.

[prob]: ../../problems/{{ page.slug }}
