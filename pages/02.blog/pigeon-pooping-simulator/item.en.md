---
title: Pigeon Pooping Simulator
---

This blog post has a single purpose, which is to show you the weird game I made, inspired by Flappy Bird and my crazy English teacher.

===

![Gameplay screenshot](simulator_screenshot.png)

The inspiration for this game came after an English class in which my English teacher jokingly talked about a pigeon simulator game. Of course I took it seriously and a couple
of lessons later I had this game working: a game where you just fly up and down, hitting SPACE to try and poop on the heads of the people you fly past.

The way the game works is pretty simple: use the spacebar to poop on top of the people you fly past. Hitting **Q** will exit the game and on the first screen you see, when you
start the game, you can use the **+** and **-** keys to change the difficulty (which relates to the speed at which the pigeon flies).

The goal is to hit as many people as possible, knowing that you can't poop _constantly_. There is a little - intentional - delay. After flying and pooping for 60 seconds
you are greeted by a screen with some stats, namely the percentage of people you managed to hit and the percentage of poops that hit / missed people.

The code was written Python 3 and pygame and the code + images can be found in [this GitHub repo](https://github.com/RodrigoGiraoSerrao/minigames/tree/master/pigeon-simulator). A windows executable can also be
found inside [this Drive folder](https://drive.google.com/open?id=0ByBeLS6ciLYVWElhc2dZdFc1Ykk). In order to be able to run the executable, the folder must be left as-is.