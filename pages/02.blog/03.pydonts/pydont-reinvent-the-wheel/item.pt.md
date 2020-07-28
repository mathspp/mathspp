---
metadata:
  description: Neste py-don't discuto uma opção viável a teres de estar sempre a reinventar
    a roda.
title: Py-don't reinventes a roda a torto e a direito
---

Supõe que estás a escrever um script em Python e a certa altura precisas do nome do diretório em que o próprio script está. Arranjarias uma solução como esta?

```py
import re

r = r"(.*/)?(\w+)/\w*[.]\w+"
match = re.match(r, __file__)
if match:
    print(match.group(2))
```

Esta pode não ser a melhor solução para ti... Por exemplo, esta solução nem funciona com caminhos Windows que contenham `\`...

===

![two wooden wheels against a wall](wheel.jpg "Photo by Jon Cartagena on Unsplash")

Este artigo vai ter um pouco menos de código do que os _py-don't_ usuais e vai ter um pouco mais de palavras... Para além disso, este artigo vai ser aplicável a uma grande quantidade de linguagens de programação, e não só a Python... Hoje vou falar de soluções que são subóptimas porque foram programadas por _nós_.

De facto, voltando ao exemplo inicial, eu diria que uma solução mais _pythonica_ seria:

```py
from pathlib import Path

path = Path(__file__)
print(path.parts[-2])
```

Relembra-te que [_simples é melhor que complexo_][zen-of-python "Simple is better than complex."] e este `import` parece-me bastante simples.

Python vem com uma quantidade _enorme_ de bibliotecas que podes explorar seguindo [este link][py3.8 docs], e estas vêm todas por defeito com a tua instalação de Python, não é preciso instalar mais nada. Há muitas tarefas usuais que já estão implementadas para nossa comodidade, para que não tenhamos de passar os nossos dias a reinventar a roda a torto e a direito.

Não me interpretes mal, eu acho que "reinventar a roda" pode ser um exercício de aprendizagem excelente e muito divertido! Mas nem sempre é a _melhor_ opção quando temos de cumprir um prazo apertado ou quando precisamos de escrever código robusto e fácil de manter. Alavancarmos tudo o que está à nossa disposição na biblioteca standard do Python dá-nos mais tempo para nos focarmos naquilo que temos _mesmo_ de ser nós a escrever.

Eu já dei por mim a reinventar a roda em todo o tipo de pequenas tarefas... Às vezes só me lembro de pesquisar por uma biblioteca que me ajude depois de gastar bastante tempo de volta daquela pequena tarefa... E acho que muitos de nós poderíamos benefeciar de todo o código que já existe por esse mundo fora... Para não falar de tudo o resto que não vem com o Python por defeito e a que podemos aceder, por exemplo através do [PyPI].

Se calhar parece-te que usar a biblioteca standard do Python ou bibliotecas instaladas à parte conspurca um pouco os teus programas, mas também te deves lembrar que [_ser prático é melhor que ser puro_][zen-of-python "praticality beats purity."].

Por esta altura já deves ter percebido a mensagem principal deste _py-don't_, deixo apenas mais um par de situações que ilustram alturas em que é bom/mau reinventar a roda.

Vamos falar de programas que recebem argumentos através da linha de comandos. Escrever um programa em Python para interpretar esses argumentos é um exercício muito interessante! Mas se estivermos a escrever um outro programa que faz X ou Y e queremos que esse programa seja utilizável através da linha de comandos, qual das duas opções soa mais produtiva?

 - Começar com `import argparse` e [ler o `argparse` HOWTO][argparse howto] para prototipar rapidamente uma CLI para o teu programa;
 - Parar de trabalhar no teu programa importante para gastares não sei quanto tempo para escrever todo o código que vai interpretar os argumentos passados pela linha de comandos.

De modo semelhante, implementar um tipo de dados para matrizes numéricas com todas as funcionalidades expectáveis não é algo que se faça em quinze minutos e é um exercício de programação muito bom; até de álgebra linear, se implementares coisas como o método de eliminação de Gauss-Jordan ou a matriz inversa... _Mas_ se tiveres de entregar um relatório sobre um método numérico que estudaste numa aula (tal como eu já tive de fazer) eu recomendo veementemente `import numpy`, já que o `numpy` é rápido e estável e assim podes focar-te nas coisas que importam realmente para _esse_ projeto.

Concordas comigo? Faz-me saber na secção de comentários em baixo.

E não te esqueças de partilhar isto com todos os teus amigos que sabem/estão a aprender a programar em _qualquer_ linguagem!

[py3.8 docs]: https://docs.python.org/3/library/index.html
[argparse howto]: https://docs.python.org/3/howto/argparse.html
[PyPI]: https://pypi.org
[zen-of-python]: ../pydont-zen-of-python