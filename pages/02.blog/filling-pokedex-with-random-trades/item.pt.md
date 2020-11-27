---
metadata:
    description: Neste artigo vou falar de probabilidades e valores esperados, e vou usar os jogos de Pokémon como exemplo.
title: "Preencher a Pokédex - um exercício probabilístico"
---

Neste artigo vou tentar agradar a matemáticos e a "*Pokéfans*".
Juntos, vamos descobrir quanto tempo é que demoraríamos a completar
uma Pokédex se só pudéssemos fazer trocas aleatórias.

===

![Uma fotografia de uma troca aleatória a acontecer no Pokémon Home](thumbnail.jpg)

# Objetivo

O objetivo deste artigo é vermos como é que podemos calcular o tempo médio
que um certo evento aleatório demora a acontecer.
O exemplo que vamos usar prende-se com uma mecânica dos jogos de Pokémon,
que vou introduzir de forma breve para quem não conhece os jogos.

Mais especificamente, vamos determinar quantas trocas aleatórias são necessárias,
em média, para se concluir uma Pokédex completa.
Continua a ler para descobrires quanto tempo demora!

Vamos fazer as contas juntos, passo por passo, à medida que as explico e partilho
um par de truques que eu uso para as fazer.
Esta parte mais técnica ficou no fim do artigo, para não perturbar o fio condutor
do artigo.

Quando acabares de ler este artigo,
 - terás melhorado a tua capacidade de formular a solução teórica para este tipo
de problemas;
 - terás calculado os resultados, que involvem manipular séries infinitas;
 - terás aprendido alguns truques para lidar com estas séries.


# Os jogos de Pokémon e a Pokédex

Esta pequena secção tem por objetivo introduzir-te ao mundo do Pokémon.
Vou explicar apenas o suficiente para que entendas o problema que queremos resolver,
que explano na secção ["Formular o problema"](#formular-o-problema) a seguir.
Podes seguir imediatamente para essa secção se estiveres familiarizado com
os jogos de Pokémon.

Nos jogos de Pokémon existem umas criaturas (chamadas Pokémon, derivado de
"pocket monsters", que significa "monstros de bolso") que podes capturar e colecionar.

Aqui tens uma imagem com péssima qualidade com três desses Pokémon:

![Uma imagem com má qualidade com três Pokémon](_pokemons.jpg)

À data de escrita deste artigo, existem [898][wiki-pokemon-list] Pokémon diferentes.
Nos jogos existe uma coisa chamada *Pokédex*, que é uma espécie de catálogo de todos
os Pokémon que já encontrámos e capturámos.
Um dos objetivos do jogo - pelo menos para quem leva o jogo a sério - é completar
a Pokédex (i.e. encontrar todos os Pokémon).

Pokémon diferentes podem ser encontrados dentro do jogo,
mas também podem ser obtidos através
de trocas com amigos ou com qualquer pessoa na Internet.

Neste artigo queremos descobrir quantas trocas é que precisaríamos de fazer para
completar a Pokédex, se todas as trocas fossem *aleatórias*.
Isto é, se todas as trocas fossem tais que não tínhamos controlo **nenhum** sobre
os Pokémon que estamos a receber.


# Formular o problema

Queremos descobrir qual é o número médio de trocas aleatórias que precisamos
de fazer até conseguirmos ver os 898 Pokémon que existem
à data de escrita deste artigo.

Vamos assumir que começamos com um único Pokémon e que, numa troca aleatória,
podemos receber qualquer um dos 898 Pokémon com igual probabilidade.

(Esta suposição facilita-nos um pouco as contas mas é pouco provável que seja
verdadeira.
De facto, basta ver que no sistema "Wonder Trade" do Pokémon Home há Pokémon
que aparecem muito mais frequentemente que outros.)

## Abstrair-nos do contexto

Se ignorarmos todos os detalhes *"supérfluos"* (supérfluos do ponto de vista
matemático), então o que nós queremos saber é o número médio de experiências
aleatórias que temos de fazer até observarmos todos os 897 possíveis resultados
que ainda não observámos de um evento aleatório, assumindo que todos os resultados
são igualmente prováveis.

No parágrafo anterior, um resultado refere-se a "receber um Pokémon específico"
e já só precisamos de ver 897 resultados porque há um Pokémon que não precisamos
de receber: aquele com que começamos.


# Formular a solução

Quando tento resolver um problema de probabilidades como este eu costumo
começar por parti-lo em partes mais pequenas, partes essas com as quais é mais
fácil de lidar.
Depois, resolvo cada um dos problemas mais pequenos e então junto tudo para obter
a minha resposta final.

## Partir o problema

Para este problema em particular, considerar o número de trocas que temos de fazer
para conseguir ver todos os Pokémon parece um problema demasiado difícil de atacar.
Em vez de pensar nesse problema, podemos pensar nestes casos específicos:

 > Se eu tiver visto $10$ Pokémon diferentes, quanto tempo é que vou precisar
 > (em média) para ver um Pokémon novo através de trocas aleatórias?

 > Se eu tiver visto $253$ Pokémon diferentes, quanto tempo é que vou precisar
 > (em média) para ver um Pokémon novo através de trocas aleatórias?

 > Se eu tiver visto $890$ Pokémon diferentes, quanto tempo é que vou precisar
 > (em média) para ver um Pokémon novo através de trocas aleatórias?

e podemos também pensar na generalização dos casos específicos:

 > Se eu tiver visto $n$ Pokémon diferentes, quanto tempo é que vou precisar
 > (em média) para ver um Pokémon novo através de trocas aleatórias?

De facto, assim que virmos a pergunta enunciada aqui em cima e percebermos que é
a pergunta mais importante que temos de resolver podemos escrever a solução
do problema original!

A solução do problema original é

$$
\overset{\text{Tempo necessário}}{\underset{\text{Pokémon diferentes}}{\text{para ver 898}}} = \overset{\text{Tempo necessário}}{\text{para ver 2° Pok.}} + \overset{\text{Tempo necessário}}{\text{para ver 3° Pok.}} + \cdots + \overset{\text{Tempo necessário}}{\text{para ver 898° Pok.}}
$$

Agora só precisamos de saber como calcular cada termo do lado direito da igualdade
em cima.
E acreditem em mim quando vos digo que é muito mais simples calcular
isso do que resolver o problema inicial de uma assentada.

## Encontrar Pokémon novos

Vamos usar $T(n)$ para representar *"o tempo médio necessário para ver um Pokémon
novo, assumindo que já vimos $n$ Pokémon diferentes"*.
Com esta definição, e porque começamos com um Pokémon, a solução é uma soma:

$$
\sum_{n = 1}^{897} T(n) = T(1) + T(2) + T(3) + \cdots + T(897)
$$

Agora basta-nos calcular o valor de $T(n)$ para todos os valores de $n$ e somar tudo.
Mas como é que calculamos $T(n)$?

Bom, vamos supor que já vimos $n$ Pokémon e que acabámos de receber um Pokémon através
de uma troca aleatória.
Quão provável é esse Pokémon ser novo?
A probabilidade de já o termos visto é $\frac{n}{898}$ e portanto a probabilidade
de nunca o termos visto é $1 - \frac{n}{898} = \frac{898 - n}{898}$.

Portanto, a probabilidade de vermos um Pokémon novo depois de termos visto $n$
Pokémon diferentes é $\frac{898 - n}{898}$.
Claro que, se $n \neq 0$, isto é uma probabilidade menor que $1$ e portanto
pode acontecer recebermos um Pokémon repetido.
Se esse for o caso, temos de fazer mais trocas até conseguirmos ver um Pokémon
diferente.
Isto quer dizer que também vamos calcular $T(n)$ ao dividir este problema em problemas
ainda mais pequenos e mais simples.

## Partir o problema... de novo?

Nota que receber um Pokémon novo depois de ter visto $n$ Pokémon diferentes
pode acontecer de várias maneiras diferentes:

 - Recebemos um Pokémon novo à primeira.
 - Recebemos um Pokémon repetido e um novo à segunda.
 - Recebemos dois Pokémon repetidos e um novo à terceira.
 - ...

Se conseguirmos calcular a probabilidade de cada um dos cenários anteriores,
depois podemos usar a definição de *valor esperado* para calcular $T(n)$.

### Valor esperado

O *valor esperado* de alguma coisa é a soma (ou *série*, se for uma soma com um
número infinito de termos) do valor de cada resultado vezes a probabilidade
desse resultado específico acontecer.

Por exemplo, qual é o *valor esperado* de um lançamento de um dado?
Por outras palavras, qual é o valor médio do lançamento de um dado?
Bom, os resultados possíveis são $1, 2, 3, 4, 5, 6$ e cada um tem probabilidade
$\frac16$ de acontecer, logo o valor esperado do lançamento de um dado é

$$
\begin{gathered}
{\color{red} \frac16}\times 1 + {\color{red} \frac16}\times 2 + {\color{red} \frac16}\times 3 + {\color{red} \frac16}\times 4 + {\color{red} \frac16}\times 5 + {\color{red} \frac16}\times 6 = \\
= {\color{red} \frac16}\times\left(1 + 2 + 3 + 4 + 5 + 6 \right) = \\
= 3.5 ~.
\end{gathered}
$$

Vamos fazer uma conta semelhante para calcular $T(n)$.
Vamos escrever $p(n) = \frac{898 - n}{898}$, porque eu sou preguiçoso, e vamos notar
que:

 - Podemos precisar de $1$ troca para ver um Pokémon novo depois de termos visto
$n$ Pokémon diferentes e isso acontece com probabilidade $p(n)$.
 - Podemos precisar de $2$ trocas para ver um Pokémon novo depois de termos visto
$n$ Pokémon diferentes e isso acontece com probabilidade $(1 - p(n))p(n)$ -
a primeira troca traz um Pokémon repetido e a segunda um Pokémon novo.
 - Podemos precisar de $2$ trocas para ver um Pokémon novo depois de termos visto
$n$ Pokémon diferentes e isso acontece com probabilidade $(1 - p(n))^2p(n)$ -
as duas primeiras trocas trazem Pokémon repetidos e a terceira um Pokémon novo.
 - ...
 - Podemos precisar de $k$ trocas para ver um Pokémon novo depois de termos visto
$n$ Pokémon diferentes e isso acontece com probabilidade $(1 - p(n))^{k-1}p(n)$ -
as $k-1$ primeiras trocas trazem Pokémon repetidos e a $k$-ésima traz um Pokémon novo.
 - ...

## O tempo que demora a ver um Pokémon novo

Se pegarmos no raciocínio explanado em cima e escrevermos um somatório com todos
os possíveis números de trocas que podemos precisar para receber um Pokémon novo,
e se para além disso multiplicarmos cada número pela probabilidade de precisarmos
desse número exato de trocas, então obtemos

$$
T(n) = \sum_{k = 1}^\infty k \times (1 - p(n))^{k-1}p(n)
$$

que pode ter mau aspeto mas é a definição de valor esperado do evento "ver um Pokémon
novo depois de ver $n$ Pokémon diferentes".
Apesar de ter mau aspeto, o resultado é bastante simples.
Peço-te que tentes calcular o resultado deste somatório por ti mesmo.
É um exercício mesmo bom.
Os cálculos estão feitos passo a passo [no fim do artigo](#calculos-auxiliares).

O resultado do somatório é

$$
T(n) = \frac1{p(n)}
$$

## O tempo que demora a ver todos os Pokémon

Há um par de secções atrás vimos que o nosso problema era calcular

$$
\sum_{n = 1}^{897} T(n)
$$

e agora sabemos que $T(n) = \frac{1}{p(n)} = \frac{898}{n - 898}$,
portanto podemos juntar tudo e calcular

$$
\begin{aligned}
\sum_{n = 1}^{897} T(n) &= \sum_{n = 1}^{897} \frac{1}{p(n)} \\
&= \sum_{n = 1}^{897} \frac{898}{n - 898} \\
&= 898\sum_{n = 1}^{897} \frac{1}{n - 898} \\
&= 898\sum_{n = 1}^{897} \frac{1}{n} ~ .
\end{aligned}
$$

A soma $\frac11 + \frac12 + \cdots + \frac1{897}$ não tem uma fórmula fechada
que possamos usar, mas como é uma soma com um número finito de parcelas,
podemos sempre usar uma calculadora.
Eu [perguntei ao WolframAlpha][wa-result] quanto é que dava e fiquei a saber
que o resultado é $\approx 7.377$,
logo o nosso resultado final é aproximadamente

$$
898 \times 7.377 = 6624.546 ~ .
$$

E aqui está!


# Fazer 6624 trocas

Em média, serão precisas aproximadamente $6624$ trocas aleatórias para que possas
ver todos os $898$ Pokémon diferentes que existem!


# Cálculos auxiliares

Agora vou mostrar como é que eu fiz os cálculos do artigo que me levaram ao
resultado final.
Também vou partilhar um par de truques que eu uso quando faço este estilo de contas,
porque eu sou péssimo a memorizar fórmulas.

Sente-te à vontade para passares esta secção à frente até ao fim do artigo, se tudo
o que tu queres agora é mostrar que gostaste do artigo.
Podes fazê-lo ao deixar uma reação com um emoji e/ou ao comentares o artigo.

Se quiseres ler esta secção e aprender um par de truques, aqui vamos nós.

Queremos calcular o valor da série

$$
\sum_{k = 1}^\infty k(1 - p)^{k-1}p ~ .
$$

Esta série é igual a $\frac1p$ desde que $|1 - p| < 1$, mas eu nunca sei isso de cor.
Em vez de saber o resultado de cor, o que eu faço é calcular o valor desta série
de cada vez que ela me aparece à frente.

Caso nunca tenhas feito isto, não quero que sejas apanhado desprevenido.
Aqui tens uma visão geral dos passos principais que vamos dar.
Não te preocupes se não for óbvio o que é que cada passo quer dizer ou como é que
ele se relaciona com os outros, só preciso que tenhas uma ideia geral do que queremos
fazer:

 - vamos reescrever a série original;
 - vamos olhar para cada termo como se fosse a derivada de um outro termo de outra
série;
 - vamos calcular a segunda série com uma fórmula fechada;
 - vamos calcular a derivada da fórmula para obter uma fórmula para a série original.

## Obter a fórmula

Vou tentar ser cuidadoso com os passos intermédios mas se quiseres ficar apenas
com a ideia geral de como isto se faz, podes sempre ler a
[versão para pessoas com pressa](#versao-para-pessoas-com-pressa).

Para obter a fórmula final, começamos por olhar para

$$
\sum_{k = 1}^\infty {\color{red} k(1-p)^{k-1}}p ~.
$$

Vamos procurar uma função $g_k(p)$ cuja derivada seja vagamente semelhante ao que
está a vermelho na fórmula em cima.
Se escolhermos $g_k(p) = (1-p)^k$ vemos que

$$
g_k'(p) = -k(1 - p)^{k-1} ~ ,
$$

portanto vamos reescrever a série para que possamos inserir as funções $g_k'(p)$:

$$
\sum_{k = 1}^\infty k(1-p)^{k-1}p = -p \sum_{k = 1}^\infty -k(1-p)^{k-1} = -p \sum_{k = 1}^\infty g_k'(p) ~ .
$$

Mas porque é que isto é relevante?
Heis o que vamos fazer:
vamos escrever a série com as funções $g_k(p)$ e tentar relacionar essa série com a
série das funções $g_k'(p)$.
Se tudo correr bem, obtemos uma fórmula fechada para a série com as funções $g_k(p)$
e ao derivar essa fórmula obtemos também uma fórmula para a série das funções $g_k'(p)$.
Para conseguirmos isto, começamos por escrever o seguinte:

$$
S_K(p) = \sum_{k = 1}^K g_k(p) = \sum_{k = 1}^K (1 - p)^k ~ .
$$

Muitos hão de saber a fórmula para o resultado, mas eu tenho uma memória mesmo má
e não a sei.
Mas sei descobri-la:

$$
S_{K+1}(p) - S_K(p) = (1 - p)^{K+1}
$$

e também é verdade que

$$
\begin{aligned}
S_{K+1}(p) &= \sum_{k = 1}^{K+1} (1 - p)^k \\
&= (1 - p) + (1-p)\sum_{k = 1}^K (1 - p)^k \\
&= (1 - p) + (1-p)S_K(p) ~ .
\end{aligned}
$$

Se juntarmos as duas expressões, obtemos

$$
\begin{aligned}
S_{K+1}(p) - S_K(p) = (1 - p)^{K+1} &\iff (1 - p) + (1-p)S_K(p) - S_K(p) = (1 - p)^{K+1} \\
&\iff (1 - p - 1)S_K(p) = (1 - p)^{K+1} - (1 - p) \\
&\iff S_K(p) = -\frac{(1 - p)^{K+1} - (1 - p)}{p} ~ .
\end{aligned}
$$

Outra forma de chegar a este resultado é a seguinte:

$$
\begin{gather}
\begin{aligned}
(1 - p)S_K(p) &= &&(1 - p)^2 + \cdots + (1 - p)^K + (1 - p)^{K + 1} ~ ,\\
S_K(p) &= (1 - p) +&&(1 - p)^2 + \cdots + (1 - p)^K
\end{aligned} \implies \\
\\
\begin{aligned}
(1 - p)S_K(p) - S_k(p) &= (1 - p)^{K+1} - (1 - p) \iff \\
S_K(p) &= -\frac{(1 - p)^{K+1} - (1 - p)}{p} ~ .
\end{aligned}
\end{gather}
$$

Aplicando a regra do quociente, e depois de arrumarmos os vários termos, temos

$$
\frac{Kp(1-p)^K + (1 - p)^K - 1}{p^2} = S_K'(p) = \sum_{k = 1}^K -k(1 - p)^{k - 1} ~ ,
$$

o que significa que descobrir a fórmula para $S_K(p)$ nos deu a fórmula para

$$
S_K'(p) = \sum_{k = 1}^K -k(1 - p)^{k - 1} ~ .
$$

A nossa série original aparece se tomarmos o limite $K \to \infty$,
portanto basta-nos garantir que podemos tomar esse limite $K \to \infty$ na fórmula
também.

Para nosso agrado, temos que

$$
\lim_{K \to \infty} \frac{Kp(1-p)^K + (1 - p)^K - 1}{p^2} = -\frac{1}{p^2}
$$

se $|(1 - p)| < 1$, porque nesse caso os termos $(1 - p)^K$ estão a tornar-se
infinitamente pequenos, de tal modo que tanto $Kp(1-p)^K$ e $(1 - p)^K$ tendem
para $0$ quando $K \to \infty$.

Mas, nesse caso, conseguimos mostrar que

$$
S(p) = \lim_{K \to \infty} S_K'(p) = \sum_{k = 1}^\infty - k (1-p)^{k - 1} = -\frac{1}{p^2} ~ .
$$

Se olharmos de novo para a nossa série original, tínhamos

$$
\sum_{k = 1}^\infty kp(1 - p)^{k - 1} = -p\sum_{k = 1}^\infty -k(1-p)^{k-1} = -pS(p) = \frac1p ~ .
$$

E assim se calcula esta série.

Nesta secção eu tentei ter algum cuidado com as subtilezas envolvidas em derivar
uma série, mas às vezes não há tempo para esses cuidados e já sabemos, de qualquer
das maneiras, que as contas vão funcionar.
Quando se dá esse caso, podes querer saltar alguns passos (ao teu risco!):

## Versão para pessoas com pressa

Se saltarmos alguns passos intermédios que, em geral, não vão dar problemas,
podemos fazer o seguinte:

 1. Começar por escrever

$$
\sum_{k = 1}^\infty k(1-p)^{k-1}p = -p \sum_{k = 1}^\infty -k(1-p)^{k-1} = -p \sum_{k = 1}^\infty g_k'(p) ~ .
$$

 2. "Integrar" as funções $g_k'(p)$ e escrever

$$
S(p) = \sum_{k = 1}^\infty g_k(p) = \sum_{k = 1}^\infty (1 - p)^k ~ .
$$

 3. Calcular

$$
S(p) = \frac{1}{p} ~ .
$$

 4. Derivar

$$
S'(p) = -\frac{1}{p^2} = \sum_{k = 1}^\infty g_k'(p) ~ .
$$

 5. Inserir na série original:

$$
\sum_{k = 1}^\infty k(1-p)^{k-1}p = -p \sum_{k = 1}^\infty - k(1-p)^{k-1} = -p \sum_{k = 1}^\infty g_k'(p) = -pS'(p) = \frac1p ~ .
$$

É ao manipular o que deixamos de fora das funções $g_k'(p)$ que podemos usar esta
mesma técnica para calcular séries com combinações diferentes de expoente e constantes
multiplicativas.
Também pode dar-se o caso de ser necessário usar este truque repetidamente,
por exemplo para calcular

$$
\sum_{k = 1}^\infty k^2(1 - p)^k ~ .
$$

Para este caso eu faria a divisão

$$
\sum_{k = 1}^\infty k^2(1 - p)^k = (1 - p)^2\sum_{k = 1}^\infty k(k-1)(1-p)^{k-2} + (1 - p)\sum_{k = 1}^\infty k(1 - p)^{k - 1} ~ .
$$

A série mais à direita é basicamente a que acabámos de calcular.
A da esquerda resolvia-se se notássemos que o termo $k(k-1)(1 - p)^{k-2}$ é a
segunda derivada da função $g_k(p) = (1 - p)^k$, o que quer dizer que faríamos
o passo 4. duas vezes.

[wiki-pokemon-list]: https://en.wikipedia.org/wiki/List_of_Pok%C3%A9mon
[wa-result]: https://www.wolframalpha.com/input/?i=1+%2B+1%2F2+%2B+1%2F3+%2B+...+%2B+1%2F897
