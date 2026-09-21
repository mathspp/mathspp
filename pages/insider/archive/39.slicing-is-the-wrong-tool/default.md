---
date: 21-09-2026 15:13
metadata:
    author: Rodrigo Girão Serrão
    description: "Learn when slicing is the wrong tool for the job."
    og:image: "https://mathspp.com/insider/archive/slicing-is-the-wrong-tool/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/slicing-is-the-wrong-tool/thumbnail.webp"
title: "Slicing is the wrong tool"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Slicing is the wrong tool

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## Checking the prefix

When [writing my own coding agent](https://mathspp.com/blog/write-a-coding-agent-from-first-principles), I wrote this:

```py
while True:
    prompt = input(" >> ")
    if prompt[:5] == "/exit":
        break
    ...
```

This checks the user input.

If the user typed something that starts with `"/exit"`, then the coding agent stops.

That's the _meaning_ of my code.

But what's actually written?

There's a slice and then an equality check.

You can say that this is an idiom: a piece of code that has a concrete meaning beyond just the concatenation of the actions it's taking.

In this case, the meaning is “does the input start with `"/exit"`.

And this idiom is so common that there's a string method for it:

```py
while True:
    prompt = input(" >> ")
    if prompt.startswith("/exit"):
        break
    ...
```

The string method `startswith` checks if the string on the left starts with the string passed as argument.

This is better than the slice because it's much more robust.

If I had miscounted the number of characters in `"/exit"`, the slice would never match the string `"/exit"` but I wouldn't get an exception to warn me.

I'd just have to find out eventually that the program wasn't exiting.

The method `startswith` removes this type of error entirely.

Also, you don't have to reason about the slice since the behaviour is abundantly clear from the method name.

## Multiple prefix checks

Another cool advantage of `startswith` is that it is a bit more flexible.

If you want to check for multiple prefixes, just pass them as a tuple:

```py
while True:
    prompt = input(" >> ")
    if prompt.startswith(("/exit", "/quit")):
        break
    ...
```

Now, we quit if the user types something that starts with `/exit` or `/quit`.

## Checking the suffix

Along the same lines, you can use the method `endswith` to check if a string ends with a given suffix.

For example:

```py
sentence = input("Type a sentence >> ")
if not sentence.endswith((".", "?", "!")):
    print("You don't punctuate your sentences?!")
    print("Shame on you!")
```

## Removing the prefix

Now, say you are processing some Python code.

It may or may not come from the REPL.

If it does, you need to strip the leading `>>> ` that the REPL code has.

How'd you write that?

```py
def process_code(code):
    processed = []
    for line in code.splitlines():
        if line.startswith(">>> "):
            processed.append(line[len(">>> "):])
        else:
            processed.append(line)
    return processed
```

But now it feels kind of silly to have such a nice method to check for the prefix...

And then having to remove it by hand.

So, Python also has a nice method to remove a prefix, called `removeprefix`:

```py
def process_code(code):
    processed = []
    for line in code.splitlines():
        if line.startswith(">>> "):
            processed.append(line.removeprefix(">>> "))
        else:
            processed.append(line)
    return processed
```

What's best?

The function `removeprefix` doesn't raise an exception if the prefix isn't there.

It just does nothing.

So you can use it without needing the `if`:

```py
def process_code(code):
    processed = [
        line.removeprefix(">>> ")
        for line in code.splitlines()
    ]
    return processed
```

## Removing the suffix

It would be outrageous to have `removeprefix` without `removesuffix`, so obviously there's that, also.

## Summary

In conclusion, there's four string methods you want to keep at your fingertips:

| Method | Behaviour |
| - | - |
| `startswith` | Checks if the string starts with (any of the) given prefix(es) |
| `endswith` | Checks if the string ends with (any of the) given suffix(es) |
| `removeprefix` | Removes the given prefix if it's there |
| `removesuffix` | Removes the given suffix if it's there |

These are more robust than the equivalent slicing operations, they're more convenient to use, and they're much more readable!

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_hero'} ) } %}
