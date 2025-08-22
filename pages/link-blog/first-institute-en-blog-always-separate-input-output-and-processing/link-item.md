---
author: Volodymyr Obrizan
date: 22-08-2025 22:12
link: https://first.institute/en/blog/always-separate-input-output-and-processing
taxonomy:
    category: link
title: "Best Code Rule: Always Separate Input, Output, and Processing"
via: https://planetpython.org
---

This article explains that the best way to write scripts in a way that sets you up for success is by separating the logic for

 1. input;
 2. processing; and
 3. output,

into three separate parts of your script.
By separating these three components, it becomes easier to change them (for example, you're now reading from an API instead of from a file) when your requirements change.
If things are tightly coupled, editing/updating/scaling becomes really hard.

I might add that separating these three components also makes it easier to turn your script(s) into CLIs.
