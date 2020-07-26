---
title: Random sentences
---

{% set langobj  = grav['language'] %}
{% set curlang  = langobj.getLanguage() %}
{% set sentences = langobj.getTranslation(curlang, 'RANDOM_SENTENCES', true) %}

<ul>
{% for sentence in sentences %}
    <li><p> {{ sentence }} </p></li>
{% endfor %}
</ul>