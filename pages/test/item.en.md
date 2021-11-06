
===

<ol>
{% for article in taxonomy.findTaxonomy({"tag": ["nnfwp"]}).order("date") %}
    <li><a href=" {{ article.url|e }} " > {{ article.title }} </a></li>
{% endfor %}
</ol>
