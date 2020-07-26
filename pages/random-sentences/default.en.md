---
title: Random sentences
---

<h1> {{ page.title }} </h1>

{% set langobj  = grav['language'] %}
{% set curlang  = langobj.getLanguage() %}
{% set sentences = langobj.getTranslation(curlang, 'RANDOM_SENTENCES', true) %}

<ul>
{% for sentence in sentences %}
    <li><p id="s{{loop.index}}"> {{ sentence }} </p></li>
{% endfor %}
</ul>