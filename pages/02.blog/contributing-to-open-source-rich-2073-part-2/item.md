This is the second part of my account of my contribution to solving issue 2073 of the Python open source library `rich`.

===


This is relevant when we use the function `pprint` to pretty print an object with a multi-line representation.
For example, if we go to the bottom of our script at `pretty.py` and change the three prints `print(pretty_repr(...))` to `pprint`:

```py
if __name__ == "__main__":
    # ...
    pprint(s)
    pprint(d)
    pprint(f)
```

We can run the code and verify that a bunch of vertical guidelines get added:

```py
A
 B
  C
   D
{
│   73: 42,
│   'carlota': A
│   │   │   │   B
│   │   │   │    C
│   │   │   │     D
}
{
│   True: False,
│   0: {
│   │   73: 42,
│   │   'carlota': A
│   │   │   │   │   B
│   │   │   │   │    C
│   │   │   │   │     D
│   }
}
```

The only print that doesn't get vertical guidelines is the first one,
probably just because the multi-line representation didn't happen to have much indentation.
So, I have to dig into the code and find where the guidelines get added.



[pydont-main]: /blog/pydonts/name-dunder-attribute#the-module-attribute-__name__
[will]: https://twitter.com/willmcgugan
[gh-2073]: https://github.com/Textualize/rich/issues/2073
[gh-first-findings]: https://github.com/textualize/rich/issues/2073#issuecomment-1105123499
[pr]: https://github.com/Textualize/rich/pull/2267#issuecomment-1126747409
