Today I learned how to allow my custom objects to be unpacked into keyword arguments like '**kwargs'.

===


# Unpacking kwargs with custom objects

If you have a dictionary, you can use the `**` syntax to unpack its keys and values.
For example, you can use that to merge two dictionaries:

```py
d_en = {1: "one", 2: "two"}
d_pt = {2: "dois", 3: "três"}
d = {**d_en, **d_pt}
print(d)  # {1: 'one', 2: 'dois', 3: 'três'}
```

This can also be used to unpack a dictionary into keyword arguments:

```py
def foo(first, middle, last):
    return f"{first} {middle} {last}"

name_bits = {
    "last": "Potter",
    "middle": "James",
    "first": "Harry",
}

print(foo(**name_bits))  # Harry James Potter
```

If you want to be able to use the syntax `**` to unpack your own classes into keyword value pairs, your class must implement the method `keys` and [the dunder method `__getitem__`](/blog/pydonts/dunder-methods).

The method `keys` must return an iterable with the keys your object is aware of and the dunder method `__getitem__` must be able to return the values associated with the given keys:

```py
class HarryPotter:  # A bit of a silly example!
    def keys(self):
        return ["first", "middle", "last"]

    def __getitem__(self, key):
        if key == "first":
            return "Harry"
        elif key == "middle":
            return "James"
        elif key == "last":
            return "Potter"
        else:
            raise KeyError()

print(foo(**HarryPotter()))  # Harry James Potter
```
