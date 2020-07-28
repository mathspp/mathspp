---
metadata:
    description: Neste artigo vou mostrar como usei Python para automatizar parte
        da gestão do meu blogue.
title: 'YAMLUtils: automatizar tarefas aborrecidas com Python'
---

Se há uma coisa de que gosto em programar em Python é que posso usá-lo para automatizar tarefas aborrecidas. Hoje usei-o para gerir o meu blogue!

===

![a close-up of three gears turning together](gears.jpg "Photo by Bill Oxford on Unsplash")

Vou começar por descrever o problema que tinha e depois mostro como é que Python me ajudou.

### O problema

Cada página no meu blogue tem dois ficheiros que lhe correspondem, um para a versão inglesa e outro para a versão portuguesa. É assim que consigo ter um site com duas línguas com [Grav]. Por exemplo, se fores a [este link do GitHub][yamlutils-post] vais encontrar a pasta onde estão os conteúdos deste artigo. Lá dentro encontras três ficheiros principais:

 - `item.en.md`
 - `item.pt.md`
 - `frontmatter.yaml`

Os ficheiros `item` são os que contêm o artigo em si e as extensões `.en.md` e `.pt.md` identificam a língua em que o artigo está escrito. O ficheiro `frontmatter.yaml` tem alguns "cabeçalhos" que me ajudam a customizar o blogue e o artigo. Eu defino esses cabeçalhos com uma sintaxe especial chamada [YAML]; com ela posso definir os tags do artigo, o título do artigo, o "slug" (o sufixo do URL, para este artigo em particular é `yamlutils`), a data em que o artigo foi publicado, etc.

A maior parte desses cabeçalhos não depende da língua em que o site está a ser visualizado, por exemplo eu uso sempre o mesmo slug:

 - [https://mathspp.com/en/blog/yamlutils](https://mathspp.com/en/blog/yamlutils)
 - [https://mathspp.com/pt/blog/yamlutils](https://mathspp.com/pt/blog/yamlutils)

Os cabeçalhos que são independentes da língua são definidos no ficheiro `frontmatter.yaml`, que se parece com isto:

<script src="https://gist.github.com/RojerGS/0ff988fb2ac54a81dc18349cc9c619f9.js"></script>



Por outro lado, há alguns cabeçalhos que dependem da língua do utilizador. Por exemplo, o título do artigo (que está no topo da página e no separador do teu navegador) é adaptado para cada língua. Esses cabeçalhos específicos ficam no início de cada ficheiro `item`, entre `---`, que faz com que o GitHub os mostre assim:

<script src="https://gist.github.com/RojerGS/1f8f2727e6358ad33bec5700be4220ed.js"></script>



As caixas em cima correspondem aos cabeçalhos dos dois ficheiro `item` diferentes. Provavelmente consegues ver que a estrutura das caixas é exatamente igual mas que os conteúdos das caixas estão escritos em línguas diferentes.

O problema é que no início do site e do blogue eu não usava os ficheiros `frontmatter.yaml` para os cabeçalhos iguais, portanto tinha vários ficheiros `*.en.md` e `*.pt.md` com cabeçalhos repetidos... e ter coisas repetidas é desagradável porque faz com que seja mais difícil manter tudo igual e atualizado. Eu podia resolver este problema à mão... mas isso seria muito aborrecido!


### Python ao salvamento

Por ser uma linguagem para escrever scripts, Python é extremamente útil neste tipo de tarefas. Eu percebi que podia facilmente escrever um script que atravessasse os meus diretórios, à procura de pares de ficheiros `.pt.md` e `.en.md`, que recolhesse os cabeçalhos que esses ficheiros têm em comum, e que atualizasse essa informação no ficheiro `frontmatter.yaml` respetivo (ou então que criasse o ficheiro `frontmatter.yaml` se necessário).

Foi assim que o meu pequeno project [YAMLUtils] nasceu. O script [`yamlutils.py`][yamlutils.py] aceita um caminho para uma pasta como argumento na linha de comandos (e, opcionalmente, o argumento `-r` para indicar que devemos atravessar os diretórios recursivamente) e depois faz exatamente aquilo que descrevi, juntando os cabeçalhos YAML sempre que possível. Eu usei-o no meu blogue com o comando `python yamlutils.py pages/ -r` e podem ver o que o script fez [neste commit](https://github.com/RojerGS/mathspp/commit/7ba80b086d6987ed819c872432ef1eafc1f1b023). Imaginem ter de fazer todas essas alterações à mão!

Mas especificamente, o meu script vai olhar para a pasta que indicares (e depois navega recursivamente se o argumento `-r` for usado) e depois, para cada pasta:

 - procura todos os ficheiros cujo nome coincida com o padrão `*.*.md`, que o script assume ser `ficheiro.lingua.md`;
 - um a um, abre esses ficheiros `.md`, extrai os cabeçalhos YAML de entre os `---` e determina os cabeçalhos em comum entre todas as páginas;
 - tenta abrir um ficheiro `frontmatter.yaml` ou `frontmatter.yml` nessa pasta (dando prioridade à extensão `.yaml`) e carrega os cabeçalhos YAML que lá encontre;
 - atualiza os cabeçalhos que já estavam presentes no ficheiro `frontmatter` (se esse ficheiro existir) e depois escreve a versão atualizada no mesmo ficheiro, ou então cria um ficheiro `frontmatter.yaml` se necessário;
 - volta a visitar os ficheiros `*.*.md`, removendo todos os cabeçalhos que já estejam específicados no ficheiro `frontmatter`.


### Valeu a pena o trabalho?

Sim.

Demorei cerca de duas horas para programar o script todo e testá-lo, e mais uns dez minutos para corrigir um problema irritante que só descobri quando tentei aplicar o script no meu blogue (cf. [esta][bug-1] e [esta][bug-2] correções às utilizações erradas). Fazer este trabalho todo à mão teria demorado mais de duas horas de certeza. Para além disso, agora tenho um script que posso melhorar mais tarde para fazer gestão de outras coisas relacionadas com YAML e também programei um pouco (que é sempre divertido!) em vez de andar a copiar e a colar cabeçalhos YAML de um lado para o outro.

Faz-me saber que tarefas é que Python já te ajudou a automatizar na secção de comentários em baixo!

[Grav]: https://getgrav.org/
[YAML]: https://en.wikipedia.org/wiki/YAML
[xkcd]: https://xkcd.com
[yamlutils]: https://github.com/RojerGS/projects/tree/master/yamlutils
[yamlutils-post]: https://github.com/RojerGS/mathspp/tree/master/pages/02.blog/yamlutils
[yamlutils.py]: https://github.com/RojerGS/projects/tree/master/yamlutils/yamlutils.py
[bug-1]: https://github.com/RojerGS/mathspp/commit/6ac01f412bdd099eb673201689d89ea77d0370d0
[bug-2]: https://github.com/RojerGS/mathspp/commit/e97dbad13ffc6009d1160b78a83cab467b42f1ca