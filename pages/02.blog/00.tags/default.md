---
process:
    markdown: false
    twig: true
twig_first: true
visible: false
---

<h1>Blog tags</h1>

{% set taxlist = taxonomy.getTaxonomyItemKeys("tag") %}

{% if taxlist %}
<ul>
{% for tax in taxlist|sort %}
<li>{{ tax }}</li>
{% endfor %}
</ul>
{% endif %}

