Today I learned how many soft keywords Python has and what they are.

===


# Python's soft keywords

Python 3.12 has 4 soft keywords:

 1. `match` – became a soft keyword in Python 3.10 when [structural pattern matching](/blog/pydonts/structural-pattern-matching) was introduced;
 2. `case` – see above;
 3. `_` – surprisingly, `_` was also turned into a soft keyword because of structural pattern matching ([this short read](/blog/til/underscore-is-a-soft-keyword) will give you a bit more context); and
 4. `type` – with the introduction of [`type` statements in Python 3.12](/blog/til/type-statement-and-type-aliases), `type` became a soft keyword (it is also a built-in!)

In older versions of Python (I _think_ Python 3.5 and 3.6), `async` and `await` were soft keywords too, but that's no longer the case.


## What are soft keywords?

Loosely speaking, soft keywords are keywords that can be assigned to, in opposition to hard keywords that can never be assigned to.

`if` is an example of a hard keyword.
No matter what you do, `if` will always be used to represent the beginning of a conditional statement and you definitely cannot assign to `if` under no circumstance, as writing something like `if = 3` will raise a `SyntaxError`.

On the other hand, a keyword like `match` is a soft keyword because you can use it for structural pattern matching, but it's also a legal variable name elsewhere: `match = re.match(...)` works perfectly well.


## How to determine all of Python's soft keywords?

!!!! EDIT: After sharing my original method and posting this article on X, Pablo Galindo Salgado – a Python core dev – [pointed out I could've simply done the following](https://x.com/pyblogsal/status/1723690831318577591):
!!!! 
!!!! ```pycon
!!!! >>> import keyword
!!!! >>> keyword.softkwlist
!!!! ['_', 'case', 'match', 'type']
!!!! ```

In order to determine Python's soft keywords, I started by opening the Python grammar (at the time of writing, [this file](https://github.com/python/cpython/blob/12a30bc1aa0586308bf3fe12c915bcc5e54a032f/Grammar/python.gram) was the most recent grammar version).

 - I opened the Python REPL;
 - I loaded the file; and
 - I used a regular expression to look for all keywords:

```pycon
>>> import re
>>> pat = re.compile(r'\'.{,15}\'|".{,15}?"')
>>> matches = set(pat.findall(gram))
>>> matches
{
    "':' &('}'|','",
    "'=', or '!', or '",
    "' | '**'",
    "'+' | '-'",
    "'//'",
    '"invalid_"',
    "'except' ':'",
    # ...
}
```

As you can see, that gives plenty of false positives, but that's ok.
We have 222 possible keywords to go through:

```pycon
>>> len(matches)
222
```

However, if we get rid of all of the ones that contain whitespace, only 105 matches are left:

```pycon
>>> len(matches)
105
```

After this, I painstakingly went through all 105 matches and checked manually whether they were soft keywords or not.
I took none for granted.
I event went through the trouble of checking `if` and `for`, for example, so that I could be 100% certain I wouldn't miss a single soft keyword.

Then, I was doing some research on structural pattern matching and I was reading [PEP 634](https://peps.python.org/pep-0634/) that introduces it, when I read this sentence referring to the Python grammar:

 > “By convention, hard keywords use single quotes while soft keywords use double quotes.”

This means that I could've used a simpler pattern:

```pycon
>>> pat = re.compile(r'".{,15}?"')
>>> matches = set(pat.findall(gram))
>>> len(matches)
11
>>> matches
{
    '"/"',
    '"dsfsdf"',
    '"expected \':\'"',
    '"match"',
    '"type"',
    '"invalid_"',
    '"_"',
    '"=="',
    '"case"',
    '", "',
    '"!"'
}
```

I was also quite intriguided by the false positive `"dsfsdf"`, but now I can confirm that it is definitely not a hidden keyword in the language!
