---
author: Dave Peck
date: 19-05-2025 07:27
link: https://davepeck.org/2025/04/11/pythons-new-t-strings/
taxonomy:
    category: link
title: "Python's new t-strings"
# via:
---

Python 3.14 is getting (or got, depending on when you read this) a new feature called t-strings.
t-strings were introduced in [PEP 750](https://peps.python.org/pep-0750/) and one of the PEP authors (Dave Peck) wrote a short article showing off some of the capabilities of t-strings.

One of the key points is that t-strings... are not strings!
Their type is `string.templatelib.Template`.
t-strings are composed of string segments and the interpolated values (`string.templatelib.Interpolation`), which will be whatever we put inside the `{}`.

The programmer is then responsible for (defining and) using a function that processes the t-string into a string.
The simplistic example below escapes the characters `<>` before interpolating HTML:

```py
from string.templatelib import Template

def interpolate_html_safe(template: Template) -> str:
    segments: list[str] = []
    for item in template:
        if isinstance(item, str):
            segments.append(item)
        else:  # Interpolation value.
            html_fragment = item.value
            segments.append(html_fragment.replace("<", "&lt;").replace(">", "&gt;"))

    return "".join(segments)

to_format = "<script>alert('Malicious JS');</script>"
html_page = t"<html>{to_format}</html>"
print(interpolate_html_safe(html_page))
# <html>&lt;script&gt;alert('Malicious JS');&lt;/script&gt;</html>
#       ^^^^      ^^^^                      ^^^^       ^^^^
```
