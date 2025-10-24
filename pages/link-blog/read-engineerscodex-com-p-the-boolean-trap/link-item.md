---
# author:
date: 24-10-2025 08:15
link: https://read.engineerscodex.com/p/the-boolean-trap
taxonomy:
    category: link
title: "The Boolean Trap - Engineer’s Codex"
# via:
---

This article defends that there are many situations where using a Boolean as a function parameter is a bad idea and an enumeration would be better.
The example given is similar to the one below.

Suppose you have a function that produces some report data and that the report is written in a text format.
A new requirement comes in and you must also be able to output the report as some funky JSON data, so you extend the signature of the function:

```py
def generate_report(output_path: Path, as_json: bool = False) -> None:
    ...
```

Now, there's one thing that's already quite nasty and which is that function calls won't be easy to read if you don't specify the second parameter name:

```py
generate_report("report.txt", False)
```

What's that `False` there?
You can attenuate this by [forcing the Boolean to be passed in as a keyword argument](/blog/pydonts/functions-a-complete-reference#positional-only-and-keyword-only-arguments):

```py
def generate_report(output_path: Path, *, as_json: bool = False) -> None:
    ...
```

Now, you have to do

```py
generate_report("report.txt", as_json=False)
```

But wait, if “as JSON” is `False`, what is the alternative?
It's not obvious that it is plain text...

And another problem remains: if later on we need to add a third format, how do we account for it?
Booleans only have two states.

That's why the preferred approach would be to use an enumeration.
In Python 3.12, I recommend using a `StrEnum` (the link uses a plain enumeration):

```py
from enum import StrEnum, auto

class OutFormat(StrEnum):
    TEXT = auto()
    JSON = auto()
    SOME_OTHER_FORMAT = auto()
```

This way, the signature is easy to maintain & extend and it will remain readable:

```py
def generate_report(output_path: Path, fmt: OutFormat = OutFormat.TEXT) -> None:
    ...

generate_report("report.txt", OutFormat.JSON)
```
