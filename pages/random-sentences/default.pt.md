---
title: Frases aleatórias
---

<link rel="stylesheet" type="text/css" href="https://mathspp.com/random-sentences/highlighting.css">

<h1> {{ page.title }} </h1>

Esta página contém a lista de <i>todas</i> as frases que podem aparecer aleatoriamente no <a href='#footer'>rodapé</a> do meu blogue. Podes contribuir para esta lista adicionando mais frases aos ficheiros <a class='external-link no-image' target='_blank' href='https://github.com/rojergs/mathspp/tree/master/languages/'>desta</a> pasta no GitHub.

{% set langobj  = grav['language'] %}
{% set curlang  = langobj.getLanguage() %}
{% set sentences = langobj.getTranslation(curlang, 'RANDOM_SENTENCES', true) %}

<ol>
{% for sentence in sentences %}
    <li id="li{{loop.index}}"><a class="anchor" id="{{loop.index}}"></a> {{ sentence }} </li>
{% endfor %}
</ol>

<script type="text/javascript" src="https://mathspp.com/random-sentences/highlighting.js"></script>
