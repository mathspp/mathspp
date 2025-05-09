---
author: Ned Batchelder
date: 09-05-2025 19:00
link: https://nedbatchelder.com/blog/202504/regex_affordances.html
taxonomy:
    category: link
title: "Regex affordances"
# via:
---

This short but instructional post by Ned covers some useful and not-so-common regular expression features, namely:

 - named groups with `(?P<group_name>)`;
 - the verbose mode for regular expressions with the flag `(?x)`; and
 - dynamic replacements with the function `re.sub`.

The regex with dynamic replacement was being used to replace references to `$`-prefixed variables.
In the post, Ned wrote a function `substitute_variables(text: str, variables: Mapping[str, str]) -> str` that you could use like this:

```py
print(
    substitute_variables(
        "Hey, my name is $name!",
        {"name": "Rodrigo"},
    )
)  # Hey, my name is Rodrigo!
```

Ned also [shared a link to the function in its real context in `coverage.py`](https://github.com/nedbat/coveragepy/blob/8da4a00b8e439b49dd5f207b6e2bc323d8e6c39c/coverage/misc.py#L228-L276).
