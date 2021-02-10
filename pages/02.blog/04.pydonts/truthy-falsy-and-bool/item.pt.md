---
metadata:
    description: ""
title: "Truthy, Falsy e bool"
---

Todos os objetos em Python podem ser usados em expressões que deviam
resultar em valores Booleanos, tais como as condições de instruções
`if` ou `while`.
Há vários objetos que são Falsy (ou seja, que são interpretados
como `False`) quando estão “vazios” ou quando “não têm valor”,
e caso contrário são Truthy (interpretados como `True`).
Também podes definir este comportamento para os teus objetos
se implementares o método dunder `__bool__`.

===

![Código Python contrastando alguns ifs.](thumbnail.png)

(Se és novo aqui e não sabes o que é uma Pydon't, então talvez queiras começar por
ler a [Proclamação das Pydon'ts][manifesto].))
Infelizmente, ainda não traduzi este artigo :/
Podes lê-lo em inglês [aqui](https://mathspp.com/blog/pydonts/truthy-falsy-and-bool).

[subscribe]: https://mathspp.com/subscribe
[manifesto]: /blog/pydonts/pydont-manifesto
[pep3140]: https://www.python.org/dev/peps/pep-3140/
