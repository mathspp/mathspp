---
title: Random sentences
---

<h1> {{ page.title }} </h1>

{% set langobj  = grav['language'] %}
{% set curlang  = langobj.getLanguage() %}
{% set sentences = langobj.getTranslation(curlang, 'RANDOM_SENTENCES', true) %}

<ul>
{% for sentence in sentences %}
    <li id="s{{loop.index}}"><p> {{ sentence }} </p></li>
{% endfor %}
</ul>