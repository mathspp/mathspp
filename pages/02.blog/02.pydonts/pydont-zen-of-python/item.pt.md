---
title: "Py-don't desrespeites o Zen do Python"
date: 06-03-2020
slug: zen-of-python
taxonomy:
    category: blogpost
    tag: [pydont, programming, python]
---

[_Py-don'ts_][pydont] são anti-dicas para escrever bom código Python. Por vezes, aprender o que é bom não chega. Há que comparar as coisas boas com as más para aprender com o contraste!

===

![Captura de ecrã com o resultado de correr "import this".](import_this.png)

Daqui em diante vou tomar como minha responsabilidade mostrar-vos mau código Python, explicando não só porque é que é mau mas também como o melhorar.

As minhas anti-dicas não vão ser sobre algoritmos ou bibliotecas específicos. Vou mostrar código _feio_ para que aprendam a apreciar código elegante. Na matemática também há um fenómeno semelhante: algumas provas de teoremas são mais elegantes do que outras e, em geral, as provas mais elegantes são mais fáceis de entender e fazem mais sentido. O mesmo se aplica a código feio/elegante. Código elegante é mais fácil de entender e, por vezes, até é significamente mais eficiente!

Dito isto, onde estão definidos os standards de código elegante? Para o Python, basta escrever `import this` e somos presenteados com o _Zen of Python_, um conjunto de premissas que podemos e devemos seguir quando programamos:
 
```
The Zen of Python, by Tim Peters

Beautiful is better than ugly.
Explicit is better than implicit.
Simple is better than complex.
Complex is better than complicated.
Flat is better than nested.
Sparse is better than dense.
Readability counts.
Special cases aren't special enough to break the rules.
Although practicality beats purity.
Errors should never pass silently.
Unless explicitly silenced.
In the face of ambiguity, refuse the temptation to guess.
There should be one-- and preferably only one --obvious way to do it.
Although that way may not be obvious at first unless you're Dutch.
Now is better than never.
Although never is often better than *right* now.
If the implementation is hard to explain, it's a bad idea.
If the implementation is easy to explain, it may be a good idea.
Namespaces are one honking great idea -- let's do more of those!
```

O _Zen of Python_ foi escrito por [Tim Peters][tim-peters], um engenheiro informático que contribuiu bastante para o Python e para a comunidade do Python.

Pessoalmente acho que o _Zen of Python_ é fácil de interpretar, a menos da piada sobre pessoas holandesas* e a referência aos _namespaces_, uma particularidade do Python. À medida que os próximos [_py-don't_][pydont]'s forem publicados, vou contrariar o _Zen of Python_ repetidamente, portanto fiquem atentos!

Deixem um comentário se houver algum [_py-don't_][pydont] que queiram sugerir!

(*) Agora que penso nisso, isto pode ser uma referência a [Guido van Rossum][guido], o criador do Python. O Guido é holandês.

[tim-peters]: https://en.wikipedia.org/wiki/Tim_Peters_(software_engineer)
[guido]: https://en.wikipedia.org/wiki/Guido_van_Rossum
[pydont]: https://mathspp.com/blog/pydonts