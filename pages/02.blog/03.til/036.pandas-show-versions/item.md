Today I learned how to use the function `pandas.show_versions` to get system diagnostic information.

===

![A picture of panda (the mammal) with the words "pandas" and "show_versions()" written.](thumbnail.png "Background photo by Elena Loshina on Unsplash.")


# What is the pandas function `show_versions` for?

The function `pandas.show_versions` is a very useful function when you need to get some information about your system and/or the versions of installed packages that are related to pandas.

If you import pandas into your session and call the function `pandas.show_versions`,
you get something like this:

```py
>>> import pandas
>>> pandas.show_versions()
```
```txt
INSTALLED VERSIONS
------------------
commit           : bb1f651536508cdfef8550f93ace7849b00046ee
python           : 3.9.7.final.0
python-bits      : 64
OS               : Windows
OS-release       : 10
Version          : 10.0.22000
machine          : AMD64
processor        : Intel64 Family 6 Model 158 Stepping 10, GenuineIntel
byteorder        : little
LC_ALL           : None
LANG             : en_US.UTF-8
LOCALE           : English_United States.utf8

pandas           : 1.4.0
numpy            : 1.21.3
pytz             : 2021.3
dateutil         : 2.8.1
pip              : 22.0.4
setuptools       : 57.4.0
Cython           : None
pytest           : 6.2.5
pyxlsb           : None
s3fs             : None
scipy            : None
sqlalchemy       : None
tables           : None
tabulate         : None
xarray           : None
xlrd             : None
xlwt             : None
zstandard        : None
```

The first section contains information about your machine and the next section contains information about packages that are related to pandas.
In particular, the function `pandas.show_versions` tells you the version of pandas you are running.

Alternatively, you can use the parameter `as_json` to have the information printed in the JSON format:

```py
>>> pandas.show_versions(as_json=True)
```
```txt
{
  "system": {
    "commit": "bb1f651536508cdfef8550f93ace7849b00046ee",
    "python": "3.9.7.final.0",
    "python-bits": 64,
    "OS": "Windows",
    "OS-release": "10",
    "Version": "10.0.22000",
    "machine": "AMD64",
    "processor": "Intel64 Family 6 Model 158 Stepping 10, GenuineIntel",
    "byteorder": "little",
    "LC_ALL": null,
    "LANG": "en_US.UTF-8",
    "LOCALE": {
      "language-code": "English_United States",
      "encoding": "utf8"
    }
  },
  "dependencies": {
    "pandas": "1.4.0",
    "numpy": "1.21.3",
    "pytz": "2021.3",
    "dateutil": "2.8.1",
    "pip": "22.0.4",
    "tables": null,
    "tabulate": null,
    "xarray": null,
    "xlrd": null,
    "xlwt": null,
    "zstandard": null
  }
}
```

Alternatively, the parameter `as_json` accepts a string representing the path of a file where the JSON with this information should be dumped on.


In conclusion, you would typically use the function `show_versions` if you were doing something like filing a bug report;
`show_versions` isn't the kind of function you will actually use in your data analysis work.


# How to check the pandas version?

If you want to check the version of your pandas installation you can use the function `pandas.show_versions`,
but you can also **import pandas and then check the dunder attribute `__version__`**:

```py
>>> import pandas
>>> pandas.__version__
'1.4.0'
```

That's the fastest way because you don't have to read the whole information printed by `show_versions`.

That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
