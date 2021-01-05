---
menu: "Knight's Tour"
metadata:
    description: "The 'Knight's Tour' is a minigame inspired by chess."
title: "Knight's Tour"
---

<div id="gameContainer" style="margin:0 auto;">
    <h1 style="padding:10px">Knight's Tour</h1>
    <p>Players take turns moving a chess knight in the board, avoiding positions that have already been visited.</p>
    <p>First player to not have a free square to move to loses.</p>
    <br />
    <p id="alertsParagraph" language="en"></p>
    <br />
    <canvas id="myCanvas"></canvas>
    <textarea id="movesTextarea" style="height:auto;resize:none;"></textarea>
    <br />
    <input type="checkbox" id="computerGoesFirstCheckbox" name="computerGoesFirstCheckbox" style="float:left;">
    <label for="computerGoesFirstCheckbox" style="float:left;margin-left:2px">Computer goes first.</label>
    <button onclick="resetGame()" style="float:right;">Restart Game</button>
    <br />
    <br />
    <p>See <a href="https://mathspp.com/blog/problems/knights-tour" target="_blank">this blog post</a>.</p>
    <p>If you cannot play this mini game, try using a modern Firefox or Chrome browser, in a computer.</p>
</div>

<script src="knights-tour/game.js" type="text/javascript"></script>

<style>
    #gameContainer * { padding: 0; margin: 0; text-align: center }
    canvas { background: #eee; display: block; margin: 0 auto; }
</style>
