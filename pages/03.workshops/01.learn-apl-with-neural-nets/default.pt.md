---
menu: Aprende APL com redes neuronais
metadata:
    description: Neste workshop aprendes APL e ainda aprendes a fazer uma rede neuronal de raíz!
title: Aprende APL com redes neuronais
---

# Aprende APL com redes neuronais

! Este workshop baseia-se em conteúdo da [Dyalog Ltd.][Dyalog] e está licenciado sob uma licença [CC BY-NC-SA 4.0 International][license].
!
! Os conteúdos desta página e do workshop são providenciados sem qualquer tipo de garantia.

!!! Este workshop inspirou [esta série de vídeos no YouTube][yt-series] para aprender APL com redes neuronais. (A série é em inglês.)

## Objetivo

O objetivo deste workshop é introduzir as pessoas a programação com [APL], sendo este primeiro contacto direcionado para a implementação de uma rede neuronal de raíz. É útil que a audiência tenha alguns conhecimentos de programação (numa linguagem de programação qualquer) e que já tenha ouvido falar de redes neuronais, mas isto não é um requisito inegociável.

Há duas razões pelas quais eu uso redes neuronais para introduzir APL aos novatos que vêm ao workshop:

 - por um lado, é muito melhor aprender uma linguagem quando se constrói algo com ela, ao invés de sermos bombardeados com uma lista exaustiva de todas as funcionalidades que a linguagem tem;
 - por outro lado, redes neuronais podem ser implementadas fazendo uso de álgebra matricial e APL é excecional nesse tipo de cálculos.


## Teor do workshop

O objetivo do workshop é implementar um "namespace" de forma progressiva, para que este tenha funcionalidades suficientes para criar uma rede neuronal que possa ser treinada nos dados MNIST (`mnistdata.rar`) e que aprenda a classificar dígitos desenhados à mão.

Ou seja, a rede neuronal vai receber imagens como as que se seguem e deve ser capaz de identificar o dígito presente.

![exemploes de imagens dos dados MNIST](MnistExamples.png "Imagem de Josef Steppan, licença CC BY-SA 4.0")

Neste sentido, segue aqui a ordem aproximada pela qual eu abordo as pequenas componentes que têm de ser implementadas (esta ordem é muito parecida com a ordem dos objetos definidos no ficheiro `NeuralNets.apln`):

 1. definir uma dfn e escrever os símbolos de APL;
 2. ganhar intuição sobre o funcionamento de redes neuronais simples;
 3. construir um tensor arbitrário com números distribuídos de forma normal (no sentido estatístico);
 4. construir os parâmetros aleatórios da rede:
    - as matrizes de pesos;
    - os vetores coluna com os "bias";
 5. construir uma função de ativação (e.g. leaky ReLU);
 6. implementar o "forward pass";
 7. implementar a função objetivo;
 8. implementar as derivadas para o algoritmo de "backpropagation":
    - derivada da função objetivo;
    - derivada da função de ativação;
 9. rever os detalhes do algoritmo de "backpropagation";
 10. implementar o dito algoritmo;
 11. verificar que as derivadas calculadas apontam na direção certa;
 12. ler os dados MNIST dos ficheiros;
 13. visualizar os dados;
 14. treinar a rede nos dados;
 15. testar a rede nos dados;

O tamanho da audiência, os seus conhecimentos prévios de APL e sobre redes neuronais, bem como outros fatores semelhantes vão impactar o quanto se consegue fazer.

Se tudo nesta lista for feito dentro do tempo previsto, seguem algumas possíveis direções para o workshop que têm custos de oportunidade reduzidos:

 - escrever uma pequena função que mostre alguns dígitos, bem como os palpites da rede;
 - alterar a função objetivo para algo que faça mais sentido nesta tarefa de classificação, e.g. cross-entropy;
 - implementar um modelo aluno-professor;


## Conclusões

No fim do workshop os participantes terão uma implementação de uma rede neuronal (ou estarão perto disso) escrita numa linguagem com que nunca tinham trabalhado, APL.

Os participantes terão usado pela primeira vez uma linguagem que é puramente orientada para tensores e tê-la-ão usado para implementar de raíz um modelo de aprendizagem automática que é moderno e popular.

Finalmente, a sua implementação de uma rede neuronal pode ser treinada em menos de 2 minutos para reconhecer dígitos com uma precisão de 89% (tempo medido no meu portátil). Aqui está um exemplo de alguns dígitos desenhados à mão, junto dos palpites da rede treinada:

```APL
               @@                                                                                   
              @@@                                                                                   
             @@@                                   @@@                                              
            @@@@                                @@@@@@@@                                            
           @@@@       @                       @@@@@@@@@@            @@                @       @@    
           @@@       @@@@@@@@                @@@@@@@  @@@       @@@@@@@              @@       @@    
          @@@        @@@@@@@@@@@@@           @@@@@   @@@        @@@@@@@@            @@@      @@@    
         @@@@         @@@@@@@@@@@@@          @@@     @@@      @@@@    @@@          @@@      @@@     
         @@@            @@@@@@@@@@@                  @@@      @@@      @@@        @@@       @@@     
        @@@                   @@@@                  @@@@     @@@        @@       @@@        @@      
        @@@   @@@@           @@@@@                 @@@       @@@        @@@     @@@@       @@@      
       @@@   @@@@@@          @@@@@                @@@@      @@@        @@@@     @@@@       @@@      
       @@@  @@@@@@@          @@@@                 @@@@      @@@@    @@@@@@@      @@@@@    @@@       
       @@@ @@@@  @@         @@@@@                @@@@@       @@@@@@@@@@@@@        @@@@@@@@@@        
       @@ @@@@   @@         @@@@                @@@@@@@@      @@@@@@@@ @@@          @@@@@@@@        
       @@@@@@   @@@        @@@@@             @@@@@@@@@@@@@      @@     @@@               @@@        
       @@@@@@ @@@@@        @@@@@            @@@@@@@@  @@@@@@           @@                @@@        
        @@@@@@@@@@         @@@@            @@@@@@@      @@@@          @@@                @@         
        @@@@@@@@           @@@@           @@@@@@@         @@          @@                @@@         
          @@@@             @@@@          @@@@@@                      @@@                @@@         
                           @@@@          @@@@                        @@@                @@          
                           @@@@          @@@                         @@@               @@@          
                           @@@@                                      @@@               @@@          
                           @@@                                       @@@               @@           
                                                                                                    
                                                                                                    
guessing 6          guessing 7          guessing 2          guessing 9          guessing 4          
```

## Feedback

Aqui tens algumas opiniões de pessoas que foram ao workshop:

 > “*Acho incrível o que nós conseguimos fazer em apenas 2 horas [...] No final senti-me cansado mas satisfeito e fascinado com o que fiz e aprendi.*”  &mdash; João Afonso

<!-- -->

 > “*Esta* hands-on *approach de aprender uma linguagem de programação nova torna o processo de aprendizagem mais interessante do que o habitual.*”  &mdash; Carlos

<!-- -->

 > “*Gostei muito da maneira simples e acessível como foi explicado.*”  &mdash; Anónim@


## Recursos adicionais

Para além do código de referência, que está disponível [neste repositório do GitHub][workshops-gh], os seguintes links podem ser úteis:

 - [versão pdf][mdapl-pdf] do livro "Mastering Dyalog APL" e [versão online][mdapl-online] (a versão online é um trabalho em curso);
 - O [Pomar de APL][apl-orchard] é uma sala de chat onde pessoas de vários níveis se juntam para ensinar, aprender e discutir APL;
 - O [APL Cart][aplcart] é um site bestial para responder a perguntas do tipo "Como é que faço ___ com APL?";
 - A [wiki de APL][aplwiki] contém artigos interessantes sobre tudo o que diz respeito a APL.

[APL]: https://aplwiki.com
[license]: https://creativecommons.org/licenses/by-nc-sa/4.0/
[Dyalog]: https://dyalog.com
[workshops-gh]: https://github.com/RojerGS/workshops
[mdapl-pdf]: https://www.dyalog.com/mastering-dyalog-apl.htm
[mdapl-online]: https://rojergs.github.io/MDAPL
[apl-orchard]: https://chat.stackexchange.com/rooms/52405/the-apl-orchard
[aplcart]: https://aplcart.info/
[aplwiki]: https://aplwiki.com
[yt-series]: https://www.youtube.com/playlist?list=PLgTqamKi1MS3p-O0QAgjv5vt4NY5OgpiM
