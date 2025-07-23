This article shows how to do base conversions in Python with the built-in int, how to write integer literals in other bases, and how to do base conversions in general.

===

<link rel="stylesheet" href="https://unpkg.com/@antonz/codapi@0.12.2/dist/snippet.css"/>
<script async src="https://unpkg.com/@antonz/codapi@0.12.2/dist/snippet.js"></script>


In this article I'll assume you know what a number base is and how there are multiple number bases.
My focus will be on showing you the tools that Python provides to work with multiple number bases, an in particular with the binary, octal, decimal, and hexadecimal, number bases.


## Bases change the representation, not the number

Before diving right into the tools that Python gives you to work with different number bases I want to stress that a number base doesn't change the underlying number we're talking about, only its _representation_.

For example, many people around the world have three meals per day: breakfast, lunch, and dinner.
It doesn't matter whether I write “three” in English, “três” in Portuguese, “3” in the decimal number base, or “11” in the binary number base.
We're always talking about the same number of meals: breakfast, lunch, and dinner.

This is very important!


## Built-in bases for integer literals

Python (and most programming languages) let you write integer literals in the decimal number base, and we typically don't even think about it.
Python also lets you write integer literals in three other bases:

 1. binary;
 2. octal; and
 3. hexadecimal.

To write an integer literal in any base other than the decimal base, you always start your integer literal with a `0` followed by the letter that identifies the base.
This is summarised in the table below:

| Base | Prefix |
| :- | :- |
| binary | `0b` |
| octal | `0o` |
| hexadecimal | `0x` |

Thus, all four assignments below create the same integer literal:

```py
svty_three = 73
svty_three_bin = 0b1001001
svty_three_oct = 0o111
svty_three_hex = 0x49
```

<codapi-snippet sandbox="python" editor="none" id="variable_assignment"></codapi-snippet>

Because [the base changes the representation but not the number](#bases-change-the-representation-not-the-number), printing any of the four variables will print `73`:

```py
print(svty_three)  # 73
print(svty_three_bin)  # 73
print(svty_three_oct)  # 73
print(svty_three_hex)  # 73
```

<codapi-snippet sandbox="python" editor="basic" depends-on="variable_assignment"></codapi-snippet>

In any of these bases, you can [use the underscore `_` to group digits to make the literals more readable](/blog/pydonts/usages-of-underscore).
For example, in the decimal base you can use the underscore `_` as the thousands separator:

```py
huge_number = 17_532_546_253_000_000
```


## Built-in functions for base conversion

Python contains 3 built-ins that let you convert integers to _string_ representations in the three other bases:

| Base | Built-in |
| :- | :- |
| binary | `bin` |
| octal | `oct` |
| hexadecimal | `hex` |

Here's example usages of all three:

```py
print(bin(73))  # 0b1001001
print(oct(73))  # 0o111
print(hex(73))  # 0x49
```

<codapi-snippet sandbox="python" editor="basic"></codapi-snippet>


Notice that these converting functions include the base prefix in the converted representation!

If you want to use these string representations to represent the number in its base, you can discard the prefix and then convert each digit.
The code below uses [a list comprehension](/blog/pydonts/list-comprehensions-101) and the built-in `bin` to convert an integer to a list of its binary digits:

```py
>>> [int(digit) for digit in bin(73)[2:]]
[1, 0, 0, 1, 0, 0, 1]
```


## String formatting in binary, octal, and hexadecimal

The prefix letters for the integer literals can also be used as formatting specifiers when doing string formatting.
Thus, using `b`, `o`, and `x`, will format a number in binary, octal, and hexadecimal, respectively.

These format specifiers work in f-strings:

```pycon
>>> f"{73:b}"
'1001001'
>>> f"{73:o}"
'111'
>>> f"{73:x}"
'49'
```

But they also work with the string method `format`:

```pycon
>>> "{:b}".format(73)
'1001001'
>>> "{:o}".format(73)
'111'
>>> "{:x}".format(73)
'49'
```

(If you're working with legacy code, `%o` and `%x` work but `%b` doesn't.
If you don't know what these `%` are for, excellent!
Don't even bother...)


## `int` for base conversion

The built-in `int` is your one-stop shop for converting numbers in different bases to integers.
By default, it assumes you're trying to convert strings to integers in base 10 and it won't work with integers represented in other bases:

```pycon
>>> int("73")
73
>>> int("0b1001001")
ValueError: invalid literal for int() with base 10: '0b1001001'
```

However, the built-in `int` has a parameter `base` that you can use to specify any base from 2 to 36:

```pycon
>>> int("0b1001001", base=2)
73
>>> int("0o111", base=8)
73
>>> int("0x49", base=16)
73
```

Notice that the base prefixes are not required:

```pycon
>>> int("0b1001001", base=2)  # With `0b` prefix.
73
>>> int("1001001", base=2)  # Without.
73
```

Like I said, the base can be any base from 2 to 36, it doesn't need to be one of `2`, `8`, `10`, or `16`:

```pycon
>>> int("rodrigo", base=36)  # Fun(ky)!
60247429944
```

The parameter `base` also accepts the value `0`, which is a special value and that tells `int` to guess the base from the base prefix:

```pycon
>>> int("0b1001001", base=0)
73
>>> int("0o111", base=0)
73
>>> int("0x49", base=0)
73
```


## Summary of tools to work with binary, octal, and hexadecimal

The table below shows examples of working with the binary, octal, and hexadecimal bases for the integer 73, summarising everything we've seen so far.

| Base | Base prefix | Literal notation | String formatting | Convert to integer |
| :- | :- | :- | :- | :- |
| binary | `0b` | `0b1001001` | `f"{73:b}"` | `int("1001001", base=2)`[^1] |
| octal | `0o` | `0o111` | `f"{73:o}"` | `int("111", base=8)`[^1] |
| hexadecimal | `0x` | `0x49` | `f"{73:x}"` | `int("49", base=16)`[^1] |

[^1]: the base prefix is optional. If you set `base=0`, the built-in `int` will need the base prefix to guess the base correctly.


## General base conversions

### Base restrictions

A number base doesn't have to be base 2, 8, 10, or 16.
A number base can be any integer greater than 1, even if it's greater than 36 (although `int` only supports up to `36`).

The reason why `int` only supports up to base 36 is because the 10 digits plus the 26 letters of the latin alphabet make up 36 characters, which are typically used to represent digits in bases up to 36.
(For example, colours in hexadecimal are ubiquously represented with the digits 0-9 and the letters A-F.)

We use letters to represent hexadecimal digits just to simplify the writing of numbers in that base and we define that the letters A-F have the values 10-15, respectively.
For hexadecimal, and for any other base, we can represent a number in that base as a list of its digits.
For example, the hexadecimal number `0x89af` would be `[8, 9, 10, 15]`.


### Converting an integer to any base

If we capitalise on this idea, we can write a function that converts any non-negative integer to any base greater than or equal to 2.
That's what the function `to_base` below does:

```py
def to_base(number, base):
    """Converts a non-negative number to a list of digits in the given base.

    The base must be an integer greater than or equal to 2 and the first digit
    in the list of digits is the most significant one.
    """
    if not number:
        return [0]

    digits = []
    while number:
        digits.append(number % base)
        number //= base
    return list(reversed(digits))

print(to_base(73, 10))  # [7, 3]
print(to_base(73, 2))   # [1, 0, 0, 1, 0, 0, 1]
print(to_base(73, 8))   # [1, 1, 1]
print(to_base(73, 16))  # [4, 9]

# Also works with other bases:
print(to_base(73, 11))  # [6, 7]
print(to_base(753_849, 1000))  # [753, 849]
print(to_base(753_849, 100))  # [75, 38, 49]
print(to_base(60_247_429_944, 36))  # [27, 24, 13, 27, 18, 16, 24]
```


### Converting from any base to an integer

Similarly, we can write a function that does the reverse calculation.
The function `from_base`, shown below, receives a list of digits and a base and computes the integer that's represented by those digits:

```py
def from_base(digits, base):
    """Converts a list of digits in the given base to an integer.

    The first digit is the most significant and the base is assumed to
    be an integer greater than or equal to 2.
    """
    power = 1
    number = 0
    for digit in reversed(digits):
        number += power * digit
        power *= base
    return number

from_base([7, 3], 10)  # 73
from_base([1, 0, 0, 1, 0, 0, 1], 2)  # 73
from_base([1, 1, 1], 8)  # 73
from_base([4, 9], 16)  # 73

from_base([6, 7], 11)  # 73
from_base([75, 38, 49], 100)  # 753_849
from_base([753, 849], 1000)  # 753_849
from_base([27, 24, 13, 27, 18, 16, 24], 36)  # 60247429944
```
