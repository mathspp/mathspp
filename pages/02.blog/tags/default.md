---
process:
    twig: true
twig_first: true
---

# Blog tags


{% set taxlist = children_only is defined ? taxonomylist.getChildPagesTags() : taxonomylist.get() %}

{% if taxlist %}
<p>Oi</p>
    {% for tax, value in taxlist["tag"] %}
        <a class="label label-rounded">{{ tax }} | {{ value }}</a>
    {% endfor %}
{% endif %}

