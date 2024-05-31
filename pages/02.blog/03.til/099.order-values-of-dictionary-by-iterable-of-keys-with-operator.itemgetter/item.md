Today I learned how to order the values of a dictionary according to an iterable of keys.

===

# Order values of dictionary by iterable of keys with `operator.itemgetter`

Today I needed to order the values of a dictionary according to another iterable that contained keys of that same dictionary.
For example, given the dictionary `{"a": 1, "b": 2, "c": 3}` and the iterable `"cab"`, I want the result `[3, 1, 2]`.

There are many different ways to do this, but I realised there is a nice way of doing it with `operator.itemgetter`:

```py
from operator import itemgetter
d = {"a": 1, "b": 2, "c": 3}
it = "cab"
print(itemgetter(*it)(d))  # (3, 1, 2)
```

What I liked about this approach is that I often forget that `operator.itemgetter` accepts multiple arguments that can be used to retrieve multiple values from its operand.

The `itemgetter` approach is great when you need to do a specific reordering multiple times.
For example, imagine you have a list of interesting government IDs and multiple sources of data:

```py
suspects = [7342, 1610, 4210]

names = {
    7342: "John Doe",
    9999: "Mary Smith",
    1610: "Alice Johnson",
    1234: "Charles Williams",
    4210: "Barbara Smith",
    5627: "Richard Hold",
}

addresses = {
    7342: "Sunshine Boulevard",
    9999: "Rain Square",
    1610: "Hail Street",
    1234: "Sandstorm Plaza",
    4210: "Cloudy Avenue",
    5627: "Misty Drive",
}
```

With the `itemgetter` approach, you can pull all the data together easily:

```py
from operator import itemgetter
suspect_data_retriever = itemgetter(*suspects)
print(list(
    zip(
        suspect_data_retriever(names),
        suspect_data_retriever(addresses),
    )
))
"""
[
    ('John Doe', 'Sunshine Boulevard'),
    ('Alice Johnson', 'Hail Street'),
    ('Barbara Smith', 'Cloudy Avenue')
]
"""
```

For the simple case of a one-off reordering / retrieval, [a list comprehension](/blog/pydonts/list-comprehensions-101) or a call to `map` with `dict.get` can do the trick:

```py
d = {"a": 1, "b": 2, "c": 3}
it = "cab"

print(list(
    map(d.get, it)
))  # [3, 1, 2]

print([d[item] for item in it])  # [3, 1, 2]
```
