<div id="gameContainer" style="margin:0 auto;">
    <h1 style="padding:10px">Diamond Hands<h1>
    <p>The objective of this game is to increase the total amount of money you have.</p>
    <p>To achieve that objective, you get to invest in a single stock and should decide on a daily basis if you are going to sell your position (paper hands) or hold your position (diamond hands).</p>

    <textarea id="mainTextarea" style="overflow-y:scroll;resize:none" rows="15"></textarea>

    <br />
    <button class="button" onclick="sell()" style="float:right;padding:5px;margin:10px;">🧻🙌 (sell)</button>
    <button class="button" onclick="hold()" style="float:right;padding:5px;margin:10px;">💎🙌 (hold)</button>

    <br />
    <i class="fas fa-dollar-sign fa-9x" data-fa-transform="rotate-37" id="clickDollar" style="></i>

</div>

<script src="diamond-hands/game.js" type="text/javascript"></script>

<style>
    #gameContainer * { text-align: center }
</style>
