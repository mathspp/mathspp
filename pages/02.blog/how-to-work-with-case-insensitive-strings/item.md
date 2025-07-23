This is a short and practical tutorial that guides you on how to work with case-insensitive strings in Python and teaches how to use the `str.lower`, `str.upper`, and `str.casefold` methods.

===

## What is case insensitivity?

Case-insensitivity is what allows you to do string operations without having to worry about whether characters are upper case or lower case.

For example, URLs are case-insensitive.
Whether you write

 - mathspp.com,
 - MATHSPP.COM, or
 - MaThSpP.cOm,

you will always end up at mathspp.com.

When programming, sometimes you need to do case-insensitive operations.

For example, when you create a new user, you may want to check if no other user has the same name, regardless of casing.
In other words, you may not want the possibility of having three different users with the names "rodrigo", "Rodrigo", and "RODRIGO".

So,


## How to work with case-insensitive strings in Python?

Suppose you want to create a check for new email users:
a user can only create a new address if that address is different from all other existing addresses.
However, email addresses are also case-insensitive, so you write a function `is_new_email` that accepts the address that the user wants to use and returns a Boolean that determines if the new address is valid or not.

Here is what that could look like:

```py
users = [
    "johndoe@gmail.com",
    "EXAMPLE@domain.com",
    "MARY@GMAIL.COM",
]

def is_new_email(address):
    for user in users:
        if ...:
            return False
    return True
```

How would you fill in the `...`?
How can you finish that function so that these tests now work:

```py
assert is_new_email("john@gmail.com")
assert is_new_email("example@gmail.com")

assert not is_new_email("JOHNdoe@gmail.com")
assert not is_new_email("JOHNDOE@GMAIL.COM")
assert not is_new_email("EXAmple@DOMain.com")
assert not is_new_email("mary@gmail.com")
```


### Don't use `str.lower` for caseless comparison

If your answer was to write something along the lines of `if user.lower() == address.lower():`, then you made a mistake.
It is a very honest mistake, though, and I will explain why I say that is a mistake.

Because of my culture and the place I was born in, I am used to 26 nice characters that have their nice and tidy uppercase and lowercase versions:

```py
>>> import string
>>> string.ascii_uppercase
'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
>>> string.ascii_lowercase
'abcdefghijklmnopqrstuvwxyz'
```

However, not all languages are like this.
For example, German has this interesting character: "ß".
This character is a lowercase character:

```py
>>> "ß".lower()
'ß'
```

However, this character is equivalent to "ss"!
So, when you convert "ß" to uppercase, you get "SS" back:

```py
>>> "ß".upper()
'SS'
```

Now, who can guess the output of the next function call:

```py
>>> "SS".lower()
...
```

`"SS".lower()` will return `"ss"` and not `"ß"`!

Here is a short script you can copy and paste into your REPL to see more characters that suffer from this:

```py
for i in range(65535):
    try: c = chr(i)
    except Exception: continue
    if c.lower() == c and c.upper().lower() != c: print(i, c)

"""
Prints characters such as:
181 µ
223 ß
305 ı
329 ŉ
383 ſ
496 ǰ
837 ͅ
## and many more...
"""
```

These interesting characters can cause quite some trouble!

Of course, you can restrict email addresses to only be composed of ASCII characters (the ones in `string.ascii_lowercase`) but you can't always run away from your problems by restricting the valid letters!


### Don't use `str.upper` for caseless comparison

For a pretty similar reason, you shouldn't be using `str.upper` when you want to do caseless comparison.
So, the answer to the challenge above was _not_ `if user.upper() == address.upper():` because of a similar reason.

Here is a script that is very similar to the one above.
It prints characters that are already uppercase, but for which their lowercase version can't be uppercased back to the same character:

```py
for i in range(65535):
    try: c = chr(i)
    except Exception: continue
    if c.upper() == c and c.lower().upper() != c: print(i, c)

"""
Prints these characters:
304 İ
1012 ϴ
7838 ẞ
8486 Ω
8490 K
8491 Å
"""
```


### How to do caseless comparison in Python

The method `str.casefold` is the method that you want to use when you need to do caseless, or case-insensitive, comparisons in Python.
This method is similar to the method `str.lower`, but it does some extra normalisation to prevent the issues we have seen above.

For ASCII inputs, the two methods look exactly the same:

```py
>>> sentence = "THE QUICK brown FoX jumps OVER the LaZy dog."
>>> sentence.lower()
'the quick brown fox jumps over the lazy dog.'
>>> sentence.casefold()
'the quick brown fox jumps over the lazy dog.'
```

However, for the funny characters that we saw above, `str.casefold` works differently:

```py
>>> word = "straße"  # "street" in German
>>> word.lower()
'straße'
>>> word.casefold()
'strasse'
```

So, if you have to check if two addresses are the same and you need to do a caseless comparison, you know you will have to use `str.casefold`:

```py
def addresses_match(new, old):
    return new.casefold() == old.casefold()

address_in_database = "Imaginary Straße, 27"
new_address = "IMAGINARY STRASSE, 27"

print(addresses_match(new_address, address_in_database))  # True
```

Now that we know how to use `str.casefold`, we can do some really interesting things.
For example, we can build [case-insensitive dictionaries][caseless-dict] or [case-insensitive sets][caseless-set]


## How to implement a case-insensitive dictionary in Python

To implement a case-insensitive dictionary in Python, we need to use the string method `str.casefold` whenever we are setting, getting, or deleting a key from the dictionary.

For this exercise, we will implement a class `CaseInsensitiveDict` that will behave like this:

```py
class CaseInsensitiveDict(dict):
    ...


d = CaseInsensitiveDict()
print(d)  # {}

d["Rodrigo"] = "Rodrigo"
print(d["RODRIGO"])  # Rodrigo
del d["rOdRiGo"]
print(d)  # {}

d["straße"] = "street"
d["STRASSE"] = "STREET"
print(d)  # {'strasse': "STREET"}
```

Dictionaries use [dunder methods][dunder-methods] when accessing keys, when setting keys to new values, and when deleting keys from the dictionary:

 - the dunder method `__setitem__(key, value)` is called when we access the dictionary to set an item, e.g. with `d[key] = value`;
 - the dunder method `__getitem__(key)` is called when we access the dictionary to get a value back, e.g. with `print(d[key])`; and
 - the dunder method `__delitem__(key)` is called when we try to delete a key and the corresponding value from the dictionary, e.g., with `del d[key]`.

So, we need to override these dunder methods and make sure to use `str.casefold` to normalise the string keys before using them.
To make our lives easier, we will make our class `CaseInsensitiveDict` inherit from `dict`.
That way, we will have to worry about using `str.casefold` but then, the actual behaviour of setting, getting, and deleting keys can be deferred back to the class `dict`.

!!! This is a very common pattern in object-oriented programming: you subclass a class that almost works they way you want, you make some tweaks, and you defer as much work as possible to the parent class.

Here is a possible implementation of a case-insensitive dictionary:

```py
class CaseInsensitiveDict(dict):
    """Case-insensitive dictionary implementation."""

    def __getitem__(self, key):
        return dict.__getitem__(self, key.casefold())

    def __setitem__(self, key, value):
        return dict.__setitem__(self, key.casefold(), value)

    def __delitem__(self, key):
        return dict.__delitem__(self, key.casefold())
```

This implementation shows the general pattern of using `str.casefold` and deferring back to `dict` to do most of the heavy lifting.

As shown before, it works like this:

```py
d = CaseInsensitiveDict()
print(d)  # {}

d["Rodrigo"] = "Rodrigo"
print(d["RODRIGO"])  # Rodrigo
del d["rOdRiGo"]
print(d)  # {}

d["straße"] = "street"
d["STRASSE"] = "STREET"
print(d)  # {'strasse': "STREET"}
```

Here is an extra challenge (not an easy one!):
can you implement a case-insensitive dictionary that stores keys with their _original_ casing, rather than with the casefolded casing?[^1]
After you do that, run this code:

```py
d = CaseInsensitiveDict()
print(d)  # {}

d["Rodrigo"] = "Rodrigo"
print(d)  # {'Rodrigo': 'Rodrigo'}
print(d["RODRIGO"])  # Rodrigo
del d["rOdRiGo"]
print(d)  # {}

d["straße"] = "street"
print(d)  # {'straße' : 'street'}
d["STRASSE"] = "STREET"
print(d)  # {'straße': "STREET"}
```


## How to implement a case-insensitive set in Python

To implement a case-insensitive dictionary in Python, we need to use the string method `str.casefold` whenever we manipulate elements of the set, for example:

 - when adding a new element with `set.add`;
 - when removing/discarding elements with `set.remove`/`set.discard`; or
 - when checking if an element is in the set with `value in set`.

To exemplify how this could be done, let us implement a `CaseInsensitiveClass` that can add items, remove items, and check for item membership, in a case-insensitive way.

This is what the class will be able to do:

```py
class CaseInsensitiveSet(set):
    # ...

s = CaseInsensitiveSet()

s.add("Rodrigo")
s.add("mathspp")
s.add("RODRIGO")

print(s)  # CaseInsensitiveSet({'rodrigo', 'mathspp'})
print("RODRIGO" in s)  # True

s.discard("MaThSpP")  # Try to remove "mathspp"
print(s)  # CaseInsensitiveSet({'rodrigo'})

s.discard("mathspp")  # Try to remove "mathspp"
print(s)  # CaseInsensitiveSet({'rodrigo'})

s.add("mathspp")
s.remove("rodrigo")  # Remove "rodrigo" and error if not present
print(s)  # CaseInsensitiveSet({'mathspp'})
```

To implement the class `CaseInsensitiveSet`, we will follow an approach that is very similar to the one employed above to implement a [case-insensitive dictionary][caseless-dict].
We just need to be aware that an expression like `value in set` depends on the [dunder method][dunder-methods] `__contains__`.

We will inherit from the built-in `set` and we will override the methods that we are interested in.
Then, we just need to use the string method `str.casefold` before deferring to the original methods that do value insertion/removal/membership testing.

Here is an example implementation of the class `CaseInsensitiveSet`:

```py
class CaseInsensitiveSet(set):
    def add(self, value):
        return set.add(self, value.casefold())

    def discard(self, value):
        return set.discard(self, value.casefold())

    def remove(self, value):
        return set.remove(self, value.casefold())

    def __contains__(self, value):
        return set.__contains__(self, value.casefold())
```

As shown before, it works like this:

```py
s = CaseInsensitiveSet()

s.add("Rodrigo")
s.add("mathspp")
s.add("RODRIGO")

print(s)  # CaseInsensitiveSet({'rodrigo', 'mathspp'})
print("RODRIGO" in s)  # True

s.discard("MaThSpP")  # Try to remove "mathspp"
print(s)  # CaseInsensitiveSet({'rodrigo'})

s.discard("mathspp")  # Try to remove "mathspp"
print(s)  # CaseInsensitiveSet({'rodrigo'})

s.add("mathspp")
s.remove("rodrigo")  # Remove "rodrigo" and error if not present
print(s)  # CaseInsensitiveSet({'mathspp'})
```

This class has a shortcoming, though.
Because it inherits from `set`, it defines all the methods that `set` defines.
For example, you could try adding multiple values to a `CaseInsensitiveSet` with the method `set.update`:

```py
s = CaseInsensitiveSet()

s.update(
    ["Rodrigo", "rodrigo", "RODRIGO"]
)

print(s)  # ?
```

What is the output of running the code above?
Here it is:

```py
print(s)  # CaseInsensitiveSet({'RODRIGO', 'Rodrigo', 'rodrigo'})
```

What is the problem here?
The problem is that we didn't override `set.update` with `CaseInsensitiveSet`, so our code uses the regular `set.update` method that doesn't care about the casing of strings.

! In case you are wondering, the issue with our implementation of `CaseInsensitiveDict` is not as obvious, but it is essentially the same thing.

To fix this, you have one of three options:

 1. you implement all of the `set` methods in `CaseInsensitiveSet`;
 2. you prevent the user from calling methods that are defined in `set` but aren't in `CaseInsensitiveSet`; or
 3. you don't inherit from `set` directly.

A possible approach for 3. could look like this:

```py
class CaseInsensitiveSet2:
    def __init__(self):
        self._set = set()

    def add(self, value):
        return self._set.add(value.casefold())

    def discard(self, value):
        return self._set.discard(value.casefold())

    def remove(self, value):
        return self._set.remove(value.casefold())

    def __contains__(self, value):
        return value in self._set
```

This might look _very_ similar to what we had before, but running the same example shows one immediate difference:

```py
s = CaseInsensitiveSet2()

s.add("Rodrigo")
s.add("mathspp")
s.add("RODRIGO")

print(s)  # <__main__.CaseInsensitiveSet object at 0x000001D87F6FBCD0>
print("RODRIGO" in s)  # True

s.discard("MaThSpP")
print(s)  # <__main__.CaseInsensitiveSet object at 0x000001D87F6FBCD0>

s.discard("mathspp")
print(s)  # <__main__.CaseInsensitiveSet object at 0x000001D87F6FBCD0>

s.add("mathspp")
s.remove("rodrigo")
print(s)  # <__main__.CaseInsensitiveSet object at 0x000001D87F6FBCD0>
```

The contents of the internal `_set` are correct but, on the outside, we get this weird output because we didn't implement neither the [dunder method `__str__`][dunder-str-repr] nor the [dunder method `__repr__`][dunder-str-repr].

Similarly, we wouldn't be able to iterate over instances of `CaseInsensitiveSet2` without implementing the appropriate methods, whereas `CaseInsensitiveSet` inherited that behaviour from `set`.

All in all, I just want you to be mindful of the different implications of each alternative.


## Conclusion

To conclude, the string method `str.casefold` exists to allow you to do case-insensitive operations and to work in caseless scenarios.
In particular, working with the string methods `str.lower` or `str.upper` may fail if you are working with characters other than the ASCII letters.

Finally, the method `str.casefold` can power many interesting applications and you saw how to use `str.casefold` to implement:

 - a [case-insensitive dictionary][caseless-dict]; and
 - a [case-insensitive set][caseless-set].


[^1]: Feel free to leave a comment with your thoughts, drop me an email, or tweet at me @mathsppblog.

[caseless-dict]: #how-to-implement-a-case-insensitive-dictionary-in-python
[caseless-set]: #how-to-implement-a-case-insensitive-set-in-python
[dunder-methods]: /blog/pydonts/dunder-methods
[dunder-str-repr]: /blog/pydonts/str-and-repr
