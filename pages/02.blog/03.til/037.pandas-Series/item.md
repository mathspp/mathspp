Today I learned about the fundamental pandas data type `Series`.

===

![A picture of panda (the mammal) with the words "pandas" and "Series" written.](thumbnail.webp "Background photo by Elena Loshina on Unsplash.")


# What is a pandas `Series`?

A `Series` is one of the fundamental data types in pandas and is a one-dimensional container for data.
`Series` are also indexable, either through integer indices (like the `list` or `tuple` built-in types),
or through arbitrary hashable labels.

To create a `Series`, you just give it an iterable with the data you want:

```py
>>> import pandas as pd     
>>> pd.Series([10, 20])
0    10
1    20
dtype: int64
>>> pd.Series(range(3))  
0    0
1    1
2    2
dtype: int64
```

The output above shows two columns, where the first column gives the indices
(consecutive non-negative integers by default),
and the second column shows the data.

`Series` are printed vertically to align with the fact that typically `Series` contain related data
that you can often imagine as a _column_ in a table of data.


# How to define the labels of a pandas `Series`?

If you want to change the labels associated with your data,
you can use the argument `index` when creating a `Series`:

```py
>>> s = pd.Series(range(3), index=["a", "b", "c"])
>>> s
a    0
b    1
c    2
dtype: int64
>>> s["b"]
1
```

That's the most straightforward way to do it.
Probably, there are others!


# Non-unique `Series` labels

On top of the ability to support arbitrary (hashable) values for the labels of its values,
a `Series` does _not_ need unique labels.
When the labels are non-unique and you use a one of those labels to access the `Series`,
you access all of the values associated with that label:

```py
>>> s = pd.Series(range(3), index=["a", "b", "a"])
>>> s["a"]
a    0
a    2
dtype: int64
```

Contrast this with the way the built-in dictionaries work:

```py
>>> d = {"a": 0, "b": 1, "a": 2}
>>> d
{'a': 2, 'b': 1}
>>> d["a"]
2
```

Notice how the key `"a"` is only associated with the value `2` because keys in dictionaries must be unique.


That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
