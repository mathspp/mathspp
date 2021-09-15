---
show_call_to_action: false
title: "Quando não usar correspondência estrutural | Pydon't"
---

Correspondência estrutural é uma funcionalidade que vai ser introduzida
com Python 3.10 e [o artigo anterior][pm-tutorial-pydont] mostrou
como a usar; este artigo mostra quais são os casos em que o `match`
não é a melhor alternativa.

===

![Código Python com correspondências estruturais.](thumbnail.png)

(Se és novo aqui e não sabes o que é uma Pydon't, então talvez queiras começar por
ler a [Proclamação das Pydon'ts][manifesto].)

Infelizmente, para poupar algum tempo, ainda não traduzi este artigo para português...
Hei de o fazer eventualmente...
Se quiseres, deixa um comentário em baixo a pedir que eu traduza o artigo ASAP ou [submete um PR][pr] com a tua tradução.


[pr]: https://github.com/mathspp/mathspp/blob/master/pages/02.blog/04.pydonts/structural-pattern-matching-anti-patterns/item.pt.md
[pizza]: https://buymeacoffee.com/mathspp
[subscribe]: https://mathspp.com/subscribe
[manifesto]: /blog/pydonts/pydont-manifesto
[pm-tutorial-pydont]: /blog/pydonts/pattern-matching-tutorial-for-pythonic-code
[pm-tutorial-pydont-options]: /blog/pydonts/pattern-matching-tutorial-for-pythonic-code#your-first-match-statement
[pm-tutorial-pydont-dict]: https://mathspp.com/blog/pydonts/pattern-matching-tutorial-for-pythonic-code#traversing-recursive-structures
[zp-pydont]: /blog/pydonts/pydont-disrespect-the-zen-of-python
[pydocs-getattr]: https://docs.python.org/3/library/functions.html#getattr
[pydocs-singledispatch]: https://docs.python.org/3/library/functools.html#functools.singledispatch
[pep-622]: https://www.python.org/dev/peps/pep-0622/
[pep-634]: https://www.python.org/dev/peps/pep-0634/
[pep-635]: https://www.python.org/dev/peps/pep-0635/
[pep-636]: https://www.python.org/dev/peps/pep-0636/
[py-pre-re]: https://www.python.org/download/pre-releases/
[gvanrossum-article]: https://gvanrossum.github.io/docs/PyPatternMatching.pdf
[collatz]: https://en.wikipedia.org/wiki/Collatz_conjecture
[rule-30]: https://www.wolframalpha.com/input/?i=rule+30
[rule-30-wiki]: https://en.wikipedia.org/wiki/Rule_30
[APL]: https://apl.wiki
[RGSPL]: https://github.com/RojerGS/RGSPL
