
===

<ol>
{% for post in taxonomy.findTaxonomy({"tag": ["nnfwp"]}).order("date") %}
    <li><a href="{{ post.url|e }}">{{ post.title }}</a></li>
{% endfor %}
</ol>

<br />

 
 ​<ul> 
 ​{% for post in taxonomy.findTaxonomy({"tag": ["lsbasi-apl"]}) %} 
 ​    <li><a href="{{ post.url }}">{{ post.title }}</a></li> 
 ​{% endfor %} 
 ​</ul>
