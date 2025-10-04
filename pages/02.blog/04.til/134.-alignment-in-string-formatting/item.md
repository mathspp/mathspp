Today I learned how to use the equals sign to align numbers when doing string formatting in Python.

===

There are three main alignment options in Python's string formatting:

| Character | Meaning |
| | |
| `<` | align left |
| `>` | align right |
| `^` | centre |

However, numbers have a fourth option `=`.
On the surface, it looks like it doesn't do anything:

```py
x = 73

print(f"@{x:10}@")   # @        73@
print(f"@{x:=10}@")  # @        73@
```

But that's because `=` influences the alignment of the sign.
If I make `x` negative, we already see something:

```py
x = -73

print(f"@{x:10}@")   # @       -73@
print(f"@{x:=10}@")  # @-       73@
```

So, the equals sign `=` aligns a number to the right but aligns its sign to the left.
That may look weird, but I guess that's useful if you want to pad a number with 0s:

```py
x = -73

print(f"@{x:010}@")  # @-000000073@
```

In fact, there is a shortcut for this type of alignment, which is to just put a zero immediately to the left of the width when aligning a number:

```py
x = -73

print(f"@{x:010}@")  # @-000000073@
```

The zero immediately to the left changes the default alignment of numbers to be `=` instead of `>`.
