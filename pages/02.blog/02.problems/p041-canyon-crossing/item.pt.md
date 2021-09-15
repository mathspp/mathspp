---
title: "Problema #041 – travessia"
metadata:
    description: "Descobre por que ordem é que 4 amigos têm de atravessar uma ponte frágil de madeira."
---

Três números diferentes são atribuídos a três amigos.
Consegues descobrir quem recebeu que número?

===

![](thumbnail.png "Fotografia de Danika Perkinson no Unsplash")

# Enunciado do problema

4 amigos estão a caminhar num bosque, à noite, quando chegam a uma
ponte de madeira que têm de atravessar.

Eles decidem que é melhor só passarem 2 de cada vez, porque a ponte
parece mesmo frágil.
Para além do mais, a ponte está um pouco esburacada,
por isso decidem que o mais seguro é se atravessarem sempre com luz
a iluminar o caminho.
Como os 4 amigos só têm uma lanterna, rapidamente percebem que sempre
que duas pessoas passam para o lado de lá, alguém terá de voltar
com a lanterna, o que os vai fazer perder algum tempo...

Os 4 amigos querem passar a ponte da forma mais eficiente possível.
Como é que o podem fazer, se eles demoram 1, 2, 5 e 10 minutos a atravessar?


!!! Pensa um pouco!

Se precisares de clarificar alguma coisa, não hesites em perguntar na secção de comentários em baixo.


# Submissões

Parabéns a todos os que conseguiram resolver o problema e,
em particular, aos que me enviaram as suas soluções:

 - Jairo, Brasil;
 - Martin J., República Checa;
 - David H., Taiwan;
 - Gerard M., Irlanda;

Junta-te à comunidade e envia-me a tua solução por [email]!


# Solução

Se usares um método ganancioso, a resposta que vais obter é 19 minutos.

_No entanto_, há uma maneira de programar as travessias que permite
completar as viagens todas em apenas 17 minutos.
Consegues descobrir como o fazer?

Eu vou explicar como se faz,
mas para facilitar a minha própria vida,
vou referir-me às pessoas pelo número de minutos que demoram a atravessar a ponte.

 - 1 & 2 atravessam (**+2**);
 - 1 volta (**+1**);
 - 5 & 10 atravessam (**+10**);
 - 2 volta (**+2**); e
 - 1 & 2 atravessam de novo (**+2**).

Isto só demora 17 minutos!
(Há outra combinação semelhante que também só demora 17 minutos,
se o 2 voltar sozinho primeiro e o 1 depois.)

Tendo encontrado esta travessia que demora 17 minutos,
como é que podemos ter a certeza que não há nenhuma alternativa
mais rápida?
Podemos testar todas as possíveis combinações de travessias
individuais, e ver se todas demoram 17 minutos ou mais.
Também podemos raciocinar sobre o problema,
e concluir que independemente do que fizermos,
acabamos sempre por precisar de 17 minutos ou mais.

Vamos usar a segunda metodologia, em que raciocinamos sobre o problema.

Porque precisamos que a lanterna esteja sempre presente
nas travessias individuais, vemos que a ponte tem de ser atravessada
um total de 5 vezes.
Vamos usar `o` para representar as pessoas, `x` para a lanterna
e `|` para a ponte:

 1. duas pessoas atravessam, e ficamos com `o o | o o x`;
 2. uma pessoa regressa com a lanterna: `o o o x | o`;
 3. duas pessoas atravessam: `o | o o o x`;
 4. uma pessoa regressa com a lanterna: `o o x | o o`; e
 5. as duas pessoas que faltam, atravessam: `| o o o o x`.

A pessoa 10 não pode ir e voltar, porque isso tomaria logo 20 minutos
do nosso tempo.
Assim, concluímos que a pessoa 10 só atravessa uma vez.

Agora, vamos raciocinar sobre o momento em que a pessoa 5 atravessa.
Das duas, uma: ou a pessoa 5 atravessa com a pessoa 10, ou não.

Se a pessoa 5 _não_ atravessar com a pessoa 10,
então as duas travessias individuais já demoram 15 minutos.
Ainda faltam 3 travessias individuais e cada uma demora _pelo menos_
1 minuto, o que significa que a travessia completa demoraria
_pelo menos_ 18 minutos, que é mais lento que a travessia de 17
minutos que encontrámos.

Isto significa que a travessia mais rápida de todas é uma travessia
em que as pessoas 5 e 10 atravessam juntas, já que:

 - sabemos que há uma travessia de 17 minutos em que elas atravessam juntas; e
 - se elas atravessarem separadamente, a travessia total demora pelo menos 18 minutos.

Agora vamos pensar no momento em que as pessoas 5 e 10 atravessam.

As pessoas 5 e 10 podem atravessar na travessia 1, 3 ou 5.
Se a pessoa 5 voltar para trás em algum momento,
então vamos demorar imenso tempo de novo:

 - 10 minutos quando as pessoas 5 e 10 atravessam juntas; e
 - 5 minutos quando a pessoa 5 voltar.

Ficam a faltar três travessias individuais, e vemos que tudo junto
já vai ultrapassar os 17 minutos.
Assim, concluímos que as pessoas 5 & 10 atravessam juntas _e_
que tanto uma como outra só atravessam a ponte _uma vez_.

Isto significa que elas não podem atravessar na travessia 1,
já que uma das duas primeiras pessoas a atravessar tem de voltar para trás.

Isto também significa que elas não podem atravessar juntas na travessia 5,
porque isso seria tarde demais:
depois da travessia 3, três pessoas diferentes já atravessaram,
o que significa que uma das pessoas 5 ou 10 já teria de ter atravessado
a ponte, que é algo que não pode acontecer.

Logo, as pessoas 5 e 10 atravessam juntas na travessia 3.

Para isso ser possível, as pessoas 1 e 2 atravessam juntas primeiro,
e depois uma das duas volta para trás.

Depois, para a travessia 3, as pessoas 5 e 10 atravessam a ponte.
De seguida, para a travessia 4, alguém tem de voltar com a lanterna.
Esse alguém não será nem a pessoa 5 nem a 10, logo é a _outra_ pessoa
que foi para aquele lado da ponte na travessia 1.
Se a pessoa 1 foi a pessoa que voltou para trás primeiro,
então agora voltaria a pessoa 2.
Se a pessoa 2 foi a pessoa que voltou para trás primeiro,
então agora voltaria a pessoa 1.

Em qualquer um dos casos, vemos que tanto a pessoa 1 como a 2 têm
de voltar para trás sozinhas, nas travessias 2 e 4.
No fim de tudo, voltam a atravessar juntas na travessia 5.

Isto mostra que a melhor combinação que podemos fazer é a que apresentei
no início, e que demora 17 minutos.

Isto fez sentido?
Se tiveres questões, coloca-as na secção dos comentários!


<!-- v -->
Não te esqueças de [subscrever a newsletter][subscribe] para receberes os problemas diretamente na tua caixa de correio,
e deixa a tua reação a este problema em baixo.
<!-- ^ -->

[email]: mailto:rodrigo@mathspp.com?subject=Resposta%20para%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: https://mathspp.com/subscribe
