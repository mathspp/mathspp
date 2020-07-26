{% set langobj  = grav['language'] %}
{% set curlang  = langobj.getLanguage() %}
{% set sentences = langobj.getTranslation(curlang, 'RANDOM_SENTENCES', true) %}

{% if curlang == 'en' %}
{% page.title = "Random sentences" %}
{% else %}
{% page.title = "Frases aleatórias" %}
{% endif %}

<h1> {{ page.title }} </h1>

<ul>
{% for sentence in sentences %}
    <li><p> {{ sentence }} </p></li>
{% endfor %}
</ul>