<h1>Hall of Fame <i class="fas fa-medal"></i></h1>

<p>Aqui encontras uma lista de todas as pessoas que já completaram o jogo.
Se completares o nível 20 em 15 movimentos, tens direito a uma estrela junto ao teu nome.
Se gostavas de acrescentar o teu nome a esta lista, por favor
<a href="mailto:syncro0game@gmail.com">envia um email</a> à equipa do Syncro,
para o endereço syncro0game(at)gmail.com.</p>

<ul>
{% for person in page.header.hof %}
 <li> {{ person }} </li>
{% endfor %}
</ul>
