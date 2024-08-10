<h1>Blog tags</h1>

{% set taxlist = taxonomy.getTaxonomyItemKeys("tag") %}

{% if taxlist %}
<span class="tags">
{% for tax in taxlist %}
    <a class="label label-rounded" href="/blog/tag{{ config.system.param_sep }}{{ tax }}">{{ tax }}</a>
{% endfor %}
</span>
{% endif %}
