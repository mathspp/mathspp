<h1>Blog tags</h1>

{% set taxlist = taxonomy.taxonomy()["tag"] %}

{% if taxlist %}
<span class="tags" style="color:#f8f8f2">
{% for tax, pages in taxlist %}
    <a class="label label-rounded" href="/blog/tag{{ config.system.param_sep }}{{ tax }}">{{ tax }} | {{ pages|length }}</a>
{% endfor %}
</span>
{% endif %}
