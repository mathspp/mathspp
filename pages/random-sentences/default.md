{% set langobj  = grav['language'] %}
{% set curlang  = langobj.getLanguage() %}
{% set sentences = langobj.getTranslation(curlang, 'RANDOM_SENTENCES', true) %}

{% if curlang == 'en' %}
<h1>Random sentences</h1>
{% else %}
<h1>Frases aleatórias</h1>
{% endif %}

<ul>
{% for sentence in sentences %}
    <li><p> {{ sentence }} </p></li>
{% endfor %}
</ul>