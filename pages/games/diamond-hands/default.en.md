---
menu: "I Like Stonks"
metadata:
    description: "In this simple game you get to experience part of what a trader's like looks like."
title: "I Like Stonks"
---

<div id="gameContainer" style="margin:0 auto;">
    <h1 style="padding:10px">I Like Stonks</h1>
    <p>The objective of this game is to increase the total amount of money you have.</p>
    <p>To achieve that objective, you get to invest in a single stock and should decide on a daily basis if you are going to sell your position (paper hands) or hold your position (diamond hands).</p>

    <textarea id="mainTextarea" style="overflow-y:scroll;"></textarea>

    <i class="fas fa-dollar-sign" id="clickDollar"></i>

    <button onclick="sell()">🧻🙌 (sell)</button>
    <button onclick="hold()">💎🙌 (hold)</button>
</div>

<script src="knights-tour/game.js" type="text/javascript"></script>

<style>
    #gameContainer * { text-align: center }
    canvas { background: #eee; display: block; margin: 0 auto; }
</style>
