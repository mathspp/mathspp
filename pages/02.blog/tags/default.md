---
process:
    twig: true
twig_first: true
---

# Blog tags

<ul>
{% for tax in taxonomylist.get() %}
<li>{{ tax }}</li>
{% endfor %}
</ul>
