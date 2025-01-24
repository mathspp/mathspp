---
date: 24-01-2025 14:33
link: https://stackoverflow.com/a/62270300/2828287
taxonomy:
    category: link
title: "intellij 15 - VS Code Regex find and replace with lowercase, use \\l or \\L if possible - Stack Overflow"
---

Sometimes I use regular expressions to do some mass text processing.
Today I was working on a first version of my Python glossary and wanted to reformat a couple of things.
In the process, I wanted to move some words around and one of the words would now become the first word in a new paragraph, so it needed to be turned into an uppercase letter.

I knew I could do this with regular expressions so I googled how to do it and this Stack Overflow answer was what I was looking for.
The sequences `\l`, `\L`, `\u`, and `\U`, can be used to modify the casing of the groups that are included after.

For example, using the search pattern `(\w+)` and the replace pattern `\u$1` on the text "bananas" will replace it with "Bananas".
Using `\U` instead will convert all letters to uppercase instead of just the first one.
