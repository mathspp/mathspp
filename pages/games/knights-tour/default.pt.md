---
menu: "Passeio do Cavalo"
metadata:
    description: "O 'Passeio do Cavalo' é um mini jogo inspirado no xadrez."
title: "Passeio do Cavalo"
---

<div id="gameContainer" style="margin:0 auto;">
    <h1 style="padding:10px">Passeio do Cavalo</h1>
    <p>Os jogadores movem um cavalo no tabuleiro de xadrez, alternadamente, evitando quadrados já visitados.</p>
    <p>O primeiro jogador sem conseguir fazer uma jogada, perde.</p>
    <br />
    <p id="alertsParagraph" language="pt"></p>
    <br />
    <canvas id="myCanvas"></canvas>
    <textarea id="movesTextarea" style="height:auto;resize:none;"></textarea>
    <br />
    <input type="checkbox" id="computerGoesFirstCheckbox" name="computerGoesFirstCheckbox" style="float:left;">
    <label for="computerGoesFirstCheckbox" style="float:left;margin-left:5px">O computador joga primeiro.</label>
    <button class="button" onclick="resetGame()" style="float:right;">Recomeçar jogo.</button>
    <br />
    <br />
    <p>Lê <a href="https://mathspp.com/blog/problems/knights-tour" target="_blank">este artigo</a> no meu blogue.</p>
    <p>Se estiveres a ter dificuldades com este mini jogo, tenta usar um navegador Firefox ou Chrome recente, num computador.</p>
</div>

<script src="knights-tour/game.js" type="text/javascript"></script>

<style>
    #gameContainer * { text-align: center }
    canvas { background: #eee; display: block; margin: 0 auto; }
</style>
