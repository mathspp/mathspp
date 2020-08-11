---
metadata:
    description: Neste artigo falo de três métodos comuns para se escreverem provas matemáticas.
title: O CCC das provas matemáticas
---

Neste artigo falo de três métodos comuns para fazer provas matemáticas: prova por construção, por contrapositivo e por contradição.

===

![a board with some mathematical squiggles](board.jpg "Photo by Roman Mager on Unsplash")

### Prova por construção

Provas por construção são, na minha opinião, as mais intuitivas. Quando provamos alguma coisa por construção, o que estamos a fazer é mostrar como construir aquilo que estamos a dizer que existe, ou a dar uma forma explícita de verificar o que acabámos de descrever como verdadeiro. Isto é importante porque, frequentemente, a matemática prova coisas como _"um objecto $x$ com estas propriedades existe"_, e depois não se diz nada sobre como encontrar esse objeto $x$.

Um bom exemplo de uma prova por construção é a prova de que qualquer função $f: \mathbb{R}\to\mathbb{R}$ pode ser decomposta numa soma $f(x) = O(x) + E(x)$ onde $O(x)$ é uma função ímpar e $E(x)$ é uma função par, i.e.

$$
    \begin{cases}
        O(-x) = -O(x)\\
        E(-x) = E(x)
    \end{cases}\ \forall x \in \mathbb{R}
$$

Para provar isto vou construir $O$ e $E$ diretamente a partir de $f$; depois mostro que $O$ é ímpar, que $E$ é par e que $f(x) = O(x) + E(x)$. Nesse sentido, define

$$
    \begin{cases}
        E(x) = \frac{f(x) + f(-x)}{2}\\
        O(x) = \frac{f(x) - f(-x)}{2}
    \end{cases}
$$

Vou mostrar primeiro que $O$ e $E$ somadas dão $f$:

$$
\begin{align}
    O(x) + E(x) &= \frac{f(x) - f(-x)}{2} + \frac{f(x) + f(-x)}{2} \\
    &= \frac{f(x) - f(-x) + f(x) + f(-x)}{2} \\
    &= \frac{2f(x)}{2} = f(x)
\end{align}
$$

Agora falta mostrar que $O$ é ímpar e que $E$ é par... De facto,

$$
    O(-x) = \frac{f(-x) - f(-(-x))}{2} = \frac{f(-x) - f(x)}{2} = -\frac{f(x) - f(-x)}{2} = -O(x)
$$

e

$$
    E(-x) = \frac{f(-x) + f(-(-x))}{2} = \frac{f(-x) + f(x)}{2} = \frac{f(x) + f(-x)}{2} = E(x)
$$

Provas por construção são as minhas preferidas e, sempre que consigo, escrevo as minhas provas assim; costumo achar muito mais gratificante mostrar como encontrar o objeto do meu teorema ou proposição, em vez de me limitar a provar que existe. Um outro exemplo interessante de prova por construção pode ser encontrado [nesta minha prova num tweet][tp-irrationals].

Vou dar ainda outro exemplo de uma prova por construção: vou mostrar que dados pares $\{(x_1, y_1), (x_2, y_2), \cdots, (x_{n+1}, y_{n+1})\}$ onde $x_i \neq x_j\ \forall i\neq j$, consigo encontrar um polinómio $p(x)$ de grau menor ou igual a $n$ tal que $p(x_i) = y_i\ \forall 1\leq i\leq n+1$. Para provar isto vou começar por provar algo mais simples: para o mesmo conjunto de pares, consigo encontrar um conjunto de polinómios $\{l_1, l_2,\cdots, l_{n+1}\}$ de graus menores ou iguais a $n$ e tais que

$$
    l_i(x_j) = 
    \begin{cases}
        1, i = j\\
        0\ \text{caso contrário}
    \end{cases}
$$

Se eu conseguir encontrar esses polinómios, então basta-me definir

$$
    p(x) = y_1l_1(x) + y_2l_2(x) + \cdots + y_{n+1}l_{n+1}(x)
$$

Vou provar que os polinómios existem encontrando uma fórmula explícita para cada um deles. Nota que $l_1$ é suposto ser $0$ em $x_2$. Assim concluo que $(x - x_2)$ deve estar, definitivamente, na fatorização de $l_1(x)$; pelo mesmo raciocínio, $(x - x_3), \cdots, (x - x_{n+1})$ também estarão. Vou definir

$$
    l_1(x) = (x-x_2)\times(x-x_3)\times\cdots\times(x-x_{n+1}) = \prod_{j\neq1} (x-x_j)
$$

Daqui segue que $l_1(x_j) = 0$ se $j \neq 1$. Mas, por enquanto,

$$
    l_1(x_1) = \prod_{j\neq 1} (x_1-x_j) \neq 1
$$

Isto pode ser resolvido facilmente se eu dividir $l_1$ por esse número! Assim

$$
    l_1(x) = \left(\prod_{j\neq1} (x - x_j)\right)\big/\left(\prod_{j\neq1}(x_1 - x_j)\right) = \prod_{j\neq1} \frac{x-x_j}{x_1-x_j}
$$

satisfaz a propriedade necessária de valer $1$ em $x_1$ e de valer $0$ em qualquer outro $x_i$ da lista.

De modo semelhante consigo construir os outros polinómios $l_i$, onde a expressão geral é

$$
    l_i(x) = \prod_{j\neq i}\frac{x-x_j}{x_i - x_j}
$$

e portanto mostrei que consigo construir os polinómios necessários. Porque cada $l_i$ é o produto de $n$ fatores, cada um de grau $1$, cada $l_i$ tem grau $n$. A combinação linear

$$
    p(x) = y_1l_1(x) + y_2l_2(x) + \cdots + y_{n+1}l_{n+1}(x)
$$

tem, portanto, grau menor ou igual a $n$.

Os dois métodos que se seguem são métodos indiretos de prova, no sentido em que provam uma determinada asserção mas não fornecem métodos para se encontrar o objeto em questão.


### Prova por contrapositivo

O primeiro método indireto de que vou falar usa-se quando queremos provar uma implicação e acabamos por provar o seu contrapositivo. Ou seja, este método usa-se quando queremos provar que um conjunto de condições $C$ implica um resultado $R$. Isto é, quando queremos provar que sempre que as condições de $C$ estão todas satisfeitas, então podemos observar o resultado $R$; em símbolos, $C \implies R$. O contrapositivo de $C \implies R$ é $\neg R \implies \neg C$. Por palavras, se o resultado $R$ não estiver a ser observado então de certeza que as condições $C$ não se verificaram.

Se pensares por um pouco, torna-se bastante intuitivo que $C \implies R$ e $\neg R \implies \neg C$ sejam a mesma coisa... Se a verificação de todas as condições dá $R$ ($C \implies R$) então é claro que se não obtivémos o resultado não podemos ter verificado as condições ($\neg R \implies \neg C$)! Porque se as condições se tivessem verificado, então teríamos obtido $R$... Mas não obtivémos!

Agora vou provar algo através do seu contrapositivo. Vou mostrar que se $s$ é a soma de dois números inteiros consecutivos, então $s$ é ímpar. Para provar o contrapositivo, preciso de mostrar que se $s$ é par, então não pode ser a soma de dois inteiros consecutivos. Isto é relativamente simples de provar mas serve o propósito educativo de mostrar como o método funciona:

Sejam $a$, $b$ dois inteiros com $a + b = s$. Porque $s$ é par, então há um número inteiro $k$ com $2k = s$ e portanto obtemos:

$$
    \begin{cases}
        a + b = 2k \iff b = 2k - a\\
        a - b = a - (2k - a) = 2a - 2k = 2(a - k)
    \end{cases}
$$

o que significa que a diferença entre $a$ e $b$ também é par. Mas se $a$, $b$ são inteiros consecutivos, teríamos $a - b = \pm 1$, o que é ímpar em qualquer um dos casos. Portanto não pode ser o caso que $a$ e $b$ são inteiros consecutivos.

Como segundo exemplo vou mostrar que se $A, B \subset \mathbb{Z}$ são subconjuntos finitos dos números inteiros e se $A \subset B$ então $\max A \leq \max B$. Para provar isto, vou mostrar que $\max A > \max B \implies A \not\subset B$. De facto, seja $a = \max A > \max B = b$ e mostre-se que $a \not \in B$. Ora, se $a \in B$, então $b \geq a$ porque $b = \max B$, mas $a > b$ e portanto $a \not \in B$, o que conclui a prova.


### Chegar a uma contradição

Quando queremos fazer uma prova por contradição, o que há a fazer é assumir a negação daquilo que queremos provar e construir um raciocínio que eventualmente nos leve a uma conclusão absurda!

Se o raciocínio não tiver falhas então a única explicação plausível para a conclusão absurda a que se chegou é que os pressupostos estavam errados.

A título de exemplo, vamos provar que se escolhermos $11$ inteiros de entre $\{1, 2, 3, \cdots, 30\}$ então há pelo menos dois deles (chamemos-lhes $x$ e $y$) que satisfazem $|x - y| \leq 2$. Para provar isto, vou começar por assumir que já escolhi $11$ inteiros e que nenhum par está suficientemente próximo; agora vou tentar chegar a uma contradição:

Vou ordenar os $11$ inteiros do menor para o maior e vou escrevê-los da seguinte forma:

$$
    a_0 < a_1 < a_2 < a_3 < a_4 < a_5 < a_6 < a_7 < a_8 < a_9 < a_{10}
$$

Agora substituo cada $a_i$ por $a_i - a_{i-1},\ \forall i &gt; 0$, o que me dá:

$$
    \begin{align}
        &a_0, a_1-a_0, a_2-a_1, a_3-a_2, a_4-a_3, a_5-a_4,\\
        &a_6-a_5, a_7-a_6, a_8-a_7, a_9-a_8, a_{10}-a_9
    \end{align}
$$

Ora, como $a_{10} = a_0 + \sum_{i=1}^{10}(a_i - a_{i-1})$ e $ \sum_{i=1}^{10} a_i - a_{i-1} \geq 30$, decorre que $a_{10} \geq a_0 + 30 \implies a_{10} \geq 31$ porque $a_0 \geq 1$, o que contraria o facto de $a_{10}$ ter sido escolhido de entre $\{1, 2, 3, \cdots, 30\}$; assim concluímos que o nosso erro está em quando suposémos que era possível fazer a escolha dos $11$ inteiros sem que nenhum par estivesse próximo.

Outro bom exemplo é esta prova de que $\sqrt{2}$ é irracional: vou supor que $\sqrt 2$ é racional e escrever $\sqrt2 = \frac{m}{n}$, onde $\frac{m}{n}$ é uma fração reduzida, i.e. $m$ e $n$ não têm divisores em comum. Note-se que

$$
    \sqrt{2} = \frac{m}{n} \iff \sqrt{2}^2 = \left(\frac{m}{n}\right)^2 \iff 2 = \frac{m^2}{n^2}
$$

Daqui, concluo que $2$ divide $m$ e portanto temos $\sqrt{2} = \frac{m}{n} = \frac{2m'}{n} \iff \frac{\sqrt{2}}{2} = \frac{m'}{n}$. Elevando os dois lados ao quadrado:

$$
    \left(\frac{\sqrt2}{2}\right)^2 = \left(\frac{m'}{n}\right)^2 \iff \frac{1}{2} = \frac{m'^2}{n^2}
$$

Daqui conclui-se que $2$ divide $n$, e portanto temos $\sqrt{2} = \frac{m}{n} = \frac{2m'}{2n'} = \frac{m'}{n'}$, o que contraria a hipótese de que $\sqrt 2 = \frac{m}{n}$ já estava simplificado, e que mostra que $\sqrt{2}$ não pode ser igual a uma fração.

Uma prova por contrapositivo e uma prova por contradição são muito parecidas, mas não são a mesma coisa. Na prova por contrapositivo pegamos na negação do resultado e mostramos que isso implica a negação das condições. Na prova por contradição pegamos nas condições _e_ na negação do resultado e tentamos chegar a uma conclusão que seja absurda.

Concluo assim o artigo sobre estes métodos de provas; qual é que é o teu preferido?

[tp-irrationals]: https://mathspp.com/blog/twitter-proofs/irrational-rationality
