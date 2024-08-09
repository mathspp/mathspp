---
process:
    twig: true
twig_first: true
visible: false
---

# Blog tags


{% set taxlist = taxonomy.getTaxonomyItemKeys("tag") %}

{% if true %}
<p>Bye</p>
{% endif %}

{% if taxlist %}
<p>Oi</p>
    {% for tax in taxlist %}
        <a class="label label-rounded">{{ tax }} | {{ value }}</a>
    {% endfor %}
{% endif %}

