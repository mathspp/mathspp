Today I learned how I can use the method 'groupdict' from a regex match to get a dictionary with all named groups.

===


# `re.Match.groupdict`

Python regular expressions support named groups, which are introduced with the flag `?P<name>`:

```py
import re

date = re.compile(
    r"""(?x)  # Use ?x for verbose regex
    (?P<year>\d{4})  # year = 4 digits, e.g., 2025
    -
    (?P<month>\d{1,2})  # month = 1 or 2 digits, e.g., 01 or 1
    -
    (?P<day>\d{1,2})  # day = 1 or 2 digits
    """
)

match = date.match("2025-01-22")
print(match.group("year"))  # 2025
```

If you have named groups, you can then use the method `groupdict` to get a dictionary with all groups and their matches:

```py
match = date.match("2025-01-22")
print(match.groupdict())  # {'year': '2025', 'month': '01', 'day': '22'}
```

This is the counterpart to `groups` that produces a tuple with all groups in the order they appear:

```py
match = date.match("2025-01-22")
print(match.groups())  # ('2025', '01', '22')
```

While `groups` shows the values of all groups, regardless of whether they're named or not, `groupdict` will only show named groups:

```py
date2 = re.compile(r"(\d{4})-(\d{1,2})-(\d{1,2})")
match = date2.match("2025-01-22")
print(match.groups())  # ('2025', '01', '22')
print(match.groupdict())  # {}
```
