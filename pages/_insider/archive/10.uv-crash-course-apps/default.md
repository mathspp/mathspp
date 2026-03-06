---
date: 23-02-2026 18:29
metadata:
    author: Rodrigo Girão Serrão
    description: "Learn how to use uv to create apps and install them globally on your computer as tools."
    og:image: "https://mathspp.com/insider/archive/uv-crash-course-apps/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/uv-crash-course-apps/thumbnail.webp"
title: "uv crash course: apps"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 uv crash course: apps

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## You’ve heard me talk about the Intermediate Python course

It’s back, but this time it’s **different**.

Instead of compressing everything into 5 intense days, the course now runs **over 5 weeks**.

That change alone makes this cohort far more powerful.

Here’s why:

When we ran it as a 5-day sprint, the feedback was clear: the material was excellent, but it was dense.

People learned a lot, and fast, but didn’t always have the breathing room to properly digest it.

Now, you get:

- More time to practice.
- More time to ask questions.
- More time to reflect and integrate.
- More time to apply what you learn to your own code.


Instead of cramming advanced concepts into a single week, you’ll build them progressively.

You’ll still get:

- Live, interactive sessions.
- Carefully designed exercises.
- Reference materials you can revisit anytime.
- Lifetime access to recordings.
- A cohort of serious Python developers to learn alongside.
- Free access to join future cohorts.


But now, you’ll actually have the space to _absorb_ it all.

This course is for you if:

- You already use Python comfortably.
- You’ve written non-trivial programs.
- You want to move from “I can code” to “I deeply understand what I’m doing”.
- You want confidence with generators, decorators, project structure, typing, and more.
- You’re ready to operate at a higher level.


By the end of the 5 weeks, you won’t just recognise intermediate concepts — you’ll be able to use them naturally in real projects.

This isn’t passive learning.

It’s applied, hands-on, and interactive.

That’s why past students give this course an average rating of **4.91 out of 5**.

If you’ve been waiting for the right moment to level up properly, this is it!

See the full details and save your seat here:

[Secure your spot in the cohort](https://mathspp.com/courses/intermediate-python-course)

Seats are limited so I can keep the cohort interactive and high-quality.

I hope to see you there!

## A little teaser about apps

During one of the weeks you’ll learn how to create and manage Python projects using uv.

In particular, you’re going to create, build, and publish, a Python project.

What does that mean?

It means that you will upload a Python project to PyPI, I will install it on my computer, and I will run it to make sure it’s working!

It’s always a super fun moment when we all play around with each other’s projects.

## Creating a globally available tool

In this email I'll show you something similar.

You'll learn how to create Python tools that are globally available in your own computer.

For example, I have a tool called `truchet` that I use to generate random profile pictures like this one:

![A pink profile image with pleasing abstract curved patterns.](_example.webp)

It's not a script that I have to run with the full path like `uv run path/to/script/truchet.py`.

And it doesn't live inside a specific virtual environment that I have to navigate to and activate.

No, I just run `truchet output.png` and it generates a picture.

This is what I use whenever I need to generate a [placeholder profile picture for your testimonials](https://mathspp.com/testimonials).

You can create these tools with uv.

## Setting up the project

The first thing you have to do is create [an app project with uv](https://mathspp.com/insider/archive/uv-crash-course-app-projects), just like you learned last week.

You want the project to be an app project and you want it to be packageable, so you run the command

```bash
$ uv init --app --package example_app
Initialized project `example-app` at `/Users/rodrigogs/Documents/tmp/example_app`
```

## Running your app's command

When you create an app project that is packageable, uv automatically sets up a command for you.

And the command should have the same name as the app.

To see what this means, try running

```bash
$ uv run example-app
Hello from example-app!
```

**Note**: the app folder is `example_app` with an underscore but the command is `example-app` with a hyphen!

You get the hello message even though there's no code at the root of your repo.

However, if you look in your project, you will find a `src` folder with the file `src/example_app/__init__.py` in there.

This is the contents of the file `__init__.py`:

```py
def main() -> None:
    print("Hello from example-app!")
```

You didn't set any of this up, uv did.

So, how does uv know to run the function `main` when you use the command `example-app`?

## App entry points

When you created your project, uv added something special to your file `pyproject.toml`: an **entry point**.

Here's the entry point that uv created for you:

```toml
[project.scripts]
example-app = "example_app:main"
```

An entry point creates a correspondence between a command and a function in your code.

If you change the `example-app` to the left of the equals sign, the name of the command changes.

For example, edit and save `pyproject.toml` to include this:

```toml
[project.scripts]
my-command = "example_app:main"
```

Now, if you run

```bash
$ uv run my-command
Hello from example-app!
```

You can see the command name changed.

## Creating a new entry point

You can create as many entry points as you want.

Suppose you create a file `src/example_app/core.py`:

```py
import time

def important_function():
    print("Calculating important stuff...")
    time.sleep(2)
    print("Done!")
```

Now, go to `pyproject.toml` and add this important function as a new entry point:

```toml
[project.scripts]
my-command = "example_app:main"
important = "example_app.core:important_function"
```

The command `important` will now run the function `important_function` from the module `example_app.core`:

```bash
$ uv run important
Calculating important stuff...
Done!
```

## Installing your app globally

Now that you have developed your important and useful app, you can install it to make it globally available in your computer!

For that, you'll [install your app as a tool](https://mathspp.com/insider/archive/uv-crash-course-tools).

Make sure you're at the root of your `example_app` project and run:

```bash
$ uv tool install -e .
Resolved 1 package in 0.66ms
Installed 1 package in 2ms
 + example-app==0.1.0 (from file:///Users/rodrigogs/Documents/tmp/example_app)
Installed 2 executables: important, my-command
```

This will build your package and install it in your own computer.

Open a new terminal and type `important` or `my-command`.

Either should work!

And if you run `uv tool list`, you should see the example app in there.

For me, this is what listing all tools gives me:

```bash
uv tool list
...
example-app v0.1.0
- important
- my-command
...
```

By using `uv tool install .` you are telling uv to install the tool of the current directory.

The option `-e` makes it an **editable install**, which means that whenever you change the source code, the installed tool will reflect those changes immediately.

You don't have to use `-e`, but if you make changes to the app you'll have to update its version and install the new version in your system.

## Conclusion

Isn't this _super cool_?

I think it's surprising how easy and convenient it is to create and deploy these little Python tools in my system and for others by publishing them.

uv really makes this stuff a breeze.

That's why you'll learn all about uv in the [Intermediate Python course](https://mathspp.com/courses/intermediate-python-course) that is starting next week.

Like I said, seats are limited, so make sure to [secure your spot in the cohort](https://mathspp.com/courses/intermediate-python-course) now!

See you there!

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_intro'} ) } %}
