---
# author:
date: 22-01-2025 20:33
link: https://michelf.ca/projects/php-markdown/extra/#markdown-attr
taxonomy:
    category: link
title: "PHP Markdown Extra – Markdown Inside HTML Blocks"
# via:
---

This page documents “Markdown Extra”, an extension of Markdown that I use on my website.
I'm blogging about this because the section “Markdown Inside HTML Blocks” shows how to tell the Markdown parser that I have Markdown nested inside HTML blocks.

This is particularly useful because sometimes I like to include code blocks inside the HTML tag `<details>`.
The link shows that I have to add the attribute `markdown="1"` to the HTML tag that will contain nested Markdown and the parser takes care of the rest (and removes the attribute).

Here's a tag `<details>` that uses the attribute in the source code:

<details markdown="1">
<summary>Attribute was used.</summary>

```py
print("Hello, world!")
```
</details>

Here's what the same HTML + Markdown combo will look like without the correct Markdown Extra attribute:

<details>
<summary>Attribute was used.</summary>

```py
print("Hello, world!")
```
</details>
