---
metadata:
    description: "Correspondência estrutural é uma funcionalidade que vai ser introduzida com Python 3.10, e este artigo mostra como a usar para escrever código elegante com a instrução `match`."
show_call_to_action: false
title: "Tutorial sobre correspondência estrutural | Pydon't 🐍"
---

Correspondência estrutural é uma funcionalidade que vai ser introduzida
com Python 3.10, e este artigo mostra como a usar para
escrever código elegante com a instrução `match`.

===

![Código Python com correspondências estruturais.](thumbnail.png)

(Se és novo aqui e não sabes o que é uma Pydon't, então talvez queiras começar por
ler a [Proclamação das Pydon'ts][manifesto].)

Infelizmente, para poupar algum tempo, ainda não traduzi este artigo para português...
Hei de o fazer eventualmente...
Se quiseres, deixa um comentário em baixo a pedir que eu traduza o artigo ASAP ou [submete um PR][pr] com a tua tradução.


[pr]: https://github.com/mathspp/mathspp/blob/master/pages/02.blog/04.pydonts/structural-pattern-matching-tutorial/item.pt.md
[pizza]: https://buymeacoffee.com/mathspp
[subscribe]: https://mathspp.com/subscribe
[manifesto]: /blog/pydonts/pydont-manifesto
[pep-622]: https://www.python.org/dev/peps/pep-0622/
[pep-634]: https://www.python.org/dev/peps/pep-0634/
[pep-635]: https://www.python.org/dev/peps/pep-0635/
[pep-636]: https://www.python.org/dev/peps/pep-0636/
[py-pre-re]: https://www.python.org/download/pre-releases/
[pydont-du]: https://mathspp.com/blog/pydonts/deep-unpacking
[pydont-sa]: https://mathspp.com/blog/pydonts/unpacking-with-starred-assignments
[pydont-str-repr]: https://mathspp.com/blog/pydonts/str-and-repr
