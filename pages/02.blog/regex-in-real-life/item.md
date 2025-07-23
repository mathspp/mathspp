This article shows how I have used regex in real life for all sorts of tasks.

===


I love keeping track of real-life examples of the usage of certain features and regex is one of those things, so in this article you will find real-life examples of how/where I used regular expressions to do all sorts of tasks.

As I use regex in new and interesting ways, I will update this article to include those examples.


## Compiling a numbered list of tip titles

For [my newsletter Python drops 🐍💧](/drops), I keep a list of all previous tips titles so that people can see what they're missing out on.
To compile this list, I run a short script that traverses the directory where the tips are and finds their number and title.

This is the rough directory structure:

```
|- 0001-zip_strict_true
   |- tip.md
|- 0002-string_casefold_case-insensitive_comparison
   |- tip.md
|- 0003_type_unions_with_pipe
   |- tip.md
...
```

Each directory contains some other files, but I only care about the files `**/tip.md` for the purposes of this specific task.
I want to check each file `tip.md` and extract the tip number and title.

As an example, this is what the file `0003_type_unions_with_pipe/tip.md` looks like:

```txt
---
themes:
    - "typing/type hints"
    - "`isinstance`"
    - "vertical bar `|`"
    - "type unions"
---

## 3 – Type unions with the vertical bar in `isinstance`

...
```

Assuming all files `tip.md` have a title in the same format, I can use regex to extract them:

```py
TITLE_PATTERN = re.compile(r"(?m)^## (?P<nr>\d{1,4}) – (?P<title>.*)$")
```

This neat little pattern uses a couple of nice regex features.

By using the inline flag `(?m)`, I make sure that the special characters `^` and `$` match the beginning and end of every new line, respectively, instead of only matching the beginning and end of the string.

I also use named groups with the syntax `(?P<group_name>...)`, which allows me to refer to the parts of the match that I care about by name, instead of having to figure out what's the group index of each portion I care about.

After extracting everything, I also go over the titles and check if they have parts that are enclosed in backticks, since those need to be converted to `<code>` tags.
For that, I use a tiny, uninteresting regex pattern, but then use dynamic replacement:

```py
import re

CODE_PATTERN = re.compile("`(.*?)`")

def to_code(title: str) -> str:
    def repl(match: re.Match) -> str:
        return f"<code>{match.group(1)}</code>"

    return CODE_PATTERN.sub(repl, title)
```

If I pass the string ``"Type unions with the vertical bar in `isinstance`"`` to the function `to_code`, I get the following HTML as output:

```html
Type unions with the vertical bar in <code>isinstance</code>
```

Putting everything together, I get a fairly short Python script that goes through all directories, finds the files `tip.md`, uses regex to extract the tip numbers and titles, and then produces a valid HTML list I use in my blog.

<details markdown="1">
<summary>Full script.</summary>

```py
from pathlib import Path
import re

TITLE_PATTERN = re.compile(r"(?m)^## (?P<nr>\d{1,4}) – (?P<title>.*)$")
CODE_PATTERN = re.compile("`(.*?)`")

def to_code(title: str) -> str:
    def repl(match: re.Match) -> str:
        return f"<code>{match.group(1)}</code>"

    return CODE_PATTERN.sub(repl, title)

if __name__ == "__main__":
    titles: list[tuple[int, str]] = []
    for tip in Path(".").rglob("**/tip.md"):
        contents = tip.read_text()
        if (match := TITLE_PATTERN.search(contents)):
            titles.append(
                (int(match.group("nr")), match.group("title"))
            )

    html_lines = ["<ol reversed>"]
    for nr, title in sorted(titles, reverse=True):
        html_lines.append(f"    <li>{to_code(title)}</li>")
    html_lines.append("</ol>")
    html = "\n".join(html_lines)
    print(html)
```

</details>

Running this script prints the HTML code I need to add to my site:

```html
<ol reversed>
    <li>Type unions with the vertical bar in <code>isinstance</code></li>
    <li>Case-insensitive string comparisons</li>
    <li><code>zip</code>'s keyword argument <code>strict</code></li>
</ol>
```

Which is then rendered like this:

<ol reversed>
    <li>Type unions with the vertical bar in <code>isinstance</code></li>
    <li>Case-insensitive string comparisons</li>
    <li><code>zip</code>'s keyword argument <code>strict</code></li>
</ol>
