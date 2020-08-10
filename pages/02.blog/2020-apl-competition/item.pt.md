---
metadata:
    description: Neste artigo partilho as minhas soluções para a competição de programação em APL da Dyalog.
title: O que eu achei da competição de programação em APL de 2020.
---

A edição de 2020 da competição de programação em APL foi difícil! Neste artigo partilho algumas reflexões e as minhas soluções.

===

![An edited screenshot of part of my submitted code](screenshot.png)

A [competição em Dyalog APL][apl-comp] é uma competição anual promovida pela [Dyalog], para que estudantes de todo o mundo possam pôr à prova os seus conhecimentos de APL resolvendo dois conjuntos de problemas.

Na Fase $1$ da competição há que resolver $10$ problemas mais simples e cujas soluções cabem, geralmente, numa única linha de APL. A Fase $1$ é resolvida diretamente no browser. Quando submetemos uma solução um sistema verifica automaticamente se a nossa solução falhou, se passou os testes básicos todos ou se falhou em algum dos testes que, apesar de básico, verificava se a nossa submissão era geral o suficiente. Se só passarmos os testes mais fáceis recebemos um troféu de prata e se passarmos os testes todos, recebemos um troféu de ouro. Ainda assim, receber um troféu de ouro não significa que a nossa solução esteja $100\%$ correta.

A Fase $2$ da competição é composta por um número variável de problemas, onde a dificuldade também varia; alguns são muito fáceis e outros são muito difíceis. Os enunciados dos vários anos podem ser encontrados [aqui][prob-sets].

Eu comecei a aprender APL por volta de março de 2020 com o único propósito de competir na edição de 2020 da competição (ok, e para me divertir e espairecer um bocado também [isto tudo aconteceu durante o surto de coronavirus em Portugal]). As soluções tinham de ser submetidas até dia $31$ de julho (há uma semana atrás) e tenho o prazer de te dizer - não que tu queiras saber - que submeti soluções para todos os problemas e sinto que fiz um bom trabalho. Por outro lado, sou um novato com APL e não faço a _menor ideia_ do nível dos alunos que, de todo o mundo, concorreram na competição, por isso não ficaria surpreendido se a minha prestação global não fosse assim tão genial.

Independentemente disso, descobri que programar em APL é uma atividade muito interessante por vários motivos, desde eu sentir que é uma linguagem que se presta bem a programar coisas relacionadas com matemática ao facto de usar símbolos bem engraçados (e.g. `⍋` e `⍳`). Outra coisa que fez com que eu gostasse tanto de APL foi que a descobri através de um site de [codegolf], o que me levou à sala de chat do ["Pomar de APL"][the APL Orchard], onde algumas pessoas amigáveis me ensinaram virtualmente tudo o que sei sobre APL e programação vetorizada.

No próximo ano já não vou poder competir como estudante mas estou ansioso por poder voltar a competir!

### As minhas soluções da Fase 1

Tal como disse no início, a Fase $1$ é suposto ser mais fácil e estas foram as minhas submissões:

<script src="https://gist.github.com/RojerGS/9ef6cd01f0f521d2b685425d69a85b0d.js"></script>

Consegui obter troféus dourados nas minhas $10$ submissões mas, ainda assim, pelo menos uma delas não está completamente correta. De facto, o enunciado do décimo problema diz claramente que a minha submissão devia funcionar como `{⎕←⍵}¨`, que aplicado a `'ab'` dá

```apl
      {⎕←⍵}¨ 'ab'
a
b
```

ao passo que a minha solução dá

```apl
      {↑⊃,/↓∘⍕¨⍵} 'ab'
ab
```

portanto há este pequeno pormenor... E na verdade também não acho que as minhas soluções `P4` e `P9` sejam incríveis; por outro lado, gosto bastante do modo como defini `P2` e `P3`.

### As minhas soluções da Fase 2

A Fase $2$ foi bem mais difícil, com alguns dos problemas a requererem que eu pensasse bastante e fizesse várias experiências. Ainda assim, algumas das tarefas eram bastante fáceis, como a solução `pv` (Tarefa $2$, problema $5$) e a solução `CheckDigit` (Tarefa $1$, problema $7$). No fim de contas, resolver os nove problemas foi uma bela experiência.

Vou agora partilhar as minhas soluções para os nove problemas e tecer pequenos comentários quando achar pertinente.

#### Problema 1

<script src="https://gist.github.com/RojerGS/6f8912674c5285a3d3dd98ad21680a76.js"></script>

A primeira solução que escrevi para este problema só funcionava para argumentos com comprimentos $3$, $5$ ou $7$ (tal como o enunciado garante) e eu também estava a ordenar explicitamente o vetor argumento com `⍵[⍋⍵]` mas depois decidi que não o queria fazer; nenhuma das duas coisas. Esta foi a solução final que desenvolvi.

#### Problema 2

<script src="https://gist.github.com/RojerGS/393a63a1af1b21c7edbd98491d5b3c70.js"></script>

No problema $2$ achei que ia conseguir escrever o caso `⍺ < 0` à custa do caso `⍺ > 0`... E durante algum tempo achei que o tinha conseguido fazer, exceto que depois reparei que o meu código falhava um dos testes e portanto tive de escrever o caso `⍺ < 0` explicitamente.

#### Problema 3

<script src="https://gist.github.com/RojerGS/23c6da18edb0d66b9624a98f120e5225.js"></script>

Este problema foi standard e as expressões regulares fizeram basicamente tudo por mim.

#### Problema 4

<script src="https://gist.github.com/RojerGS/aa060988925754356166d76904823c7b.js"></script>

O problema $4$ era composto por duas tarefas que vieram do website [Rosalind], um site com problemas de bioinformática. Ainda assim, as duas tarefas não estão relacionadas (pelo menos, não de maneira óbvia) e a segunda tarefa não passa de um simples problema de matemática (cuja pertinência em bioinformática me ultrapassa).

Na primeira tarefa esforcei-me ao máximo por fazer tudo de forma vetorizada e sem usar o operador `¨`, que para mim parece-se com um ciclo `for elem in iterable` em Python (que é um ciclo que eu uso a toda a hora). Ainda assim acabei por ter de usar `⍤0 1` que "sabe" a `¨`, mas como estava satisfeito com a minha solução, deixei o `⍤` sossegado.

A segunda tarefa era _mesmo_ fácil e só tive de implementar o método de elevar ao quadrado sucessivamente (não sei como é que este algoritmo se chama em português) para implementar a operação `PowerMod` de forma eficiente (i.e. calcular $b^n \mod m$ de forma eficiente). Talvez parte do desafio estivesse em compreender que um conjunto de $n$ elementos tem $2^n$ subconjuntos mas como eu estudei isto durante a minha licenciatura não tive de gastar muito tempo nessa parte. Na verdade, provar isto é tão simples que até podem encontrar uma [prova num tweet][tp-sets] deste facto! Para os que não conhecem o algoritmo, elevar ao quadrado sucessivamente permite que se calcule $b^n$ de forma muito eficiente, quando $n$ é grande. Suponhamos que queremos calcular $2^{857}$:

Basta notar que

\[
    \begin{align}
    2^{857} &= 2^{1\times 1 + 0\times 2 + 0\times 4 + 1\times 8+ 1\times 16 + 0\times 32 + 1\times 64 + 0\times 128 + 1\times 256 + 1\times 512} \\
    &= 2^{1+8+16+64+256+512} \\
    &=  2^{1}\times2^{8}\times2^{16}\times2^{64}\times2^{256}\times 2^{512}
    \end{align}
\]

e por causa da relação de recorrência

\[
    2^{(2^{k+1})} = \left(2^{(2^k)} \right)^2
\]

basta-nos calcular as primeiros $10$ potências, que são sempre o quadrado da última potência que acabei de calcular (daí o nome "elevar ao quadrado sucessivamente"...). Escrever este algoritmo em APL foi um desafio engraçado porque não consegui evitar a utilização do operador `⍣` para calcular as potências... E agora sinto-me parvo, abri _agora mesmo_ o interpretador enquanto escrevia isto e reparei que podia ter escrito `*⍨\`, ou talvez `{⍺←1 ⋄ m|⍺*⍵}\` para manter a operação de módulo entre cada cálculo auxiliar... Ora bolas, esperemos que isto não me prejudique muito!

! **Edicão** (10 de Agosto de 2020):
! Usar `⍣` poupa-me algumas operações porque `f\v` aplica `f` um número de vezes que é $O(n^2)$, se $n$ for o comprimento do vetor `v`, ao passo que `⍣` é linear em `v`. Não posso jurar ter pensado nisto mas tenho a vaga ideia de o ter feito. Por outro lado, [esta discussão no Reddit][reddit-post] mostrou-me que se eu tivesse optado pelo "scan" podia ter usado `m∘|⍤*⍨\` em vez de `{⍺←1 ⋄ m|⍺*⍵}\`.

#### Problema 5

<script src="https://gist.github.com/RojerGS/0ffe48f804f4f46c215c50a3d7fe4e44.js"></script>

O problema $5$ foi _super_ divertido porque tinha imenso a ver com matemática. Resolver a primeira tarefa de forma vetorizada foi mesmo um bom desafio.

Para resolver esta tarefa bastou-me escrever explicitamente a relação de recorrência para ver como é que ela funcionava, o que me deu (assumindo `⎕IO←0`):

\[
    \begin{cases}
    r_1 = \alpha_0 \\
    r_2 = \alpha_1 + \alpha_0\omega_1 \\
    r_3 = \alpha_2 + \alpha_1\omega_2 + \alpha_0\omega_2\omega_1 \\
    r_4 = \alpha_3 + \alpha_2\omega_3 + \alpha_1\omega_3\omega_2 + \alpha_0\omega_3\omega_2\omega_1 \\
    \cdots
    \end{cases}
\]

e portanto tornou-se claro que eu ia ter de calcular as taxas cumulativas, multiplicando omegas consecutivos, e que depois teria de multiplicar cada um desses produtos pelo depósito/levantamento respetivo, somando tudo no fim.

Decidi escrever estes resultados parciais em matrizes, onde cada linha corresponde a um $r_i$:

\[
    rt = \begin{bmatrix}
    0 & 0 & 0 & 0 & \cdots & 0 \\
    \omega_1 & 0 & 0 & 0 & \cdots & 0 \\
    \omega_2 \omega_1 & \omega_2 & 0 & 0 & \cdots & 0 \\
    \omega_3 \omega_2 \omega_1 & \omega_3 \omega_2 & \omega_3 & 0 & \cdots & 0 \\
    & & \cdots & & &
    \end{bmatrix}
\]

e

\[
    a = \begin{bmatrix}
    \alpha_0 & 0 & 0 & 0 & \cdots & 0 \\
    \alpha_0 & \alpha_1 & 0 & 0 & \cdots & 0 \\
    \alpha_0 & \alpha_1 & \alpha_2 & 0 & \cdots & 0 \\
    \alpha_0 & \alpha_1 & \alpha_2 & \alpha_3 & \cdots & 0 \\
    &  & \cdots & & \ddots & \alpha_n
    \end{bmatrix}
\]

Depois de construídas as matrizes basta-me multiplicá-las componente por componente e somar ao longo das linhas.

A segunda tarefa era substancialmente mais simples e acho que a resolvi bem com um comboio elegante (acho eu que é elegante).

#### Problema 6

<script src="https://gist.github.com/RojerGS/ddc89dbebab4fd7a812ea83f517c6042.js"></script>

Não gostei muito do problema $6$ porque as expressões regulares voltaram a fazer tudo por mim; o outro bocadinho do problema que podia ter sido engraçado também nos foi oferecido no enunciado. Tentei aproveitar o problema ao máximo evitando usar a função `⎕nl` (recomendada pelo enunciado) e acabei por escrever algo que se parece com um bloco `try ... except ...` em Python. No fim acabei por usar a função `⎕nl` porque assim a solução ficava mais ajustada ao estilo de APL.

#### Problema 7

<script src="https://gist.github.com/RojerGS/955db89c6ea6ddf96825fc78f3dce9a9.js"></script>

O problema $7$ era um problema interessante com três tarefas; a primeira tarefa, escrever a função `CheckDigit`, foi praticamente oferecida. No entanto, a tarefa $3$ foi um bom desafio.

Por algum motivo, eu acho elegante quando uma dada função tem um comportamento que ramifica (consoante os argumentos) e eu consigo definir algumas dessas ramificações à custa das outras, que é conseguido através de chamadas recursivas da função com argumentos ligeiramente modificados. Um exemplo (um pouco parvo) é o seguinte: quero definir uma função `f` que some dois números se o da direita for positivo ou $0$ e que os subtraia caso contrário. Para atingir este fim, eu poderia definir

```apl
f ← {
    ⍵<0: ⍺∇-⍵
    ⍺ + ⍵
}
```

Como a função `ReadUPC` deve aceitar códigos de barras lidos da esquerda para a direita e da direita para a esquerda, gastei bastante tempo a tentar encontrar uma maneira elegante para a função se chamar recursivamente se o código de barras tivesse sido lido da direita para a esquerda. No fim, por causa de toda a validação de argumentos que estava a ser feita, achei que ficaria mais simples e mais bonito se eu me limitasse a usar alguma aritmética (multiplicando por $1$ e por $0$ estrategicamente) para distinguir as duas direções em que o argumento podia ser fornecido.

#### Problema 8

<script src="https://gist.github.com/RojerGS/99bbab8cd5728858608fd20502d59aba.js"></script>

A razão pela qual achei este problema fácil é mais ou menos a mesma razão pela qual achei a tarefa `sset` fácil: ter estudado coisas semelhantes no curso. _No entanto_, não faço ideia se o que fiz corresponde aos padrões de uma boa solução. O enunciado do problema diz, mais ou menos,

The reason for which I found this problem easy was the same reason that led me to solve the `sset` with ease, because I have studied related topics in my maths degree. _However_, I have no idea if I did what was expected of a good solution. The problem statements says, and I quote,

 > "Entender as nuances deste problema é o mais importante para se poder implementar um bom algoritmo."
 
Eu fiz umas pesquisas sobre literatura relacionada com problemas de subsomas... E não encontrei nada que parecesse indicado para este problema.

O que eu quero dizer é que a minha solução funciona por força bruta... Mas é mesmo elegante (ou pelo menos, eu acho que é) no sentido em que é puramente vetorizada; e porque o enunciado garante que o argumento é relativamente pequeno, a solução por força bruta acaba por ser bastante rápida... Resumindo, eu estou bastante satisfeito com esta solução _mas_ o júri pode não ficar!

#### Problema 9

Este foi, de longe, o meu problema preferido da competição, até implementei duas soluções para este problema. Comecei com uma solução recursiva porque eu queria ter a certeza que resolvia todos os problemas. Depois de ter essa solução a funcionar decidi responder ao desafio do júri, que escreveu no enunciado:

 > "Este é o problema mais difícil da competição e provavelmente tem de ser resolvido de forma iterativa ou recursiva. (Se encontrares uma solução vetorizada e elegante, vamos ficar muito impressionados!)"

##### Solução recursiva

<script src="https://gist.github.com/RojerGS/8845dc6a7191bc66cb9d2733e44261d5.js"></script>

A minha solução recursiva começou por ser bastante maior do que é agora, mas com o passar do tempo fui pensando melhor e fui concluindo que havia bastantes partes que não eram necessárias. A ideia chave é transformar a matriz de caracteres numa estrutura inspirada em árvores binárias e na estrutura que se encontra no [workspace sobre brainf*ck][bf].

Eu transformo um esquema como

```txt
           ┌───────┴───────┐     
           │           ┌───┴───┐ 
           │           │       A 
       ┌───┴───────┐   │         
       │           B   │         
       │  ┌────────────┴───────┐ 
       │  C                    D 
 ┌─────┴───────┐                 
 E             F                 
```

em algo com esta forma:

```apl
┌───────────────┬─┬─┬────────────────┐
│┌───────┬─┬─┬─┐│1│1│┌────────┬─┬─┬─┐│
││E 3 4 F│1│2│B││ │ ││C 13 8 D│1│1│A││
│└───────┴─┴─┴─┘│ │ │└────────┴─┴─┴─┘│
└───────────────┴─┴─┴────────────────┘
```

Depois basta-me atravessar a estrutura e calcular tudo o que for preciso, desde o peso total da estrutura, ao conjunto de letras usado pela estrutura, ao peso de cada extremidade. Exceto que não preciso de fazer nada disso. A solução podia ser condensada ainda mais e podia fazer tudo numa só passagem pela estrutura, evitando construir a representação intermédia, mas achei mais elegante manter este passo intermédio, na suposição de que estamos num cenário em que quereríamos aplicar mais algoritmos à estrutura (e que portanto dava jeito reutilizar a representação intermédia).

##### Solução vetorizada

Quando tudo o resto estava concluído decidi resolver este problema de forma vetorizada. Eu sabia que era possível, só tinha de descobrir como o fazer. Posso dizer que houve uma determinada semana em que fiz duas caminhadas de $2$h e a maior parte do tempo em que caminhei ia pensando em como pôr isto a funcionar.

No fim, fui capaz de o fazer e estou bastante satisfeito com o resultado final; os comentários no código explicam como é que esta solução funciona:

<script src="https://gist.github.com/RojerGS/7f09c6314c3c6bdfab34e4594c728fc6.js"></script>

Ainda fiz uma pequena alteração às duas soluções, generalizando o problema para o caso em que as extremidades não são necessariamente letras maiúsculas; nas minhas soluções, as extremidades são qualquer caracter que não seja um espaço ou algum de `┐┴┌│─`, que são caracteres usados para especificar a estrutura.

Também competiste? Partilha as tuas opiniões e as tuas soluções na secção de comentários em baixo!

[apl-comp]: https://dyalogaplcompetition.com
[Dyalog]: https://dyalog.com
[prob-sets]: https://www.dyalog.com/student-competition.htm
[codegolf]: https://codegolf.stackexchange.com/users/75323/rgs
[The APL Orchard]: https://chat.stackexchange.com/rooms/52405/the-apl-orchard
[Rosalind]: http://rosalind.info
[bf]: https://dfns.dyalog.com/n_bf.htm
[tp-sets]: https://mathspp.com/blog/twitter-proofs/subsets-of-a-set
[reddit-post]: https://www.reddit.com/r/apljk/comments/i6gqxs/my_two_cents_on_the_2020_apl_problem_solving/g0xlarf?utm_source=share&utm_medium=web2x
