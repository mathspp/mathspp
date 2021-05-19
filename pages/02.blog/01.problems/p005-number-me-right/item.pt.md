---
metadata:
    description: Este artigo contém um problema muito interessante que se desenrola
        num tabuleiro infinito!
title: 'Problema #005 - numera-me corretamente'
---

O problema contido neste artigo é um problema que resolvi duas vezes. Da primeira vez consegui resolvê-lo corretamente mas não deixei tudo bem provado... uns anos mais tarde lembrei-me do problema e dessa vez já o consegui resolver como deve ser. Vamos ver como te sais!

===


# Enunciado

Imagina um tabuleiro de xadrez que se extende indefinidamente para cima e para a direita. No canto inferior esquerdo vais pôr um $0$ e nos outros quadrados pões o menor número não-negativo que

 - ainda não apareceu diretamente por baixo dessa célula;
 - ainda não apareceu diretamente à esquerda dessa célula.

Assim, por exemplo, a primeira linha do tabuleiro vai ter os números $0, 1, 2, 3, \cdots$. Qual é o número que aparece na $1997$&ordf; linha e na $2018$&ordf; coluna?

!!! Pensa um pouco... o meu melhor conselho é que desenhes uma grelha e a comeces a preencher seguindo as regras do enunciado. Consegues encontrar algum padrão?

Se precisares de clarificar alguma coisa, não hesites em perguntar na secção de comentários em baixo.


# Solução

A chave da minha solução está em perceber o modo como a grelha é preenchida. Da primeira vez que resolvi o problema comecei por fazer aquilo que sugeri em cima, desenhei uma grelha e comecei a preenchê-la à mão para ver o que acontecia. Ao fazê-lo, comecei a perceber algumas coisas mais óbvias como:

 - na diagonal aparece sempre o $0$;
 - o tabuleiro é simétrico em relação à diagonal, i.e. o número na coluna $j$ e na linha $i$ é o mesmo que na linha $j$ e na coluna $i$.

Depois de estudar padrões como este acabei por conjeturar o seguinte:

**Conjetura:** o número na $i+1$&ordf; linha e na $j+1$&ordf; coluna é $i \hat{} j$, onde $\hat{}$ representa a aplicação da operação [XOR] bit por bit.

A operação binária XOR devolve $1$ quando apenas um dos bits de entrada é $1$. Caso contrário devolve $0$. Por exemplo, $1100_2 \hat{} 1010_2 = 0110_2$.

Repara que isto está de acordo com as duas observações que eu já tinha feito:

 - a diagonal só tem o número $0$; na diagonal o número da linha e da coluna são iguais e $n \hat n = 0$;
 - estar na linha $i$ e coluna $j$ é o mesmo que estar na linha $j$ e coluna $i$, já que $i \hat{} j = j \hat{} i$.

De ora em diante vou numerar as linhas e as colunas a começar no $0$ e vou usar $c(i, j)$ para me referir ao valor na célula da linha número $i$ e da coluna número $j$. Seja $P(n)$ a asserção "a região quadrada de lado $2^n$ que está no canto inferior esquerdo do tabuleiro tem todos os números de $0$ a $2^n - 1$ em cada linha **e**

\[
    \forall i,j \leq 2^n-1: c(i,j) = i \hat{} j\ .
\]

Isto significa que para calcular o valor de uma célula podemos usar a operação XOR.

Vamos provar que a asserção $P(n)$ é verdadeira para todo o $n$ por indução. $P(0)$ é trivialmente verdade porque o quadrado de lado $2^0 = 1$ corresponde apenas à célula $0$ que é numerada pelo próprio enunciado do problema e de facto $c(0, 0) = 0 \hat{} 0 = 0$.

Vamos assumir que $P(n)$ é verdade e agora mostramos que então $P(n+1)$ também é verdade.

![A 4 by 4 square already filled in](nmr_1.png)

Na imagem em cima, exemplificamos com $P(2)$ já preenchido. Agora vamos mostrar que conseguimos numerar todas as células da imagem usando sempre a mesma regra (i.e. usando a operação XOR) e vamos ainda mostrar que cada linha da imagem vai contar os números $0, 1, \cdots, 6, 7$. Para facilitar a vida a toda a gente, vamos pegar no quadrado grande da imagem e dividi-lo em quatro quadrados mais pequenos, todos $4 \times 4$; o quadrado $SO$ é o que já está preenchido e por cima dele está o quadrado $NO$. À direita do quadrado $SO$ está o quadrado $SE$ e, no canto superior direito do quadrado grande $8 \times 8$, está o quadrado mais pequeno $NE$.

Vamos começar por preencher o quadrado $SE$ que tem lado $2^n$, o que está à direita do pedaço já preenchido (na imagem, é o bloco $4 \times 4$ que já tem números). Podemos ver que cada linha tem contém os números de $0$ a $2^n - 1$, portanto decorre facilmente que o preenchimento do quadrado $SE$ vai ser igual ao do quadrado $SO$, exceto que a cada célula somamos $2^n$. Basta-nos verificar que a regra para calcular o número em cada célula continua válida. Seja $0 \leq i < 2^n$ e $0 \leq j < 2^n$. Então $(i, 2^n+j)$ representa coordenadas válidas para uma célula dentro do quadrado $SE$ e

\[
    i \hat{} (2^n + j) = 2^n +  (i \hat{} j) = 2^n + c(i,j)
\]

que é exatamente o valor que nós atribuímos à célula $(i, 2^n + j)$. A razão pela qual $i \hat{} (2^n + j) = 2^n + (i \hat{} j)$ é porque $i < 2^n$. Basta notar que $2^n + j$ começa com $1$ em binário, ao passo que $i$ começa com $0$ se forçarmos a representação binária de $i$ a ter tantos dígitos quanto a de $2^n + j$.

Neste momento estamos assim:

![A 4 by 8 rectangle filled in](nmr_2.png)

Por simetria, vamos obter que o quadrado pequeno $NO$, de lado $2^n$, é exatamente igual ao quadrado $SE$ que acabámos de criar e mostrar que a regra com a operação XOR também funciona em cima à esquerda é exatamente como mostrar que a regra funciona em baixo à direita. Logo, estamos neste ponto:

![An L shaped region filled in](nmr_3.png)

O que nos falta agora é entender como é que o canto $NE$ é preenchido. Tanto à sua esquerda (no quadrado $NO$) como por baixo dele (no quadrado $SE$) já estão todos os números de $2^n$ a $2^{n+1} - 1$, logo cada linha e cada coluna tem à sua disposição os números de $0$ a $2^n - 1$. Mas essa situação é exatamente igual à situação em que estamos quando vamos preencher o canto $SO$ do tabuleiro, logo os quadrados $NE$ e $SO$ são o mesmo. Agora assume que $0 \leq i, j < 2^n$. Então $(2^n + i, 2^n + j)$ representa as coordenadas de uma célula no quadrado $NE$ e decorre imediatamente que

\[
    c(2^n+i, 2^n+j) = (2^n + i)\hat{}(2^n+j) = i\hat{}j = c(i,j)\ ,
\]

que é o valor da célula correspondente no quadrado $SO$, tal como esquematizado aqui em baixo:

![The 8 by 8 square filled in](nmr_4.png)

Isto conclui o passo indutivo, mostrando que a regra com a operação XOR funciona realmente. Para calcularmos o valor da célula na $1997$&ordf; linha e na $2018$&ordf; coluna basta-nos escrever $1996$ e $2017$ em binário:

\[
    \begin{cases}
        1996 = 1024 + 512 + 256 + 128 + 64 + 8 + 4 \\
        2017 = 1024 + 512 + 256 + 128 + 64 + 32 + 1
    \end{cases}
\]

o que significa que $1996$ é $11111001100_2$ em binário e $2017$ é $11111100001_2$, logo

\[
    c(1996, 2017) = 11111001100_2 \hat{} 11111100001_2 = 00000101101_2
\]

E o número de que estamos à procura é exatamente $45$.


Não te esqueças de [subscrever a newsletter][subscribe] para receberes os problemas diretamente na tua caixa de correio,
e deixa a tua reação a este problema em baixo.

[email]: mailto:rodrigo@mathspp.com?subject=Resposta%20para%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: https://mathspp.com/subscribe
