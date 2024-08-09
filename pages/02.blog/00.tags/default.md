---
process:
    twig: true
twig_first: true
visible: false
---

# Blog tags


{% set taxlist = taxonomy.findTaxonomy("tag") %}

{% if true %}
<p>Bye</p>
{% endif %}

<p>{{ taxlist }}</p>
<p>{{ dump(taxlist) }}</p>
<p>{{ dumps(taxlist) }}</p>

{% if taxlist %}
<p>Oi</p>
    {% for tax in taxlist %}
        <a class="label label-rounded">{{ tax }} | {{ value }}</a>
    {% endfor %}
{% endif %}

