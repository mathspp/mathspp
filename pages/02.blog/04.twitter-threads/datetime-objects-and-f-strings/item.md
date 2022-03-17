---
date: 17-03-2022
metadata:
    author: Rodrigo Girão Serrão
    description: "Learn how to format dates/times with f-strings and %-format specifiers."
    og:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
    twitter:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
slug: datetime-objects-and-f-strings
taxonomy:
    category: twitter thread
    tag:
      - programming
      - python
title: "Datetime objects and f-strings"
---

Learn how to format dates/times with f-strings and %-format specifiers.

===

Lately, I have tweeted a lot about dates and times.

In Python 🐍, the built-in module to work with those is `datetime`.

If you have a `datetime` object, you can format it easily with f-strings!

You just need to use the syntax you are used to:

```py
>>> import datetime as dt
>>> now = dt.datetime.now()
>>> now
datetime.datetime(2022, 3, 17, 10, 42, 44, 770965)
>>> f"Today is the {now:%d-%m-%Y}."
'Today is the 17-03-2022.'
```


However, keep this in mind:

What you write on the right of the `:` inside the curly braces `{}` can be any string whatsoever!

It can be any string, and the % format specifiers will be replaced.

So, I can take the example above and turn the f-string inside out:

```py
>>> f"{now:Today is the %d-%m-%Y.}" 
'Today is the 17-03-2022.'
```


That looks a bit funky, doesn't it? 🤪

It works, but it's not the recommended way of using the formatting.

Here are some more examples of specifiers that you can find cool/useful:

```py
>>> import datetime as dt
>>> now = dt.datetime.now()
>>> now
datetime.datetime(2022, 3, 17, 11, 7, 20, 770789)

# Day of week name (%A) and month name (%B):
>>> f"Today is a fine {now:%A} of {now:%B}."
'Today is a fine Thursday of March.'
# Day of the year as a number 1 - 366 (%j):
>>> f"{now:%d/%m} is day number {now:%j} of the year {now:%Y}."
'17/03 is day number 076 of the year 2022.'
# Locale's appropriate date and time representation (%c):
>>> f"{now:%c}"
'Thu Mar 17 11:07:20 2022'
```
