<h1>Blog tags</h1>

{% set taxlist = taxonomy.taxonomy()["tag"]|sort|reverse %}

{% if taxlist %}
<span class="tags" style="line-height:200%">
{% for tax, pages in taxlist|slice(0, 15) %}
    <a class="label label-rounded" style="color:#3085ee;font-size:110%" href="/blog/tag{{ config.system.param_sep }}{{ tax }}">{{ tax }}</a>
{% endfor %}
</span>
{% endif %}
{% if taxlist %}
<span class="tags">
{% for tax in taxlist|slice(15)|keys|sort %}
    <a class="label label-rounded" style="color:#3085ee;" href="/blog/tag{{ config.system.param_sep }}{{ tax }}">{{ tax }}</a>
{% endfor %}
</span>
{% endif %}
