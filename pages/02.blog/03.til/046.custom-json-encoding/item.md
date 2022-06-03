Today I learned how to do custom JSON encoding in Python with the module `json`.

===

# How to encode Python objects as JSON

In Python, we use the module `json` whenever we need to work with JSON data.
This module makes it very easy to do so,
by means of the methods `json.dump` and `json.dumps`.

By using `json.dumps`, we can get a JSON document as a string,
based on the Python object we give it:

```py
import json

data = {
    "one_key": 73,
    "other_key": [
        True,
        False,
        18,
    ]
}

print(json.dumps(data))
```
```txt
{"one_key": 73, "other_key": [true, false, 18]}
```

The method `json.dump` is useful when you have a file you want to write the JSON to.

You can read more about `json`, `json.dump`, and `json.dumps` [in the `json` documentation][json].

Despite being quite useful, the module `json` has its limitations,
especially ones derived from the JSON standard itself.
For example, the JSON standard doesn't tell you how to handle complex numbers,
and so the module `json` doesn't know how to deal with them:

```pycon
>>> import json
>>> json.dumps(complex(2, 3))
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
  File "C:\Program Files\Python39\lib\json\__init__.py", line 231, in dumps
    return _default_encoder.encode(obj)
  File "C:\Program Files\Python39\lib\json\encoder.py", line 199, in encode
    chunks = self.iterencode(o, _one_shot=True)
  File "C:\Program Files\Python39\lib\json\encoder.py", line 257, in iterencode
    return _iterencode(o, 0)
  File "C:\Program Files\Python39\lib\json\encoder.py", line 179, in default
    raise TypeError(f'Object of type {o.__class__.__name__} '
TypeError: Object of type complex is not JSON serializable
```

So, what can the module `json` handle?


# Default JSON conversion of Python objects

According to [the `json` docs][json],
these are the types supported by default:

| Python | JSON |
| :- | :- |
| `dict` | object |
| `list`, `tuple` | array |
| `str` | string |
| `int`, `float`, `int`- & `float`-derived Enums | number |
| `True` | true |
| `False` | false |
| `None` | null |

If you want to convert anything that is not on the left column of that table,
you need to implement your own custom JSON encoder.


# Custom JSON encoding of Python objects

If you want to do custom JSON encoding of Python objects that are not included in the table above,
you will need to subclass `json.JSONEncoder` and you will need to override the method `.default`.

The method `json.JSONEncoder.default` is called for each object that the module `json` doesn't know how to encode by default.

For example, if you want to encode complex numbers in JSON, you could do this:

```py
import json


class JSONComplexEncoder(json.JSONEncoder):
    """JSON encoder that also knows how to encode complex numbers."""

    def default(self, obj):
        if isinstance(obj, complex):
            return {"real": obj.real, "imag": obj.imag}

        return super().default(obj)
```

! The reason we call `super().default(obj)` if the object provided isn't a complex number [is explained in the docs][json-encoder-default].
! Essentially, the base implementation is responsible for raising the `TypeError` when we fail to serialise an object.

Now that we have the class `JSONComplexEncoder`,
we can pass it in to the methods `json.dump` and `json.dumps`,
so that they can use this custom encoder we defined:

```pycon
>>> c = complex(2, 3)
>>> json.dumps(c, cls=JSONComplexEncoder)
'{"real": 2.0, "imag": 3.0}'
```

As we can see, the complex number `c` was properly converted to JSON.


[json]: https://docs.python.org/3/library/json.html
[json-encoder-default]: https://docs.python.org/3/library/json.html#json.JSONEncoder.default

That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
