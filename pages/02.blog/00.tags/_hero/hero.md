<h1>Blog tags</h1>

{% set taxlist = taxonomy.taxonomy()["tag"] %}

{% if taxlist %}
<span class="tags">
{% for tax, pages in taxlist|sort %}
    <a class="label label-rounded" style="color:#3085ee" href="/blog/tag{{ config.system.param_sep }}{{ tax }}">{{ tax }} | {{ pages|length }}</a>
{% endfor %}
</span>
{% endif %}
