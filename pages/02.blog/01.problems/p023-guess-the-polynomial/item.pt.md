---
metadata:
    description: Neste problema vais ter de derrotar o computador num jogo.
title: 'Problema #023 - adivinha o polinómio'
---

Neste problema vais ter de encontrar uma estratégia para derrotares o computador num jogo para adivinhares um polinómio secreto.

===

<script>
    var max_degree = 3;
    var max_coef = 3;
    var poly_times = 0;
    var evaluated_at = [];

    // Generate a random integer between a and b, inclusive.
    randint = function(a, b) {
        return Math.floor(Math.random()*(1+b-a)) + a;
    }

    reset_poly = function() {
        poly_times = 0;
        evaluated_at = [];
        document.getElementById("polyHint").innerHTML = "";
        document.getElementById("polyTimes").innerHTML = 0;
        document.getElementById("polyResult").innerHTML = "";
        reset_test_coefs();
        set_disables(guessing = true);
    }

    /* set the disabled status of inputs and buttons, depending on whether
     * the user is currently guessing the poly or not. */
    set_disables = function(guessing) {
        document.getElementById("newPolyBtn").disabled = guessing;
        document.getElementById("verifyPolyBtn").disabled = !guessing;
        document.getElementById("giveUpPolyBtn").disabled = !guessing;
        set_disabled_test_coefs(disabled = !guessing);
    }

    reset_test_coefs = function() {
        for (var i = 0; i <= max_degree; ++i) {
            document.getElementById(`c${i}`).value = 0;
        }
    }

    set_disabled_test_coefs = function(disabled) {
        for (var i = 0; i <= max_degree; ++i) {
            document.getElementById(`c${i}`).disabled = disabled;
        }
    }

    var poly = new Array(max_degree + 1);
    generate_poly = function() {
        for (var i = 0; i <= max_degree; ++i) {
            poly[i] = randint(0, max_coef);
        }
        reset_poly();
    }

    evaluate_poly = function() {
        var a = parseInt(document.getElementById("polyAt").value);
        var value = 0;
        for (var i = 0; i <= max_degree; ++i) {
            value += poly[i]*a**i;
        }
        document.getElementById("polyHint").innerHTML = `p(${a}) = ${value}`;
        if (-1 === evaluated_at.indexOf(a)) {
            evaluated_at.push(a);
            ++poly_times;
            document.getElementById("polyTimes").innerHTML = poly_times;
        }
    }

    verify_poly = function() {
        var right = true;
        for (var i = 0; i <= max_degree; ++i) {
            right &= document.getElementById(`c${i}`).value === `${poly[i]}`;
        }
        if (right) {
            document.getElementById("polyResult").innerHTML = "Certo!";
            set_disables(guessing = false);
        } else {
            document.getElementById("polyResult").innerHTML = "Errado!";
        }
    }

    give_up_poly = function() {
        set_disables(guessing = false);
        polyResult = `O polinómio era p(n) = ${poly[0]}`
        for (var i = 1; i<= max_degree; ++i) {
            polyResult += ` + ${poly[i]}n^${i}`;
        }
        document.getElementById("polyResult").innerHTML = polyResult;
    }

    window.onload = generate_poly;
</script>

![Um ponto de interrogação em néons](thumbnail.jpg "Fotografia de Emily Morter do site Unsplash")


# Enunciado do problema

Quero que jogues um jogo com o computador.
O computador vai pensar num polinómio com coeficientes inteiros e não negativos.
Suponhamos que $p(n)$ é o nome desse polinómio secreto.

Quero que descubras os coeficientes de $p(n)$, sendo que a única coisa que podes fazer é pedir dicas ao computador, na forma do valor $p(n)$ para $n \geq 0$ inteiro.
Por exemplo, podes perguntar quanto é $p(0)$ ou $p(49)$, mas não $p(-1)$ ou $p(0.5)$.
Tens de encontrar a melhor estratégia possível, para que consigas determinar $p(n)$ com o menor número de pistas possível.

Podes testar a tua estratégia aqui em baixo.
O computador só vai pensar em polinómios com grau $3$ ou menos
e os coeficientes também vão estar entre $0$ e $3$, mas isto é só para facilitar os teus testes.
A estratégia deverá funcionar para graus superiores e coeficientes maiores.

Com as restrições do jogo interativo tens

$$
p(n) = c_0 + c_1n + c_2n^2 + c_3n^3, 0 \leq c_i \leq 3
$$

!!! Boa sorte!

---

<div>
    <br />
    Pediste <span id="polyTimes">0</span> dica(s).
    <br />
    <button id="newPolyBtn" onclick="generate_poly()">Novo polinómio</button>
    <br />
    <br />
    <label>Avaliar o polinómio em</label> &nbsp; <input id="polyAt" type="number" step="1" min="0" size="6" value="0">. &nbsp; <button onclick="evaluate_poly()">Avaliar</button>
    <p id="polyHint"></p>
    <br>
    O teu palpite: p(n) = 
    <input id="c0" type="number" step="1" min="0" max="3" size="1" value="0">
    &nbsp; + &nbsp;
    <input id="c1" type="number" step="1" min="0" max="3" size="1" value="0">
    n &nbsp; + &nbsp;
    <input id="c2" type="number" step="1" min="0" max="3" size="1" value="0">
    n^2 &nbsp; + &nbsp;
    <input id="c3" type="number" step="1" min="0" max="3" size="1" value="0">
    n^3
    <br />
    <button id="verifyPolyBtn" onclick="verify_poly()">Verificar</button> <button id="giveUpPolyBtn" onclick="give_up_poly()">Desistir</button>
    <p id="polyResult"></p>
</div>

---

Se precisares de clarificar alguma coisa, não hesites em perguntar na secção de comentários em baixo.

Este problema foi-me colocado pela [MathGurl].


# Solução

A melhor estratégia funciona com apenas dois passos para *qualquer* polinómio.

Se ainda não conseguiste resolver este problema, tenta outra vez agora que já
sabes que há uma estratégia que funciona em dois passos.
É mais fácil descobrir uma estratégia que funcione num número fixo de passos
do que tentar inventar uma estratégia do zero.

Vamos supor que

$$
p(n) = \sum_{k = 1}^N c_k n^k
$$

é o polinómio secreto, onde $N$ é o grau (desconhecido) do polinómio e os coeficientes
$c_k$, $k < N$ são inteiros não negativos.

A primeira coisa a fazer é perguntar por $p(1)$, porque isso nos dá um valor máximo
para cada coeficiente:

$$
p(1) = \sum_{k = 1}^N c_k
$$

e todos os $c_k$ são não-negativos, logo temos a certeza que para qualquer $i$,

$$
c_i \leq \sum_{k = 1}^N c_k = p(1) ~ .
$$

Agora que sabemos que nenhum coeficiente é maior que $p(1)$, vamos definir
$b = p(1) + 1$ e perguntar por $p(b)$.
Se escrevermos o resultado de $p(b)$ em base $b$, então os dígitos de $p(b)$ na base
$b$ são os coeficientes $c_k$.
Isto funciona porque $b$ é maior que qualquer um dos coeficientes $c_k$, logo perguntar
pelo valor de $p(b)$ dá

$$
p(b) = \sum_{k = 1}^N c_k b^k ~ ,
$$

que é a forma de escrever $p(b)$ em base $b$, por definição.

Vou mostrar agora um par de exemplos, para que isto fique mais claro.
Se já tiveres percebido bem como isto funciona, então podes saltar o resto
do artigo e deixar o teu feedback em relação ao artigo, ou com um emoji ou com um
comentário!


## Exemplos

 1. Pedimos o valor de $p(1) = 1$.
 2. Definimos $b = p(1) + 1 = 2$.
 3. Pedimos o valor de $p(b) = p(2) = 8$.
 4. Escrevemos $p(b) = 8$ em base $b = 2$, que dá $1000_2$.
 5. Separamos os coeficientes em $1, 0, 0, 0$, resultando em $c_3 = 1$
e $c_2 = c_1 = c_0 = 0$.

**Example 2**: suppose $p(n) = x^3 + 2x$.

 1. Pedimos o valor de $p(1) = 3$.
 2. Definimos $b = p(1) + 1 = 4$.
 3. Pedimos o valor de $p(b) = p(4) = 72$.
 4. Escrevemos $p(b) = 72$ em base $b = 4$, que dá $1020_4$.
 5. Separamos os coeficientes em $1, 0, 2, 0$, resultando em $c_3 = 1$, $c_1 = 2$
e $c_2 = c_0 = 0$.

**Example 3**: suppose $p(n) = 4x^2 + 2x + 3$.

 1. Pedimos o valor de $p(1) = 9$.
 2. Definimos $b = p(1) + 1 = 10$.
 3. Pedimos o valor de $p(b) = p(10) = 423$.
 4. Escrevemos $p(b) = 423$ em base $b = 10$, que dá $423_{10}$!
 5. Separamos os coeficientes em $4, 2, 3$ resultando em $c_2 = 4$, $c_1 = 2$
e $c_0 = 3$.

Espero que tudo tenha feito mais sentido com estes exemplos!
Se precisares de converter algum número para outra base, podes sempre
[pedir ao WolframAlpha][wa-convert].


Não te esqueças de [subscrever a newsletter][subscribe] para receberes os problemas diretamente na tua caixa de correio,
e deixa a tua reação a este problema em baixo.

[MathGurl]: https://www.youtube.com/channel/UC5RV_s1Jh-jQI4HfexEIb2Q
[sol]: ../../solutions/{{ page.slug }}
[subscribe]: https://mathspp.com/subscribe
