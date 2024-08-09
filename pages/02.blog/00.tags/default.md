---
process:
    twig: true
twig_first: true
visible: false
---

# Blog tags


{% set taxlist = taxonomylist.get() %}

{% if taxlist %}
<p>Oi</p>
    {% for tax, value in taxlist["tag"] %}
        <a class="label label-rounded">{{ tax }} | {{ value }}</a>
    {% endfor %}
{% endif %}

