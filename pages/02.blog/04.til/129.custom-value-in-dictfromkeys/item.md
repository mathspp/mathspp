Today I learned you can specify a custom value when using the class method `dict.fromkeys`.

===


## Default `dict.fromkeys` behaviour

If you have an iterable of keys, you can initialise a dictionary with them by using the class method `dict.fromkeys`, which creates a dictionary where all values are set to `None`:

```py
keys = ["name", "age", "address"]
person_info = dict.fromkeys(keys)

print(person_info)
"""
{
    'name': None,
    'age': None,
    'address': None,
}
"""
```


## Initiliasing with a custom value

However, apparently you can also set a custom value if you pass in a second argument to `dict.fromkeys`:

```py
keys = ["name", "age", "address"]
person_info = dict.fromkeys(keys, "")

print(person_info)
"""
{
    'name': "",
    'age': "",
    'address': "",
}
"""
```

This is an “obvious” argument to include, but I didn't know this was possible...
I wonder if it was there all along and I didn't know, or if it was added “recently”.


## Mutable value as the initial value

If you use a mutable value as the initial value, that mutable value will be shared across keys and you might end up with some funky situations:

```py
keys = ["a", "b", "c"]
my_dict = dict.fromkeys(keys, [])

my_dict["a"].append("Hello")
my_dict["b"].append("there")
print(my_dict["c"])  # ['Hello', 'there']
```
