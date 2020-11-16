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

![Um ponto de interrogação em néons](question_mark.jpg "Fotografia de Emily Morter do site Unsplash")

### Enunciado do problema

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

### Solução

A minha proposta de solução vai ser publicada [aqui][sol] quando tiverem passado duas semanas desde a publicação deste problema. Também podes usar o link para partilhar a tua própria solução nos comentários. Por favor, **não** escrevas a tua solução nos comentários aqui em baixo.
<!--Podes encontrar a minha proposta de solução [aqui][sol], para confirmares a tua resposta. Também podes usar o link para partilhar a tua própria solução nos comentários. Por favor, **não** escrevas a tua solução nos comentários aqui em baixo.-->

---

Este problema foi-me colocado pela [MathGurl].

Se gostaste deste problema e se gostavas de receber novos problemas diretamente na tua caixa de correio, então [subscreve a newsletter dos Problemas][subscribe].

[MathGurl]: https://www.youtube.com/channel/UC5RV_s1Jh-jQI4HfexEIb2Q
[sol]: ../../solutions/{{ page.slug }}
[subscribe]: https://mathspp.com/subscribe
