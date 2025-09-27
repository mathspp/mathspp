# 🐍🚀 3 tips to use f-strings effectively

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider#subscribe) to get weekly Python deep dives like this one on your inbox!

## 3 tips for f-strings

f-strings have been around ever since Python 3.6 and I cannot live without them.

If you still use the string method `format` to do formatting – or even `%-style` formatting! – then you can read up on the basics of f-strings in this article.

If you already know f-strings, let me share 3 tips with you that will make your life easier.

## 1. Self-debugging f-strings

One of my favourite features of f-strings is that they can self-debug.

If your format specifier ends with an equals sign `=`, Python will format the string with the result value but also with the expression you typed!

Compare the two:

```python
>>> value = 73
>>> f"{value}"
'73'
>>> f"{value = }"
'value = 73'
```

The spaces around the equals sign `=` are relevant; if you don't include them, the formatted string also won't include them.

It also works with more “complex” expressions:

```python
>>> x, y, z = 2, 10, 100
>>> f"{(y + z) ** x = }"
'(y + z) ** x = 12100'
```

## 2. The specifier `!r`

When using f-strings for any form of debugging, it might be useful to include `!r` in your expression to be formatted so that Python uses `repr` (and not `str`) to format the string.

(You can read about the differences between `repr` and `str` – or between `__repr__` and `__str__` in this article.)

For example, when formatting strings that actually look like numbers, you can only distinguish them if you use `!r`:

```python
>>> num = 73
>>> s = "73"
>>> f"{num}, {s}"
'73, 73'
>>> f"{num!r}, {s!r}"
"73, '73'"
```

Bonus tip: if you use the self-debugging features, Python will use `repr` instead of `str`:

```python
>>> f"{num = }, {s = }"
"num = 73, s = '73'"  # <-- '' around the s value.
```

## 3. Parametrised formatting

Sometimes, you'll want to use a format specifier that is, itself, a variable.

One way in which I use this is when I have a list of variables to align to the left and I don't know, ahead of time, how wide my column needs to be.

I compute the maximum width, I add a couple of spaces to make it neat, and then I use the nested braces `{}` to use the variable as the formatter:

```python
>>> names = ["Harry", "Anne", "George"]
>>> width = 2 + max(map(len, names))
>>> for name in names:
...     print(f"| {name:>{width}} |")
...
|    Harry |
|     Anne |
|   George |
```

## Bonus: fstring.help

I'm here to help, so here's a bonus tip:

To learn more about f-strings, a friend created the page <fstring.help>.

It's sort of a quick reference to the most common and useful features of f-strings.

## Enjoyed reading?

This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter.
Subscribe to the mathspp insider 🐍🚀 to get weekly Python deep dives like this one on your inbox:

[Join mathspp insider 🐍🚀](?classes=btn,btn-lg,btn-center#subscribe)
