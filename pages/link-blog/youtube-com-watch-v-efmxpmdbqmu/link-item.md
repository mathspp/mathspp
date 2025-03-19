---
author: Alan Becker
date: 19-03-2025 20:50
link: https://www.youtube.com/watch?v=EFmxPMdBqmU
taxonomy:
    category: link
title: "Animation vs. Coding - YouTube"
# via:
---

In this brilliant video, Alan Becker shows us a world where a stick figure fights a computer through programming.
I can't do a great job at explaining what's happening other than the fact that the two characters are fighting and writing Python code to use as weapons and to manipulate the arena...

What I enjoyed the most was the attention to detail and the fact that we were shown the code and the code matched the animations quite faithfully.
For example, at one point, the computer creates a “weapon” that “fires” letters from the alphabet:

```py
import string as ammo
gun = list(ammo.ascii_uppercase)
print(gun.pop())
```

As the animation progresses and `gun.pop()` keeps getting called, you see the alphabet being fired back to front, and once the letter A is fired, the gun is empty and you hear the click of a gun without ammo.
Just wonderful!
