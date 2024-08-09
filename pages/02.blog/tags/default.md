---
process:
    twig: true
twig_first: true
---

# Blog tags

{% include 'partials/taxonomylist.html.twig' with {base_url: my_url, taxonomy: 'tag', children_only: true, of_page: page.parent} %}

{% set taxlist = children_only is defined ? taxonomylist.getChildPagesTags() : taxonomylist.get() %}

{% if taxlist %}
    {% for tax, value in taxlist["tag"] %}
        <a class="label label-rounded">{{ tax }} | {{ value }}</a>
    {% endfor %}
{% endif %}

