<h1>Hall of Fame <i class="fas fa-medal"></i></h1>

<p>Here is a list of all the people who have completed the game.
If you finish level 20 with only 15 moves, you also get a star next to your name.
If you would like to have your name/alias added here, please <a href="mailto:syncro0game@gmail.com">email</a> the Syncro team
to syncro0game(at)gmail.com.</p>

<ul>
{% for person in page.header.hof %}
 <li> {{ person }} </li>
{% endfor %}
</ul>
