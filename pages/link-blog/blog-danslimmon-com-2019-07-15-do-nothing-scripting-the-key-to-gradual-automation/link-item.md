---
author: Dan Slimmon
date: 17-02-2025 19:48
link: https://blog.danslimmon.com/2019/07/15/do-nothing-scripting-the-key-to-gradual-automation/
taxonomy:
    category: link
title: "Do-nothing scripting: the key to gradual automation"
via: https://learnbyexample.gumroad.com/l/learnbyexample-weekly?layout=profile
---

In this article, Dan defines “do-nothing scripting” and presents it as an excellent starting point to making it easier to automate boring, repetitive tasks that don't really add long-term value to your project or life.
The idea is simple: create a basic script that essentially just prompts the user to do each step of the boring repetitive task, and wait for user confirmation before showing the steps for the next task.

Here's an example for the task of creating [a testimonial on my website](/testimonials) based on what someone writes on social media:

<details markdown="1">
<summary><code>do-nothing-testimonial.py</code></details>

```py
def wait():
    input("Press Enter when done...")

print("Copy an existing testimonial as the boilerplate.")
wait()

print("Copy the text of the testimonial into the page.")
wait()

print("Does the review have an associated profile with a picture?")
has_pic = input("y/n >>> ").strip().casefold()

if has_pic.startswith("y"):
    print("Download the picture.")
    wait()
    print("Compress it with optimizt and put it in the page folder.")
    wait()
else:
    print("Generate a new picture.")
    wait()

print("Add & commit the new testimonial.")
```

</details>

This script is already valuable because it gives me all of the steps I need to follow and makes sure I don't forget anything.

Furthermore – and I feel like this is the greatest advantage – the fact that the script already exists means I can gradually replace sections with their automated versions instead of just printing instructions to follow.
