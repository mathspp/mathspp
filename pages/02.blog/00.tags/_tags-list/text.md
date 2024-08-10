{% set taxlist = taxonomy.getTaxonomyItemKeys("tag")|sort %}

{% if taxlist %}
<ul>
{% for tax in taxlist %}
    {% set tag_description_name = 'TAG_DESCRIPTIONS.' ~ tax %}
    {% set tag_description = tag_description_name|t %}
    {% if tag_description_name != tag_description %}
    <li><a href="/blog/tag{{ config.system.param_sep }}{{ tax }}">{{ tax }}</a>: {{ tag_description }}</li>
    {% else %}
    <li><a href="/blog/tag{{ config.system.param_sep }}{{ tax }}">{{ tax }}</a></li>
    {% endif %}
{% endfor %}
</ul>
{% endif %}
