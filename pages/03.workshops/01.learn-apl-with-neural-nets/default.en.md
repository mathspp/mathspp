---
menu: Learn APL w/ neural nets
metadata:
    description: This workshop teaches you both APL and how to build neural networks from scratch!
title: Learn APL with neural networks
---

# Learn APL with neural networks

! This workshop is based off of content owned by [Dyalog Ltd.][Dyalog] and licensed under a [CC BY-NC-SA 4.0 International][license] license.
!
! The contents of this page and of the workshop are provided as-is and with no warranties whatsoever.

## Aim

The aim of this workshop is to introduce people to the [APL] programming language, with the first contact geared towards building a neural network from scratch. It helps if the audience has some programming knowledge (in no programming language in particular) and has heard of neural networks a bit, but that is not necessary.

There are two reasons why I use neural networks to introduce APL to newcomers in this workshop:

 - for one, it is better to learn a language and build something with it instead of just going through all the built-ins that APL has to offer;
 - secondly, neural networks can be built on top of matrix algebra and such operations lend themselves naturally to APL.


## Agenda

The objective of the workshop is to make incremental improvements to a namespace that eventually contains enough functionality to create a neural network that can be trained on the MNIST data (`mnistdata.rar`) and classify handwritten digits.

That is, the neural network will receive input images like the ones below and should be able to identify the digit in the image.

![MNIST image examples](MnistExamples.png "Image by Josef Steppan, CC BY-SA 4.0 license")

For that matter, here is the standard order in which things get done in the workshop (this lines up almost perfectly with the order in which objects appear in `NeuralNets.apln`):

 1. defining a dfn and writing glyphs in APL;
 2. go over the *intuitive* basics on neural networks;
 3. build an array of arbitrary shape with normally-distributed real numbers;
 4. build the random parameters for the network:
    - build the weight matrices;
    - build the bias column vectors;
 5. build an activation function (e.g. leaky ReLU);
 6. implement the forward pass;
 7. implement loss function;
 8. implement derivatives for backprop algorithm:
    - for the loss function;
    - for the activation function;
 9. go over the details of the backpropagation algorithm;
 10. implement the backwards pass;
 11. check the backward pass is in the correct direction;
 12. read MNIST data from a file;
 13. display some data;
 14. train the network on the MNIST training data;
 15. test the network on the MNIST testing data;

The number of people attending the workshop, their previous knowledge of APL and neural networks and other related factors impacts how much we manage to accomplish.

If the list is exhausted within the time allotted for the workshop, here's a couple of follow-ups with little opportunity cost to start:

 - writing a small function that takes a network and prints some digits and their classifications;
 - changing the loss function to something more appropriate to the task at hand, e.g. the cross-entropy measure;
 - learning about student-teacher models;


## Takeaways

By the end of the workshop, attendees will have a (close to finished) neural network written in a programming language they probably never dealt with, APL.

Attendees will have dabbled for the first time with a purely array-oriented programming language and built a popular, modern-day machine learning model from scratch.

Finally, their own implementation of a neural network can be trained in less than 2 minutes to recognise handwritten digits with 89% accuracy (timed on my laptop).
Here is an example of some drawn digits and the neural network's guesses.

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

## Additional resources

Other than the code with the reference implementation (available in [this GitHub repository][workshops-gh]), here are some links that might be useful:

 - A [pdf version][mdapl-pdf] of the "Mastering Dyalog APL" book, and its [online version][mdapl-online] (the online version is a work in progress);
 - [The APL Orchard][apl-orchard] is a chat room where people from any APL level get together to talk about, teach and learn APL;
 - The [APL Cart][aplcart] is the go-to place whenever you have a question of the form "How do I ___ in APL?";
 - The [APL wiki][aplwiki] has articles on everything that has to do with APL.

[APL]: https://aplwiki.com
[license]: https://creativecommons.org/licenses/by-nc-sa/4.0/
[Dyalog]: https://dyalog.com
[workshops-gh]: https://github.com/RojerGS/workshops
[mdapl-pdf]: https://www.dyalog.com/mastering-dyalog-apl.htm
[mdapl-online]: https://rojergs.github.io/MDAPL
[apl-orchard]: https://chat.stackexchange.com/rooms/52405/the-apl-orchard
[aplcart]: https://aplcart.info/
[aplwiki]: https://aplwiki.com
