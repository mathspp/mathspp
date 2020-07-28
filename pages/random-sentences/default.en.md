---
title: Random sentences
---

<style type="text/css">
/* https://stackoverflow.com/a/13184714/2828287 */
a.anchor {
    display: block;
    position: relative;
    top: -46px;
    visibility: hidden;
}

@keyframes fade {
    0% {
        background: #ff07;
        box-shadow: 0 0 1em yellow;
        border-radius: 1em;
    }
    50% {
        background: #ff07;
        box-shadow: 0 0 1em yellow;
        border-radius: 1em;
    }
}

.highlight {
    animation: fade 2s;
    padding-left: 2em;
    margin-left: -2em;
    padding-right: 1em;
    margin-right: -1em;
    position:relative;
    z-index:-1;
}
</style>

<h1> {{ page.title }} </h1>

This page lists _all_ the sentences that can randomly appear in the [footer](#footer) of my blog! You can contribute to this list by adding sentences to the files in [this][contribute] folder on GitHub.

[contribute]: https://github.com/rojergs/mathspp/tree/master/languages/

{% set langobj  = grav['language'] %}
{% set curlang  = langobj.getLanguage() %}
{% set sentences = langobj.getTranslation(curlang, 'RANDOM_SENTENCES', true) %}

<ol>
{% for sentence in sentences %}
    <li><a class="anchor" id="{{loop.index}}"></a><p id="p{{loop.index}}"> {{ sentence }} </p></li>
{% endfor %}
</ol>

<script type="text/javascript">
    // cf. https://chat.stackexchange.com/transcript/message/55084622#55084622
    var highlight = function() {
        var id = window.location.hash.split("?")[0].slice(1);
        if (id) {
            var p = document.getElementById("p"+id);
            p.innerHTML = "<span class='highlight'>" + p.innerHTML + "</span>";
        }
    }
    window.onhashchange = highlight;
    highlight();
</script>
