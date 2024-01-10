Learn how to find text patterns and replace them with dynamic content using regex.

===


# Dynamic string replacements with regex

The other day I was working on [my problems book](https://mathspp.gumroad.com/l/problems) and I needed to update a markdown file to number the headings.
In short, I had a markdown file like this:

```md
# Problems

## Dancing triangle

...

## Bag full of numbers

...

## Quarrel in the Shire

...
```

I wanted to find all H2 headings and number them, so I'd end up with this file:

```md
# Problems

## 01 – Dancing triangle

...

## 02 – Bag full of numbers

...

## 03 – Quarrel in the Shire

...
```


I thought about doing this manually but I'd need to do this to 2 documents, each with thousands of lines and 64 headings, so I decided to use a bit of Python and regex to do it.

The module `re`, from the Python standard library, lets you do string replacements with [the function `re.sub`](https://docs.python.org/3/library/re.html#re.sub), which needs 3 parameters:

 1. the pattern we're looking for;
 2. the replacement for the pattern; and
 3. the string we're searching & replacing in.

`re.sub` is like the string method `str.replace` on steroids!

In my case, the pattern I was looking for was this:

```
^## (.*)$
```

This looks for a `##` at the beginning of the line, followed by a space, and then the `(.*)$` matches everything else until the end of the line.

We can see this in action if we use `re.findall` and a dummy string:

```pycon
>>> import re
>>> string = '# Problems\n\n## Dancing triangle\n\n...\n\n## Bag full of numbers\n\n...\n\n## Quarrel in the Shire\n\n...'
>>> re.findall("^## (.*)$", string, flags=re.MULTILINE)
[
    'Dancing triangle',
    'Bag full of numbers',
    'Quarrel in the Shire'
]
```

The flag `re.MULTILINE` was used so that the anchors `^` and `$` matche the beginning and end of each line, respectively, instead of the beginning and end of the string.

(Go to [regex101](https://regex101.com) (an online regex playground), paste the regular expression `^## (.*)$` in the top, middle bar, and copy and paste the first version of the `# Problems` markdown in the big, central text area.)

The next thing I wanted was to be able to replace each title with a number followed by `–` and then itself!
By using group references, adding the `–` before the title is easy:

```pycon
>>> print(
...     re.sub("^## (.*)$", r"## xx – \1", string, flags=re.MULTILINE)
... )
# Problems

## xx – Dancing triangle

...

## xx – Bag full of numbers

...

## xx – Quarrel in the Shire

...
```

The “difficult” part is adding the number that must be incremented each time we add it.
Thankfully, the function `re.sub` has a trick up its sleeve!

The second parameter of the function `re.sub`, which is the replacement, can be a _function_.
This function must have a single parameter, which is the match object, and must return a string, which is the replacement for the given match.
Thus, by using a counter variable, I can add these increasing IDs:

```pycon
>>> counter = 0
>>> def replacer(match):
...     global counter
...     counter += 1
...     return f"## {counter:02} – {match.group(1)}"
...
>>> print(
...     re.sub("^## (.*)$", replacer, string, flags=re.MULTILINE)
... )
# Problems

## 01 – Dancing triangle

...

## 02 – Bag full of numbers

...

## 03 – Quarrel in the Shire

...
```

And that was it!
In 5 minutes, I was done!
I numbered 128 chapters with a regular expression and a couple of lines of Python code and saved myself roughly 60 minutes of tedious manual work!

If you've ever used regular expressions to help you with anything, let me know in the comments below!
