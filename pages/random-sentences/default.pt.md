---
title: Frases aleatórias
---

<style type="text/css">
/* https://stackoverflow.com/a/13184714/2828287 */
a.anchor {
    display: block;
    position: relative;
    top: -46px;
    visibility: hidden;
}
</style>

<h1> {{ page.title }} </h1>

{% set langobj  = grav['language'] %}
{% set curlang  = langobj.getLanguage() %}
{% set sentences = langobj.getTranslation(curlang, 'RANDOM_SENTENCES', true) %}

<ul>
{% for sentence in sentences %}
    <li><a class="anchor" id="s{{loop.index}}"></a><p> {{ sentence }} </p></li>
{% endfor %}
</ul>
