---
title: A aposta dos aniversários
---

Quando eu estava no secundário, tinha um colega que fazia anos no mesmo dia que eu.
Que grande coincidência, certo? Certo..?

===

![Um bolo de aniversário com umas velas acesas](birthday-cake.jpg)

O objetivo deste artigo é mostrar um resultado que eu considero bastante contra-intuitivo e que pode ser uma boa desculpa para se fazer uma pequena aposta com um grupo de amigos. Durante o artigo, sempre que eu falar de _aniversário_, interessa-me o dia e o mês; o ano não importa.

Qual é que é a probabilidade de eu e o meu melhor amigo fazermos anos no mesmo dia e mês? Mesmo sem fazermos as contas, é fácil de adivinhar que é muito mais provável nós termos aniversários _diferentes_ do que termos aniversários _iguais_. Assumindo que é igualmente provável uma pessoa nascer em qualquer um dos $366$ dias do ano, a probabilidade de eu e o meu melhor amigo termos o mesmo aniversário é só $\frac{1}{366} \approx 0.27\%$ e a probabilidade de termos aniversários diferentes é $\frac{365}{366} \approx 99.73\%$.

!!! Posto isto, que tamanho é que um grupo precisa de ter para que duas pessoas partilharem o dia de aniversário seja mais provável do que todos terem aniversários diferentes?

Será que precisamos de metade de 366, 183 pessoas? Será que precisamos de 100? 50? 10?

A resposta é $23$ pessoas! Se tivermos um grupo de $23$ pessoas, a probabilidade de ninguém partilhar o aniversário é aproximadamente $49.37\%$! Isto quer dizer que, num grupo de $23$ pessoas, há aproximadamente $\approx 50.63\%$ probabilidade de duas pessoas partilharem um aniversário.

Este resultado pode parecer bastante contra-intuitivo porque $23$ pessoas só poderiam ter $23$ aniversários diferentes, e $23$ dias nos $366$ dias do ano são apenas $6.3\%$ de todos os dias disponíveis. No entanto, essas $23$ pessoas que só podiam cobrir $6.3\%$ de todos os dias do ano, são suficientes para fazer com que seja provável haver um aniversário repetido. Mas porque é que isto importa..?

Em qualquer grupo de $23$ pessoas ou mais, podemos apostar que existirão duas pessoas com um aniversário partilhado. Fazendo isto vezes suficientes, temos lucro! Tal e qual como um casino: vamos perder algumas vezes e ganhar algumas vezes, mas a matemática dita que, a longo prazo, o jogo esteja a nosso favor.

 - Num grupo de $23$, a probabilidade de ganharmos está acima de $50\%$;
 - Num grupo de $27$, a probabilidade de ganharmos está acima de $60\%$;
 - Num grupo de $30$, a probabilidade de ganharmos está acima de $70\%$;
 - Num grupo de $35$, a probabilidade de ganharmos está acima de $80\%$;
 - Num grupo de $41$, a probabilidade de ganharmos está acima de $90\%$.

Como é que podemos calcular estas probabilidades? A maneira como eu gosto de pensar nisto é simples: pegamos nas $n$ pessoas que temos e fazemos uma fila com elas. O que vamos fazer agora é comparar o aniversário de uma pessoa com os aniversários de todas as pessoas que estão à frente dessa pessoa. Começamos no início da fila, obviamente. Pegando na segunda pessoa, qual é a probabilidade dessa pessoa ter um aniversário diferente da primeira pessoa na fila? $\frac{365}{366}$. E qual é a probabilidade da terceira pessoa ter aniversários diferentes da primeira e segunda pessoas, se essas tiverem aniversários diferentes? É $\frac{364}{366}$, logo qual é a probabilidade das três primeiras pessoas terem aniversários diferentes? É $\frac{365}{366} \times \frac{364}{366}$, a probabilidade das duas primeiras pessoas terem aniversários diferentes vezes a probabilidade da terceira pessoa ter aniversários diferentes das duas primeiras, se essas duas primeiras tiverem aniversários diferentes.

Podemos continuar com este racioncínio: se as três primeiras pessoas tiverem aniversários diferentes, então a probabilidade da quarta pessoa ter um aniversário distinto das outras três é $\frac{363}{366}$, portanto a probabilidade das primeiras quatro pessoas terem aniversários diferentes é $\frac{365}{366}\times\frac{364}{366}\times\frac{363}{366}$.

A fórmula para o caso geral, que dá a probabilidade de $n$ pessoas terem aniversários distintos é

\\[ \prod_{i=1}^{n-1} \frac{366-i}{366} \\]

<div>
<script>
var compute = function() {
    var n = parseInt(document.getElementById("n").value);
    var result = 1;
    for (var i = 1; i < n; ++i) {
        result *= (366-i)/(366);
    }
    result = 1 - result;
    document.getElementById("result").innerHTML = "Num grupo de " + n + " pessoas, há aproximadamente uma probabilidade de " + Math.round(result*10000)/100 + "% de duas pessoas partilharem um aniversário.";
}
</script>
<input type="number" id="n" placeholder="tamanho do grupo"></input>
<button onclick='compute()'>Calcular a probabilidade</button>
<br />
<p id="result"></p>
</div>

A caixa de texto aqui em cima permite-nos calcular a probabilidade de haver aniversários partilhados num grupo com um certo número de pessoas. Escrevendo $31$ e carregando no botão consigo concluir que na minha turma de $31$ pessoas havia uma probabilidade de $72.95\%$ de duas pessoas partilharem um aniversário... No secundário, acabei por ser eu a partilhar o meu dia com o Gustavo!

Deixem os vossos pensamentos nos comentários! Isto também já vos aconteceu? Talvez haja alguém na vossa turma, no vosso escritório, que faça anos no mesmo dia que vocês..?