---
metadata:
    description: Neste artigo desafio-te a dobrares uma folha de papel... Soa fácil?
        Prometo que não vai ser ;)
title: 'Problema #019 - dobrar o alfabeto'
---

Pega numa folha de papel e num lápis. Agora vou pedir-te que escrevas umas letras na folha e depois vou desafiar-te a dobrar a folha... Soa fácil? Prometo que não vai ser.

===

![A picture of some paper sheets folded in a nice pattern](aaa.jpg "Photo by Thomas Renaud on Unsplash")

### Preparação

Pega numa folha de rascunho e deita-a na horizontal.

Agora preciso que dobres a folha ao meio, ao longo do eixo horizontal de simetria; de seguida dobra ao meio, ao longo do eixo vertical de simetria duas vezes consecutivas.

No fim, é suposto teres uma folha dividida em oito retângulos:

![The creases marked in a piece of paper](frame.png)

Exceto que as tuas dobras deviam estar centradas e no meu desenho ficou tudo torto... Azar!

### Enunciado

O desafio é o seguinte: vou pedir-te que escrevas as letras `ABCDEFGH` nos retângulos que criaste e depois tens de dobrar a folha de papel de modo a que

 - os oito retângulos estejam todos empilhados;
 - folheando os retângulos um a um, as letras do alfabeto devem aparecer por ordem.

A única restrição é que não podes cortar a folha de papel! O objetivo é _mesmo_ dobrar a folha da forma certa, não é pegar numa tesoura, recortar os oito retângulos e empilhá-los.

Vou desafiar-te com três níveis, que vão corresponder a disposições diferentes das letras na folha. Começa pelo nível 1: escreve as letras a lápis na folha e tenta fazer as dobras corretas. Quando conseguires, apaga as letras e tenta o nível 2 e depois o nível 3. Boa sorte!

#### Nível 1

![Left to right, top to bottom the letters are ADEG / BCFH](lvl1.png "ADEG / BCFH")

#### Nível 2

![Left to right, top to bottom the letters are AHGD / BCFE](lvl2.png "AHGD / BCFE")

#### Nível 3

![Left to right, top to bottom the letters are AHBG / DECF](lvl3.png "AHBG / DECF")

!!! Pensa um pouco... e mais importante de tudo, pega numa folha de papel e tenta! Conta-me como correu na secção de comentários no fim da artigo ;)

!!!! **Dica**: se estiver a ser difícil manter tudo em ordem e saber que retângulo é qual, talvez valha a pena escrever as letras também na parte de trás da folha... mas **tem cuidado**, um retângulo deve ter a **mesma** letra à frente e atrás.

!!!! **Dica**: se o papel for demasiado rijo podes tentar amarrotá-lo todo e depois voltar a esticar antes de tentares fazer as dobras todas.
O amigo que me falou deste problema viu-o [neste vídeo do YouTube](https://www.youtube.com/watch?v=GpClxF41ugg).

### Soluções

Podes encontrar a minha proposta de solução [aqui][sol], para confirmares a tua resposta.

[sol]: ../s/{{ page.slug }}
