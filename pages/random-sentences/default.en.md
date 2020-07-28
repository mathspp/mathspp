---
title: Random sentences
---

<link rel="stylesheet" type="text/css" href="highlighting.css">

<h1> {{ page.title }} </h1>

This page lists <i>all</i> the sentences that can randomly appear in the <a href="#footer">footer</a> of my blog! You can contribute to this list by adding sentences to the files in <a class='external-link no-image' target='_blank' href='https://github.com/rojergs/mathspp/tree/master/languages/'>this</a> folder on GitHub.

{% set langobj  = grav['language'] %}
{% set curlang  = langobj.getLanguage() %}
{% set sentences = langobj.getTranslation(curlang, 'RANDOM_SENTENCES', true) %}

<ol>
{% for sentence in sentences %}
    <li><a class="anchor" id="{{loop.index}}"></a><p id="p{{loop.index}}"> {{ sentence }} </p></li>
{% endfor %}
</ol>

<script type="text/javascript" src="highlighting.js"></script>
