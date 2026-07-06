---
date: 29-06-2026 14:27
metadata:
    author: Rodrigo Girão Serrão
    description: "Learn how to use typing.TypedDict to write safer code when working with dictionaries with fixed structures."
    og:image: "https://mathspp.com/insider/archive/safer-dictionaries-with-typeddict/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/safer-dictionaries-with-typeddict/thumbnail.webp"
title: "Safer dictionaries with TypedDict"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Safer dictionaries with `TypedDict`

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## How dictionaries are used

Dictionaries are containers, like lists or tuples.

But while lists or tuples allow accessing individual values by index...

Dictionaries provide a way to access individual values that is semantically more meaningful: through keys.

A natural consequence of this is that dictionaries are often used to represent fixed structures.

For example, you can use a dictionary to represent a set of configurations for your IDE:

```py
config = {
    "font-size": 18,
    "theme": "Flexoki",
    "format_on_save": True,
}
```

Or a response from an API:

```py
response = {
    "status_code": 200,
    "payload": "Dictionaries are containers, like ...",
}
```

Or a newsletter issue:

```py
newsletter = {
    "content": ...,
    "scheduled_on": datetime.date(2026, 6, 29),
    "read_online": "https://mathspp.com/insider/archive",
}
```

What do all of these dictionaries have in common?

For starters, all their keys are strings.

And second, you expect their structure to stay fixed...

## Dictionaries with fixed structures

Take the newsletter dictionary as an example.

You'll write a function `estimate_reading_time` that estimates the reading time of the issue based on the dictionary.

You may write something like this:

```py
# Adult average is 200—250 WPM, for technical content
# it's reasonable to pick a slower speed.
READING_WPM = 200

def estimate_reading_time(newsletter_dict):
    words = newsletter_dict["text"].split()
    return len(words) / READING_WPM
```

There's just a problem with the code above.

Can you spot it?

If you compare the snippet above with the place where you first defined the newsletter dictionary, you'll see that the key `"text"` doesn't exist.

Instead, you should've used the key `"content"`.

This is a bug you'd only catch during runtime.

And yet, you _know_ that newsletter issue dictionaries are supposed to have the keys `"content"`, `"scheduled_on"`, and `"read_online"`.

So, a tool that's smart enough could've warned you about the bug...

## Using `TypedDict` to specify the structure of dictionaries

In situations like this, you can use `typing.TypedDict` to specify the structure of your dictionaries.

Then, a static type checker can let you know whether you're trying to access the wrong keys.

To create a `TypedDict`, you create a class that inherits from `TypedDict`.

Then, you list the keys as class variables with a type hint that indicates the type of the corresponding value.

For the newsletter issue dictionary, you'd write something like

```py
from typing import TypedDict

class NewsletterIssue(TypedDict):
    content: str
    scheduled_on: datetime.date
    read_online: str
```

Then, when you're assigning the real dictionary to a variable, you add the type hint:

```py
newsletter: NewsletterIssue = {
    "content": ...,
    "scheduled_on": datetime.date(2026, 6, 29),
    "read_online": "https://mathspp.com/insider/archive",
}
```

You are _not_ supposed to instantiate the class `NewsletterIssue`.

At runtime, the dictionary `newsletter` is a regular dictionary.

But static type checkers can help you keep track of the keys that you can access.

And your IDE will provide smart auto-completion suggestions when you try to key into your dictionary.

For your function `estimate_reading_time`, you'd add the typed dictionary as a type hint for the argument as well:

```py
def estimate_reading_time(newsletter_dict: NewsletterIssue) -> float:
    words = newsletter_dict["text"].split()
    return len(words) / READING_WPM
```

In doing so, a static type checker is now able to spot the bug.

For example, if you run mypy, you get the following error:

```text
error: TypedDict "NewsletterIssue" has no key "text"  [typeddict-item]
```

## Other `TypedDict` niceties

Typed dictionaries have a couple of other niceties that make them more flexible and convenient.

Next week you'll learn about keys that may or may not be required, you'll learn about the **totality** of the typed dictionary, and you'll learn how to specify the type of arbitrary extra items.

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_intro'} ) } %}
