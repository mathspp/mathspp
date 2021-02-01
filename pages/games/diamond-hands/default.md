<script src="/user/themes/myquark/js/all.min.js" type="text/javascript"></script>

<div id="gameContainer" style="margin:0 auto;text-align:center;">
    <h1 style="padding:10px">Diamond Hands<h1>
    <h3 style="padding:5px">Minimalistic Meme Game</h3>
    <p>
    The objective of this game is to increase your net worth.<br />
    You can increase your worth by 💎🙌'ing (holding) GME shares throughout the days.<br />
    If you MUST have cash, you can go 🧻🙌 (sell your shares) like a monkey.<br />
    You can click the diamond to earn more shares without spending money.
    </p>

    <textarea readonly id="eventsTextarea" style="overflow-y:scroll;resize:none;text-align:left;font-family:'Miriam Libre'" rows="15"></textarea>

    <br />
    <div class="smallBanner">
        <p style="float:left;">GME value: <span id="gme_value"></span></p>
        <button title="Skip day after selling (shortcut: S)" class="button" onclick="sell()" style="float:right;">🧻🙌 (sell)</button>
        <button title="Skip day while holding (shortcut: H)" class="button" onclick="hold()" style="float:right;">💎🙌 (hold)</button>
    </div>

    <div class="smallBanner" id="exceptional-event-div">
        <br class="clear">
        <!-- Use https://stackoverflow.com/a/11837673/2828287 to animate a new event showing up/disappearing. -->
        <p style="text-align:left;float:left;" id="exceptional-event-text"></p>
        <button class="button" onclick="exceptional_event_buy()" style="float:right;">Buy for <span id="exceptional-event-cost"></span>.</button>
    </div>

    <br class="clear">

    <i class="fas fa-gem fa-9x" id="clickableDollar" onclick="clickDollar()" style="margin:auto;"></i>

    <br class="clear">

    <div id="statsDiv" style="float:left;">
        <p style="text-align:left">
            Cash: <span id="cash"></span> <br />
            Shares owned: <span id="shares"></span> <br />
            Net worth: <span id="total_value"></span>
        </p>
    </div>

    <br class="clear"/>

    <p><small>This game is not a simulator of the financial markets, nor does it represent real word data/real world events. Do not base your investment strategies in your experience with this game.</small></p>
</div>

<script src="diamond-hands/game.js" type="text/javascript"></script>

<style>
    .smallBanner * {
        padding: 5px;
        margin: 10px;
    }
    .clear {
        clear: both;
    }
    #gameContainer * { text-align: center }
    .green {
        color: #33d61a;
    }
    .red {
        color: #e32619;
    }
    #exceptional-event-div {
        -moz-transition: height .5s;
        -ms-transition: height .5s;
        -o-transition: height .5s;
        -webkit-transition: height .5s;
        transition: height .5s;
        height: 0;
        overflow: hidden;
    }
</style>
