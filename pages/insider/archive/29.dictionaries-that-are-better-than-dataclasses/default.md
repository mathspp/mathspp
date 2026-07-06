---
date: 06-07-2026 15:29
metadata:
    author: Rodrigo Girão Serrão
    description: "Learn the features of TypedDict that make them more versatile than dataclasses."
    og:image: "https://mathspp.com/insider/archive/dictionaries-that-are-better-than-dataclasses/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/dictionaries-that-are-better-than-dataclasses/thumbnail.webp"
title: "Dictionaries that are better than dataclasses"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Dictionaries that are better than dataclasses

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## TypedDict looks like a dataclass

[Last week you learned how to create and use typed dictionaries](https://mathspp.com/insider/archive/safer-dictionaries-with-typeddict).

The snippet below is a short example of a typed dictionary that could be used to represent an issue of this newsletter:

```py
from typing import TypedDict

class NewsletterIssue(TypedDict):
    content: str
    scheduled_on: datetime.date
    read_online: str
```

The class `NewsletterIssue` can now be used as a type hint in place of the built-in `dict`.

That means static type checkers can now check if you're accessing keys that exist or not.

And your IDE will provide autocompletion hints when you're accessing values of the dictionary.

But if you look at the class definition, it looks very similar to a dataclass.

## Typed dictionaries are similar to dataclasses

You can just as easily create a dataclass that represents newsletter issues:

```py
from dataclasses import dataclass

@dataclass
class NewsletterIssue:
    content: str
    scheduled_on: datetime.date
    read_online: str
```

One advantage of using a dataclass is that you can define methods directly in the dataclass.

But a dataclass is _fundamentally_ different from a typed dictionary.

A typed dictionary is just a data container.

And typed dictionaries provide some functionality that goes beyond what you can do with dataclasses.

That's what you'll learn about now.

## Keys that are required or not required

Suppose you want to create a dictionary representing a newsletter issue that does _not_ have a link to read online:

```py
issue: NewsletterIssue = {
    "content": "...",
    "scheduled_on": datetime.date(2026, 7, 6),
}
```

It's reasonable to imagine this scenario.

You have the main structure but some keys and values might be missing.

However, a static type checker will complain about this:

```text
error: Missing key "read_online" for TypedDict "NewsletterIssue"  [typeddict-item]
```

By default, all keys in a typed dictionary are required.

However, you can mark a key as not being required with the annotation `NotRequired` from the module `typing`:

```py
from typing import TypedDict, NotRequired

class NewsletterIssue(TypedDict):
    content: str
    scheduled_on: datetime.date
    read_online: NotRequired[str]
```

In doing so, your static type checker no longer complains about the fact that you're creating a dictionary without the key `read_online`.

The keys listed in the body of a typed dictionary are all required by default.

But you can flip the default around if you pass the argument `total=False` when you're defining the typed dictionary.

In that case, you can mark required keys with `Required`:

```py
from typing import TypedDict, Required

class NewsletterIssue(TypedDict, total=False):
    content: Required[str]
    scheduled_on: Required[datetime.date]
    read_online: str
```

The typed dictionary above sets the keys `"content"` and `"scheduled_on"` as required and `"read_online"` as not required.

Just to clarify, a key that isn't required isn't a key that's set to the value `None`...

It's a key that is _not_ present in the dictionary.

The default behaviour, in which all keys are required, is obtained from `total=True`.

## Read-only values

Another useful feature of typed dictionaries is that you can set some key/value pairs to be read-only.

In doing so, type checkers will warn you if you try to modify the values in the dictionary.

A value can be marked as read-only with the annotation `ReadOnly`:

```py
from typing import TypedDict, ReadOnly

class NewsletterIssue(TypedDict):
    content: str
    scheduled_on: datetime.date
    read_online: ReadOnly[str]
```

You can try to modify the value associated with the key `"read_online"`:

```py
issue: NewsletterIssue = {
    "content": "...",
    "scheduled_on": datetime.date(2026, 7, 6),
    "read_online": "mathspp.com/insider/archive",
}

issue["read_online"] = "https://mathspp.com/insider/archive"
```

If you run a type checker, you get an error in the line that tries to change the value of `issue["read_online"]`:

```text
error: ReadOnly TypedDict key "read_online" TypedDict is mutated  [typeddict-readonly-mutated]
```

By default, all key/value pairs can be modified.

If you want a key/value pair to be read-only, you always have to annotate it as such explicitly.

## Typed dictionaries with extra items

You can create a typed dictionary that accepts extra arbitrary values of a given type.

For example, you can say that the typed dictionary `NewsletterIssue` can have extra items as long as they're Boolean values:

```py
class NewsletterIssue(TypedDict, extra_items=bool):
    content: str
    scheduled_on: datetime.date
    read_online: ReadOnly[str]
```

By setting the class parameter `extra_items=bool`, you're saying that it's ok to include extra items...

As long as they're Booleans:

```py
issue: NewsletterIssue = {
    "content": "...",
    "scheduled_on": datetime.date(2026, 7, 6),
    "read_online": "mathspp.com/insider/archive",
    "worth_reading": True,  # OK
    "lines": 200,  # Not OK
}

issue["saved_for_later"] = False  # OK
```

If you run a static type checker on this, the type checker will complain about the line `"lines": 200`:

```text
Invalid argument to key "lines" with declared type `bool` on TypedDict `NewsletterIssue`
```

(mypy doesn't support `extra_items` yet. If you use mypy, try using [ty](https://docs.astral.sh/ty/), for example.)

It may not be a great idea, but you _can_ use the types `typing.Any` or `object` in `extra_items`.

This means you can create key/value pairs with values that have arbitrary types.

The (in)ability to add extra items to a typed dictionary has to do with the dictionary's openness.

You can explicitly control the openness of a typed dictionary by setting the class parameter `closed=True` or `closed=False`.

But that only comes into play if you bring inheritance into the mix:

## Inheriting typed dictionaries

Things can get even crazier.

A typed dictionary cannot inherit from arbitrary classes.

But you _can_ inherit from another typed dictionary to get their key/value pairs and to potentially specify others.

If you get into typed dictionary inheritance make sure to read up on how inheritance interacts with:

 - required and non-required keys
 - read-only keys
 - dictionary totality (class parameter `total`)
 - extra items (class parameter `extra_items`)
 - openness (class parameter `closed`)

## Better than dataclasses?

What do you think?

Typed dictionaries or dataclasses?

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_intro'} ) } %}
