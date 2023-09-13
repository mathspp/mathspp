---
metadata:
    description: "In this Pydon't you will learn the Python string methods `translate` and `maketrans`."
title: "String translate and maketrans methods | Pydon't 🐍"
---

In this Pydon't you will learn the Python string methods `translate` and `maketrans`.

===

![An implementation of the Caesar cipher making use of the string `maketrans` and `translate` methods.](thumbnail.webp)

(If you are new here and have no idea what a Pydon't is, you may want to read the
[Pydon't Manifesto][manifesto].)


# Introduction

The strings methods `str.translate` and `str.maketrans` might be some of the
lesser known string methods in Python.

Sadly, most online resources that cover this topic make a really poor
job of explaining how the two methods work,
so hopefully this Pydon't will serve you and let you know
about two really cool string methods.

In this Pydon't, you will:

 - be introduced to the string method `str.translate`;
 - learn the available formats for the method `translate`;
 - see that _all_ characters (even emojis!) have a corresponding integer value;
 - review the behaviour of the built-in functions `ord` and `char`;
 - learn about the complementary string method `str.maketrans`;
 - see good use cases for both `str.translate` and `str.maketrans`.

<!--v-->
!!! You can now get your free copy of the ebook “Pydon'ts – Write elegant Python code” [on Gumroad][gumroad-pydonts]
!!! to help support the series of “Pydon't” articles 💪.
<!--^-->


# `str.translate`

The `str.translate` method is much unknown, but not because it is difficult to understand.
It's just underappreciated,
which means it doesn't get used much,
which means it gets less attention than it deserves,
which means people don't learn it,
which means it doesn't get used much,
...
Do you see where this is going?

I won't pretend like this method will completely revolutionise
every single piece of Python code you will write in your life,
_but_ it is a nice tool to have in your tool belt.

As per the documentation, the `str.translate(table)` method returns

 > “a copy of the string in which each character has been mapped through the given translation table.”

The translation table being mentioned here is the only argument that the method `str.translate` accepts.

In its simplest form, the method `str.translate` is _similar_
to the method `str.replace`.

In case you don't know it, here is what `str.replace` looks like:

```py
>>> s = "Hello, world!" 
>>> s.replace("l", "L")
'HeLLo, worLd!'
```


## Character code points

Computers work with zeroes and ones, binary –
that's something we've all heard someone say at some point in our lives.
But if that's the case, then how can we work with characters and text?
How do we encode text information as zeroes and ones?

I'm not going to pretend like I know _really well_ how the internals
of these things work, but essentially we just need to attribute
a number to every single character,
and then the computer can take a look at that number and say
_“Oh, that's a 65? Alright, then I'll show an "A".”_.

For that to work, computer programs must agree on what integers
represent what characters.
For example, who said 65 is "A"?
Did I just invent that?

Much like the scientific community agreed that the metre
would be the standard unit to describe distances,
computer people have standards that specify how to map
numbers to characters.
The most well-known such standard is the [Unicode] standard,
and that's the standard that Python uses.

All this talk has a single purpose: make you comfortable with the
idea that characters can be turned into integers and back.
Python even provides two useful built-in functions to do these
conversions, the built-in functions `chr` and `ord`:

```py
>>> ord("A")
65
>>> ord("a") 
97
>>> ord(" ")
32
>>> chr(65)
'A'
>>> chr(97)
'a'
>>> chr(32)
' '
>>> chr(128013)
'🐍'
```

Notice that even emoji have an integer that represents them!

`chr` takes an integer and returns the character that that integer
represents, whereas `ord` takes a character and returns the
integer corresponding to its Unicode code point.

! The “code point” of a character is the integer that corresponds
! to it in the standard being used – which is the Unicode standard
! in the case of Python.


## Translation dictionaries

Now that we know about the code points of characters,
we can learn how to use the method `str.translate`,
because now we can build dictionaries that can be passed in
as translation tables.

The translation dict that is fed as the argument to `str.translate`
specifies the substitutions that are going to take place
in the target string.

The dictionary needs to map Unicode code points (i.e., characters)
to other Unicode code points, to other strings, or to `None`.

Let's see if you can infer how each case works:

```py
>>> ord("a"), ord("b"), ord("c")
(97, 98, 99)
>>> ord("A")
65
>>> "aaa bbb ccc".translate(
...     {97: 65, 98: "BBB", 99: None}
... )
'AAA BBBBBBBBB '
```

Notice that the method `str.translate` above
received a dictionary with 3 keys:

 - `97` (the code point for `"a"`) mapped to `65` (the code point for `"A"`);
 - `98` (the code point for `"b"`) mapped to `"BBB"`; and
 - `99` (the code point for `"c"`) mapped to `None`.

In the final result,
we see that all lower case "A"s were replaced with upper case "A"s,
the lower case "B"s were replaced with triple "BBB"
(so much so that we started with three "B"s and the final string
has nine "B"s),
and the lower case "C"s were removed.

This is subtle, but notice that the empty spaces were left intact.
What happens if the string contains other characters?

```py
>>> "Hey, aaa bbb ccc, how are you?".translate(
...     {97: 65, 98: "BBB", 99: None}
... )
'Hey, AAA BBBBBBBBB , how Are you?'
```

We can see that the characters that were not keys of the dictionary
were left as-is.

Hence, the translation works as follows:

 - characters that do not show up in the translation table are left untouched;
 - all other characters are replaced with their values in the mapping; and
 - characters that are mapped to `None` are removed.


# Non-equivalence to `str.replace`

Some of you might be thinking that I'm just being silly,
making a huge fuss about `str.translate`,
when all I need is a simple `for` loop and the method `str.replace`.
Are you right?

Let me rewrite the example above with a `for` loop
and the string method `str.replace`:

```py
>>> s = "Hey, aaa bbb ccc, how are you?"
>>> from_ = "abc"
>>> to_ = ["A", "BBB", ""]
>>> for f, t in zip(from_, to_):
...     s = s.replace(f, t)
...  
>>> s
'Hey, AAA BBBBBBBBB , how Are you?'
```

As we can see, the result seems to be exactly the same,
and we didn't have to introduce a new string method.

! If you are not comfortable with the `zip` in that
! `for` loop above, I got you:
! take a look at the [Pydon't about `zip`][pydont-zip-up].

Of course, we are forgetting the fact that the `for` loop
technique using successive `str.replace` calls is doing more work
than the `str.translate` method.
What do I mean by this?

For every loop iteration, the `str.replace` method has to go over
the whole string looking for the character we want to replace,
and that's because consecutive `str.replace` calls are _independent_
of one another.

But wait, if the successive calls are _independent_ from one another,
does that mean that..?
Yes!

What if we wanted to take a string of zeroes and ones and replace
all zeroes with ones, and vice-versa?
Here is the solution using the successive `str.replace` calls:

```py
>>> s = "001011010101001"
>>> from_ = "01"
>>> to_ = "10"
>>> for f, t in zip(from_, to_):
...     s = s.replace(f, t)
... 
>>> s
'000000000000000'
```

It didn't work!
Why not?
After the first iteration is done,
all zeroes have been turned into ones,
and `s` looks like this:

```py
>>> s = "001011010101001"
>>> s.replace("0", "1")
'111111111111111'
```

The second iteration of the `for` loop has no way to know
what ones are original and which ones used to be zeroes that were
just converted, so the call `s.replace("1", "0")` just replaces
everything with zeroes.

In order to achieve the correct effect, we need `str.translate`:

```py
>>> "001011010101001".translate(
...     {ord("0"): "1", ord("1"): "0"}
... )
'110100101010110'
```

Therefore, we have shown that `str.translate` is not equivalent
to making a series of successive calls to `str.replace`,
because `str.replace` might jumble the successive transformations.


# Generic translation tables

The method `str.translate` accepts a “translation table”,
but that table does _not_ need to be a dictionary.
That table can be any object that supports indexing with square
brackets.
In general, people use mappings (like dictionaries)
or sequences (like lists),
but you can even use your own custom objects.

I really enjoy using dictionaries (and other similar objects)
because it is really easy to specify what maps to what,
but for the sake of learning, let me show you an example
where a list is used.

For that, let's write a translation table (as a list)
that maps each of the 26 upper case letters of the alphabet
to two times the same letter, but lower case.

The upper case letters range from the code point `65` to `90`,
so first we need to create a list with `90` elements,
where each index should map to itself, so that other characters
are left intact:

```py
>>> translation_table = [i for i in range(91)]
```

Then, for the upper case letters, we need to update the corresponding
positions so that they map to the values we want:

```py
>>> for l in "ABCDEFGHIJKLMNOPQRSTUVWXYZ":
...     translation_table[ord(l)] = 2 * l.lower()
... 
>>> translation_table[60:70] 
[60, 61, 62, 63, 64, 'aa', 'bb', 'cc', 'dd', 'ee']
```

Now, we just need to call the method `str.translate`:

```py
>>> "Hey, what's UP?".translate(translation_table)
"hhey, what's uupp?"
```

Here is all of the code from this little example,
also making use of the `string` module, so that
I don't have to type all of the alphabet again:

```py
>>> from string import ascii_uppercase
>>> ascii_uppercase
'ABCDEFGHIJKLMNOPQRSTUVWXYZ'

>>> translation_table = [i for i in range(91)]
>>> for l in ascii_uppercase
...     translation_table[ord(l)] = 2 * l.lower()
... 
>>> translation_table[60:70] 
[60, 61, 62, 63, 64, 'aa', 'bb', 'cc', 'dd', 'ee']
>>> "Hey, what's UP?".translate(translation_table)
"hhey, what's uupp?"
```


# `str.maketrans`

Having seen the generic form of translation tables,
it is time to introduce `str.translate`'s best friend,
`str.maketrans`.

The method `str.maketrans` is a utility method that provides
for a convenient way of creating translation tables
that can be used with `str.translate`.

`str.maketrans` accepts up to 3 arguments,
so let's break them down for you.


## Single argument

The version of `str.maketrans` that only accepts one argument has
the purpose of making it simpler for us, users,
to define dictionaries that can be used with `str.translate`.

Why would that be useful?

As we have seen above, when using dictionaries as translation
tables we need to make sure that the keys of the dictionary
are the code points of the characters we want to replace.

This generally introduces some boilerplate,
because in the most common cases we _know_ the characters we want
to replace, not their code points,
so we need to do the conversion by hand previously,
or when defining the dictionary with `ord`.

This is ugly, just take a look at the example we used before:

```py
>>> "001011010101001".translate(
...     {ord("0"): "1", ord("1"): "0"}
... )
'110100101010110'
```

It would be lovely if we could just write the dictionary
in its most natural form:

```py
trans_table = {"0": "1", "1": "0"}
```

For this to work, we need to use `str.maketrans`:

```py
>>> "001011010101001".translate(
...     str.maketrans({"0": "1", "1": "0"})
... )
'110100101010110'
```


## Two arguments

If you look at the example I just showed,
we see that we did a very specific type of translation:
we replaced some characters with some other single characters.

This is so common, that the method `str.maketrans` can be used
to create translation tables of this sort.
For that, the first argument to `str.maketrans` should be a
string consisting of the characters to be replaced,
and the second argument is the string with the corresponding
new characters.

Redoing the example above:

```py
>>> "001011010101001".translate(
...     str.maketrans("01", "10")
... )
'110100101010110'
```

Here is another example where the two strings have
different characters, just for the sake of diversity:

```py
>>> "#0F45cd".translate(
...     str.maketrans("abcdef", "ABCDEF")
... )
'#0F45CD'
```

In this example we took a hexadecimal value representing
a colour and made sure all the letters were upper case.

(Of course we could have, and maybe should have,
done that with the method `str.upper`.)


## The third argument

Finally, the third argument to `str.maketrans` is simply
a string of all the characters that should be mapped to `None` or,
in other words, that should be removed altogether from the string.

Here is a little example:

```py
>>> "# 0F45cd".translate(
...     str.maketrans("abcdef", "ABCDEF", "# ")
... )
'0F45CD'
```


# Examples in code

Now that you have been introduce to the string methods `str.translate`
and `str.maketrans`,
I will share a couple of interesting use cases for these methods.

I will start with a personal use case, and then include three
use cases from the Python Standard Library.
These code examples should help you understand how the two
methods are used in the real world.


## Caesar cipher

[I wrote on Twitter][twitter-caesar-challenge],
asking people for their most Pythonic implementation of the Caesar cipher.

I defined the Caesar cipher as a function that takes two arguments.
The first, a string, specifies some text.
The second, an integer, specifies an integer key.
Then, the upper case letters of the argument string should be shifted,
along the alphabet, by the amount specified by the key.
All other characters should be left as-is:

```py
>>> caesar("ABC", 1)
'BCD'
>>> caesar("ABC", 13)
'NOP'
>>> caesar("ABC", 25)
'ZAB'
>>> caesar("HELLO, WORLD", 7)
'OLSSV, DVYSK'
```

Some time later, [I went to Twitter again][twitter-caesar-solution]
to comment on some straightforward solutions and to also share
the most elegant solution ever.

Can you guess what my Caesar implementation leverages?
If you said/thought `str.translate` and `str.maketrans`,
you are absolutely right!

Here is the nicest implementation of the Caesar cipher
you will ever see:

```py
def caesar(msg, key):
    return msg.translate(
        str.maketrans(ABC, ABC[key:] + ABC[:key])
    )
```

In the code above, `ABC` is a global constant that contains the
alphabet that is subject to change.
If we set `ABC = string.ascii_uppercase`, then we match
exactly the Caesar cipher that I defined in the beginning:

```py
>>> from string import ascii_uppercase
>>> ABC = ascii_uppercase
>>> def caesar(msg, key):
...     return msg.translate(
...         str.maketrans(ABC, ABC[key:] + ABC[:key])
...     )
... 
>>> caesar("HELLO, WORLD", 7)
'OLSSV, DVYSK'
```


## Sanitising file names

The Python Standard Library provides a module to work with ZIP archives,
and that module is called [`zipfile`][zipfile].

This module can be used, for example, to extract ZIP archives programmatically.

When you use `zipfile` on Windows and `zipfile` extracts an archive,
the module will look at the files that are being extracted and make
sure that those files have names that are illegal on Windows.
Can you guess what's the code that does this?
It's a piece of code using `str.maketrans` and `str.translate`!

Here it is:

```py
# In Lib/zipfile.py in Python 3.9.2

class ZipFile:
    # ...

    @classmethod
    def _sanitize_windows_name(cls, arcname, pathsep):
        """Replace bad characters and remove trailing dots from parts."""
        table = cls._windows_illegal_name_trans_table
        if not table:
            illegal = ':<>|"?*'
            table = str.maketrans(illegal, '_' * len(illegal))
            cls._windows_illegal_name_trans_table = table
        arcname = arcname.translate(table)
        # ...
```

The `arcname` is the name of the archive.
The first thing we do is fetch the `table` and see if it has been set.
If it has _not_ been set, then we set it for ourselves!

We define a series of illegal characters,
and then use `str.maketrans` to create a translation table
that translates them to underscores `_`:

```py
>>> illegal = ':<>|"?*'
>>> table = str.maketrans(illegal, '_' * len(illegal))
>>> table
{58: 95, 60: 95, 62: 95, 124: 95, 34: 95, 63: 95, 42: 95}
```

Then, we save this computed `table` for later and proceed
to translating the name of the archive, `arcname`.

This shows a straightforward usage of both `str.maketrans`
and `str.translate`.


## Whitespace munging

(I didn't know, so I Googled it: “to munge” means to manipulate data.)

Along the same spirit, Python's [`textwrap`][textwrap] module
(used to wrap text along multiple lines and to do other
related string manipulations)
uses `str.translate` to munge whitespace in the given text.

As a preprocessing step to wrapping a string,
we replace all sorts of funky whitespace characters with a simple blank space.

Here is how this is done:

```py
# In Lib/textwrap.py from Python 3.9.2

_whitespace = '\t\n\x0b\x0c\r '

class TextWrapper:
    # ...

    unicode_whitespace_trans = {}
    uspace = ord(' ')
    for x in _whitespace:
        unicode_whitespace_trans[ord(x)] = uspace

    # ...

    def _munge_whitespace(self, text):
        # ...
        if self.replace_whitespace:
            text = text.translate(self.unicode_whitespace_trans)
        return text
```

Notice that we start by hardcoding a series of whitespace characters.
Comments (that I omitted) explain why we do that.
Then, inside the `TextWrapper` class, we define the translation table by hand.
Later, in `_munge_whitespace`, we use that table to replace the funky
whitespace characters with the blank space.

You might be wondering why `str.maketrans` was not used here,
and I wouldn't know!
Can you rewrite the lines of code that define the translation table,
so that it uses `str.maketrans` instead?
It should be similar to the code from the previous example.


## Default replacement

If we peek at the source code for [IDLE], the IDE that ships with Python,
we can also find a usage of the method `str.translate`,
and this one in particular defines a custom object for the translation table.

Before showing you the code, let me tell you what it should do:
we want to create a translation table that

 - _preserves_ the whitespace characters `" \t\n\r"`;
 - maps "(", "[", and "{" to "(";
 - maps ")", "]", and "}" to ")"; and
 - maps _everything_ else to "x".

The point here is that we need to parse some Python code and we are only
interested in the structure of the lines,
while not so much in the actual code that is written.

By replacing code elements with "x", those "x"s can then be deduplicated.
When the "x"s are deduplicated the string becomes (much!) smaller
and the processing that follows becomes significantly faster.
At least that's what the comments around the code say!

To help in this endeavour, we will implement a class called `ParseMap`
that will be _very_ similar to a vanilla `dict`, with one exception:
when we try to access a `ParseMap` with a key it doesn't know,
instead of raising a `KeyError`, we return 120.
Why 120?
Because:

```py
>>> ord("x")
120
```

Assuming `ParseMap` is already defined, here is what using it could look like:

```py
>>> pm = ParseMap()
>>> pm 
{}
>>> pm[0] = 343
>>> pm["hey"] = (1, 4)
>>> pm
{0: 343, 'hey': (1, 4)}
>>> pm[999]
120
```

By implementing this behaviour of returning `120` by default,
we know that our translation table will map any character to "x"
by default.

Now that the idea was introduced, here is the code:

```py
# In Lib/idlelib/pyparse.py from Python 3.9.2

class ParseMap(dict):
    # [comments omitted for brevity]

    def __missing__(self, key):
        return 120  # ord('x')


trans = ParseMap.fromkeys(range(128), 120)
trans.update((ord(c), ord('(')) for c in "({[")  # open brackets => '(';
trans.update((ord(c), ord(')')) for c in ")}]")  # close brackets => ')'.
trans.update((ord(c), ord(c)) for c in "\"'\\\n#")  # Keep these.
```

In order to implement the “return 120 by default” behaviour,
all that was needed was to say that `ParseMap` inherits from `dict`,
and then we implement the `__missing__` dunder method.

Then, we initialise a translation table that already maps a bunch
of characters to 120.
We do that with the `dict.fromkeys` method:

```py
>>> dict.fromkeys("abc", 42)
{'a': 42, 'b': 42, 'c': 42}
>>> dict.fromkeys(range(3), "Hello, world!") 
{0: 'Hello, world!', 1: 'Hello, world!', 2: 'Hello, world!'}
```

The line

```py
trans = ParseMap.fromkeys(range(128), 120)
```

is there to explicitly map many common characters to "x",
which is supposed to speed up the translation process itself.

Then, the three lines that follow update the translation table
in such a way that the parenthesis, brackets, and braces,
are mapped like I said they would.

In the end, the translation behaves like this:

```py
>>> s = "(This [is]\tsome\ntext.)" 
>>> print(s)
(This [is]      some
text.)
>>> print(s.translate(trans))
(xxxxx(xx)xxxxx
xxxxx)
```


# Conclusion

Here's the main takeaway of this Pydon't, for you, on a silver platter:

 > “*When you need to replace several characters with other characters or strings,
 the method `str.translate` is your best friend.*”

This Pydon't showed you that:

 - the `str.translate` method replaces characters from an origin string with new characters or substrings;
 - the character translation is controlled by a translation table that can be any object that supports indexing by integers;
 - all characters (even emojis!) can be converted to a unique integer, and back, through the use of the built-in functions `ord` and `chr`;
 - the “code point” of a character is the integer that represents it;
 - Python uses the code points set by the [Unicode] standard, the most widely-used in the world;
 - the translation tables make use of the code points of characters to decide what is replaced by what;
 - in general, `str.translate` cannot be replaced with a series of calls to `str.replace`;
 - Python provides a utility method (`str.maketrans`) to help us create translation tables:
   - with a single argument, it can process dictionaries to have the correct format;
   - with two arguments, it builds a translation table that maps single characters to single characters; and
   - the third argument indicates characters that should be removed from the string. And
 - the `__missing__` dunder method controls how custom `dict` subclasses work when indexed with missing keys;

<!-- v -->
If you liked this Pydon't be sure to leave a reaction below and share this with your friends and fellow Pythonistas.
Also, [don't forget to subscribe to the newsletter][subscribe] so you don't miss
a single Pydon't!
<!-- ^ -->

[subscribe]: https://mathspp.com/subscribe
[manifesto]: /blog/pydonts/pydont-manifesto
[gumroad-pydonts]: https://gum.co/pydonts
[pydont-zip-up]: /blog/pydonts/zip-up
[Unicode]: https://home.unicode.org
[twitter-caesar-challenge]: https://twitter.com/mathsppblog/status/1424878368760242183
[twitter-caesar-solution]: https://twitter.com/mathsppblog/status/1425492527868694528
[zipfile]: https://docs.python.org/3/library/zipfile.html
[textwrap]: https://docs.python.org/3/library/textwrap.html
[IDLE]: https://docs.python.org/3/library/idle.html
