const WIDTH = Math.min(window.screen.width, 800);
const RED = "#e32619";
const GREEN = "#33d61a";

document.getElementById("gameContainer").style.width = `${WIDTH}px`;
// Get the reference to the textarea that registers the events.
var eventsTextarea = document.getElementById("eventsTextarea");
eventsTextarea.style.width = `${WIDTH}px`;

var shares = 0;
var gme_value = 300;
var clicks = 0;
var clicks_needed = 3;
var next_shares = 2;
var cash = 1000;
var days_elapsed = 0;
var last_change = 0;

var positive_sentences = [
    "Apes. Together. Strong. 🐵",
    "I like the stock.",
    "I lIkE tHe StOcK.",
    "A series of monkeys install trading apps and coincidentally buy GME.",
    "Many monkeys like GME.",
    "To the moon! 🌕",
    "Let's gooo 🚀",
    "To Mars!",
    "To Jupyter!",
    "To Saturn 🪐🪐",
    "To Uranus!",
    "To Neptune!",
    "To Pluto!",
    "To Alpha Centauri 🌠🌠",
    "A billboard by the highway 🚗🚗 motivates retail investors.",
    "Gamestonk!",
    "Can't Stop, Won't Stop, GameStop.",
    "A Chinese 🇨🇳 billionaire speaks on Twitter about GME.",
    "A Russian 🇷🇺 mobster speaks about GME on TikTok.",
    "A French 🇫🇷 mime gesticulates about GME.",
    "An Italian 🇮🇹 chef creates a pizza 🍕 with the GME logo.",
    "Nordic countries freeze their sell buttons.",
    "Dutch tulip fields 🌷 seeded to form the GME logo after blossoming.",
    "Australian Hedge Funds get their buttons upside down and buy instead of selling.",
    "Massages with 'happy ending' now yield GME shares.",
    "A famous footballer talks about GME in their Instagram stories",
    "A porn star mentions GME in their OnlyFans.",
    "A Hollywood star wears GME to the Oscars.",
    "A Bollywood star posts about GME on Facebook.",
    "Penguins 🧊 develop basic alphabet with three letters: G, M and E.",
    "Fast food chains 🍔 now sell consoles with kid's menus.",
    "The Statue of Liberty now holds the GME logo instead of a torch 🔥",
    "A charismatic redditor gives an interview to WSJ.",
    "Strawberries are red.",
    "The sky is blue.",
    "Lemons are yellow.",
    "The grass is green.",
    "Chocolate is brown.",
    "RIP is now universally understood as 'Rest In Profits'.",
    "Flowers start to bloom in the Northern hemisphere.",
    "Tendies taste good.",
    "McDonald's replaces nuggets with tendies.",
    "KFC replaces all 🐔 food with tendies.",
    "New Pokémon game only distributed in GameStop stores.",
    "New iPhone 69 only for sale at GameStop stores.",
    "You ring the bell on Monday morning.",
    "Guardians of the Stonks, Vol. III premieres 🎥 worldwide.",
    "Harry Potter and the Hands 🙌 of Diamond 💎 now on streaming platforms.",
    "Harry Potter and the Gem Stone 💎💎 announced for next year.",
    "Star Wars: the Hedge Fund Wars announced to start filming soon.",
    "Dictionaries updated, 'retarded' now defined as 'someone with many GME shares.",
    "Dictionaries updated, 'ape' now means 'retail trader'.",
    "If mankind has been to the Moon, why can't GME? 💸💸 ",
    "UFO sighted.",
    "You pray to u/WSBGod.",
    "@wsbmod retweets you.",
    "@wsbmod now follows you.",
    "New TikTok trend involves mocking hedge funds.",
    "New TikTok trend involves dancing like an ape.",
];

var negative_sentences = [
    "Dang it!",
    "Media outlets manipulate the news.",
    "Hedge Funds perform a ladder attack.",
    "People mistake GM for GME.",
    "Analysts 🤓🤓🤓 predict drop in GME.",
    "You slip on a banana peel.",
    "Polemic with former US president ensues stock market loss.",
    "WSB is mistaken by 'WallStreet Babies'.",
    "u/DFV catches a cold.",
    "UFO sighted.",
    "The sea level rises.",
    "Winter is coming!",
    "Rich people lose their yachts 🛥️",
    "Illuminati turn reddit off for a couple of hours.",
    "Buy buttons disabled.",
    "For 'risk control' reasons you can't buy GME.",
    "You do not trade for a day because you were out in a 🦷 dentist appointment.",
    "You forgot to get hydrated during the day.",
    "You have cold lasagna for dinner.",
    "Your wife's boyfriend doesn't give you your allowance.",
    "GME shares forced to socially distance because of COVID-19.",
    "Your favourite show is canceled.",
    "Rumour has it that the man never went to the moon, and so GME also can't go.",
    "Tax exemptions for those who own three or more yacths 🛥️",
    "u/WSBGod does not answer your prayers.",
    "Social media creates fake ads driving attention to boring stocks.",
    "Boring weekend.",
];

var earn_shares_sentences = [
    "You robbed a kid and got @ GME shares.",
    "You find @ GME shares on the street.",
    "A charity organisation donated you @ GME shares.",
    "u/DFV transferred you @ GME shares.",
    "US Government issued financial stimulus: you get @ GME shares.",
    "You won at Monopoly at a family reunion and were awarded @ GME shares.",
    "You lost at Monopoly at a family reunion and were given @ GME pity shares.",
    "A sweet, old lady thinks you are cute and gives you @ GME shares.",
]

/* Push text to the bottom of the events textarea and scroll to bottom. */
var pushText = function(string) {
    eventsTextarea.value += string + "\n";
    eventsTextarea.scrollTop = eventsTextarea.scrollHeight;
}

/* Return random element from a list. */
var getRandomElem = function(list) {
    var idx = Math.floor(list.length * Math.random());
    return list[idx];
}

/* Build a string with +/- and the % change of the GME stock, e.g. -5.63% or +2.00%. */
var formatGMEChange = function() {
    return `${last_change < 0 ? "" : "+"}${(last_change/100).toFixed(2)}%`;
}

/* Update the UI with all the most recent numbers. */
var updateUI = function() {
    document.getElementById("cash").innerHTML = `$${cash.toFixed(2)}`;
    document.getElementById("shares").innerHTML = shares;
    document.getElementById("total_value").innerHTML = `$${(cash + shares*gme_value).toFixed(2)}`;

    var gme_value_span = document.getElementById("gme_value");
    gme_value_span.classList.remove("green");
    gme_value_span.classList.remove("red");
    if (last_change < 0) {
        gme_value_span.classList.add("red");
    } else if (last_change > 0) {
        gme_value_span.classList.add("green");
    }
    var value_str = `$${gme_value.toFixed(2)} (${formatGMEChange()})`;
    document.getElementById("gme_value").innerHTML = `${value_str}`;
}

/* Award shares to the user because the user clicked the diamond enough times. */
var earnShares = function() {
    shares += next_shares;
    var sentence = getRandomElem(earn_shares_sentences).replace("@", next_shares);
    pushText(sentence);
    ++next_shares;
    clicks = 0;
    clicks_needed = Math.floor(Math.pow(next_shares, 1.5));
}

/* Function triggered every time the user clicks the dollar. */
var clickDollar = function() {
    ++clicks;

    if (clicks >= clicks_needed) {
        earnShares();
        updateUI();
    }

    document.getElementById("clickableDollar").setAttribute("data-fa-transform", `rotate-${(360*clicks/clicks_needed).toFixed(2)}`);
}

/* Update the textarea with the event that drove the price more recently. */
var pushNewEvent = function() {
    var get_from = last_change < 0 ? negative_sentences : positive_sentences;
    pushText(`Day ${days_elapsed}: ${getRandomElem(get_from)} GME ${formatGMEChange()}`);
}

/* Split the GME stock and update the game state. */
var splitStock = function() {
    var target_price = 5 + 5*Math.random();
    var split_in = Math.floor(gme_value / target_price);
    gme_value /= split_in;
    shares *= split_in;

    pushText(`GME stock split in a 1:${split_in} ratio to new target price $${gme_value.toFixed(2)}.`);
}

/* Update the GME stock value as the day progresses. */
var nextDay = function() {
    ++days_elapsed;

    // Randomly pick the percentage change of GME, which is a value between -99.99% and +399.96%.
    var multiplier = Math.min(4*9999, 500 + 10*days_elapsed);
    var change = multiplier*Math.sqrt(Math.random());
    if (Math.random() < 0.3) {
        change *= -0.25;
    }
    last_change = change;
    gme_value *= 1 + last_change/10000;

    if (gme_value > 1000) {
        splitStock();
    }

    pushNewEvent();
    updateUI();
}

/* Skip to next day after selling all GME shares owned. */
var sell = function() {
    if (shares > 0) {
        var cash_won = shares*gme_value;
        cash += cash_won;
        pushText(`You sold ${shares} GME shares at $${gme_value.toFixed(2)}/share, for a total of $${cash_won.toFixed(2)}`);
        shares = 0;
    }
    nextDay();
}

/* Hold the GME position and skip to next day. */
var hold = function() {
    var to_buy = Math.floor(cash / gme_value);
    if (to_buy > 0) {
        var cost = gme_value*to_buy;
        cash = Math.max(0, cash - cost);
        shares += to_buy;
        pushText(`You bought ${to_buy} GME shares at $${gme_value.toFixed(2)}/share, spending $${cost.toFixed(2)}.`);
    }
    nextDay();
}

updateUI();
