This article gives an overview of the tools available in the module `enum` and how to use them.

===


# Module `enum` overview

## Introduction

The standard library module `enum` is used to create enumerations, objects that make it convenient to work with groups of related constant values.

Enumerations are also commonly used when you need a programmatic way to distinguish between different values without caring about the concrete values.
This is often the case when you have functions that accept Booleans, integers, or string values, as configuration options for those functions.

This article provides an overview of the module `enum` and the tools it provides so that you can work with enumerations ergonomically.
The article is structured in four sections, ordered from the most fundamental and commonly-useful tools of the module `enum` to the most rarely-needed tools:

 1. The [first section covers the absolute bare minimum to work with enumerations, the class `Enum` and `auto`](#how-to-create-enumerations-with-enum-and-auto).
 2. The [second section covers string enumerations with `StrEnum`, a very common type of specialised enumeration](#string-enumerations).
 3. The [third section covers specialised enumeration types](#other-specialised-enumeration-types).
 4. The [fourth and final section covers convenience tools that the module provides](#convenience-tools).


## How to create enumerations with `Enum` and `auto`

### Enumerations with concrete values

Consider the signature for the function below, that connects to a weather API to fetch the temperature at a given location:

```py
def get_temperature(city, unit):
    ...

print(get_temperature("Lisbon", "celsius"))  # 18.0
print(get_temperature("Lisbon", "fahrenheit"))  # 64.4
```

The parameter `unit` can take one of two values and it specifies the unit in which the temperature is returned:

| Value | Unit of the result |
| | |
| `"celsius"` | Celsius |
| `"fahrenheit"` | Fahrenheit |

Working with arbitrary, loose string values works, but has a few issues:

 - typos in the string values are hard to prevent and debug;
 - it's easy to forget the actual spelling (was it with a capital “F”..?);
 - if you use type hints, the type hints tell you nothing about the valid values/the type hint makes it look like more values are valid than those that are actually valid; and
 - you have the burden to document explicitly what values are valid.

It's because of situations like this that enumerations exist:

 - enumerations let you group related values together;
 - enumerations are code objects that IDEs pick up on, so you get autocomplete and typos are caught by your IDE;
 - you can specify that a parameter expects values from an enumeration, letting you narrow down the values that static type checking will accept; and
 - in the worst case scenario, the enumeration itself documents the valid values in a readable way.

To create an enumeration, you inherit from the class `Enum` from the module `enum`[^1].
Then, you create a class variable for each different value that you want to use:

```py
from enum import Enum

class TempUnit(Enum):
    CELSIUS = "celsius"
    FAHRENHEIT = "fahrenheit"

def get_temperature(city, unit):
    ...

print(get_temperature("Lisbon", TempUnit.CELSIUS))  # 18.0
print(get_temperature("Lisbon", TempUnit.FAHRENHEIT))  # 64.4
```

[^1]: Technically speaking, you can also create enumerations programmatically in a functional way, but IDE support for those is limited (if not non-existent) and the `class`-based approach is preferred.

The enumeration members are the singleton values that you can use.
In this case, they're `TempUnit.CELSIUS` and `TempUnit.FAHRENHEIT`.
The members' names are typically written all in uppercase because they're constants: you won't change their values during the execution of the program.

Enumeration members know their own name and associated value, which you can access with the attributes `name` and `value`:

```py
print(TempUnit.CELSIUS)  # TempUnit.CELSIUS
print(repr(TempUnit.CELSIUS))  # <TempUnit.CELSIUS: 'celsius'>

print(TempUnit.CELSIUS.name)  # CELSIUS
print(TempUnit.CELSIUS.value)  # celsius
```

You'd need this, for example, if the API in question required the unit as a query parameter in the request URL:

```py
def get_temperature(city, unit):
    req_url_parameters = f"?city={city}&temp={unit.value}"
    print(req_url_parameters)
    ...


get_temperature("Lisbon", TempUnit.CELSIUS)  # ?city=Lisbon&temp=celsius
```


### Automatic values with `auto`

In many scenarios, enumerations are good enough if their members map to different successive integers.
For example, it's easy to look at the enumeration `Weekday` from the snippet below and image that it might be useful in programs that work with calendar dates, scheduling, and similar situations:

```py
from enum import Enum

class Weekday(Enum):
    SUNDAY = 1
    MONDAY = 2
    TUESDAY = 3
    WEDNESDAY = 4
    THURSDAY = 5
    FRIDAY = 6
    SATURDAY = 7
```

In cases where you want enumeration members to be numbered successively, starting at 1, you can use the shortcut `auto`:

```py
from enum import Enum, auto

class Weekday(Enum):
    SUNDAY = auto()
    MONDAY = auto()
    TUESDAY = auto()
    WEDNESDAY = auto()
    THURSDAY = auto()
    FRIDAY = auto()
    SATURDAY = auto()
```

This might feel like a bad deal because you're typing more but `auto` is more robust.
When numbering options by hand you might accidentally skip a value, repeat a value, or forget to update the values when you delete/reorder members.
With `auto`, none of that happens.

`auto` will assign a unique integer for each member, typically starting at `1` and increasing successively:

```py
print(Weekday.SUNDAY.value)  # 1
print(Weekday.SATURDAY.value)  # 7
```


### Enumeration niceties

Enumerations provide some additional niceties to make it easier to work with them.
The class of an enumeration can be queried for the number of members it contains and you can iterate over it to check all the members and their values:

```py
print(len(TempUnit))  # 2
print(list(TempUnit))
# [<TempUnit.CELSIUS: 'celsius'>, <TempUnit.FAHRENHEIT: 'fahrenheit'>]
```

```py
print(len(Weekday))  # 7
print(list(Weekday))
# [<Weekday.SUNDAY: 1>, <Weekday.MONDAY: 2>, <Weekday.TUESDAY: 3>,
#  <Weekday.WEDNESDAY: 4>, <Weekday.THURSDAY: 5>, <Weekday.FRIDAY: 6>,
#  <Weekday.SATURDAY: 7>]
```

You can also fetch an enumeration member programmatically from its name or from its value, respectively by indexing into the enumeration class with the member name or by instantiating it with the member value:

```py
# Access the member by name:
print(Weekday["SATURDAY"])  # Weekday.SATURDAY

# Access the member by value:
print(Weekday(7))  # Weekday.SATURDAY
```

However, the instantiation syntax might look misleading, given that enumeration members are singletons:

```py
# a, b, c, and d, are all the same object.
a = Weekday.SATURDAY
b = Weekday["SATURDAY"]
c = Weekday(7)
d = Weekday(7)

assert a is b
assert b is c
assert c is d
```


## String enumerations

String enumerations are enumerations for which the members' values are strings.

```py
from enum import StrEnum

class TempUnit(StrEnum):
    CELSIUS = "celsius"
    FAHRENHEIT = "fahrenheit"
```

The difference between a string enumeration (inheriting from `StrEnum`) and an enumeration that uses string values (like the initial version of `TempUnit`, that inherited from `Enum`) is that the members of a string enumeration can be used directly as strings:

```py
print(TempUnit.CELSIUS.capitalize())  # Celsius
print("Degrees " + TempUnit.CELSIUS)  # Degrees celsius
print(TempUnit.FAHRENHEIT.split("e"))  # ['fahr', 'nh', 'it']
```

If you use a regular enumeration that inherits from `Enum`, these operations could only be performed on the values of the enumeration members and not on the members directly:

```py
from enum import Enum

class TempUnitEnum(Enum):
    CELSIUS = "celsius"
    FAHRENHEIT = "fahrenheit"

# The value is still a string:
print(TempUnitEnum.FAHRENHEIT.value.split("e"))  # ['fahr', 'nh', 'it']

# The member itself can't be treated like a string:
print(TempUnitEnum.FAHRENHEIT.split("e"))
# AttributeError: 'TempUnitEnum' object has no attribute 'split'
```


### Automatic string values

When used within a string enumeration, `auto` will generate a member value that is equal to the member name in lowercase letters.
Hence, the version of the string enumeration `TempUnit` seen above could also be defined as

```py
from enum import StrEnum, auto

class TempUnit(StrEnum):
    CELSIUS = auto()
    FAHRENHEIT = auto()

assert TempUnit.CELSIUS.value == "celsius"
assert TempUnit.FAHRENHEIT.value == "fahrenheit"
```


### String enumerations before Python 3.11

The class `StrEnum`, used for string enumerations, was added to the module `enum` in Python version 3.11.
In earlier versions of Python, you can achieve a similar behaviour by inheriting from `str` and `Enum`:

```py
from enum import Enum, auto

class TempUnitKindaStr(str, Enum):
    CELSIUS = "celsius"
    FAHRENHEIT = "fahrenheit"

print(TempUnitKindaStr.FAHRENHEIT.split("e"))  # ['fahr', 'nh', 'it']
```

However, note that `auto` will create integer member values, since the enumeration inherits from `Enum`:

```py
from enum import Enum, auto

class TempUnitKindaStr(str, Enum):
    CELSIUS = auto()
    FAHRENHEIT = auto()

print(TempUnitKindaStr.FAHRENHEIT.value)  # 2
```


## Other specialised enumeration types

### Integer enumerations

The class `IntEnum` creates enumerations in which the members can be used directly as integers.
However, the result of such an operation will never be an enumeration member, even if the result is an integer that is the value of one of the members:

```py
from enum import IntEnum

class SomeIntegers(IntEnum):
    ONE = 1
    TWO = 2
    THREE = 3

print(repr(SomeIntegers.TWO))  # <SomeIntegers.TWO: 2>
print(repr(SomeIntegers.ONE + SomeIntegers.ONE))  # 2
```

Members of an enumeration created with the class `IntEnum` can be used wherever an integer can be used.

Unlike [string enumerations with `StrEnum`](#string-enumerations), integer enumerations have been around for a while and thus can be used in any Python 3 version.


### Flags

Enumerations can also be used to represent sets of Boolean flags that can be manipulated with the Boolean operators AND (`&`), OR (`|`), XOR (`^`), and INVERT (`~`).
You can create flag enumerations with the class `Flag` and member values should be powers of two:

```py
from enum import Flag

class NotificationMethod(Flag):
    IN_APP = 1
    PUSH = 2
    EMAIL = 4
    SMS = 8

user_settings = NotificationMethod.IN_APP | NotificationMethod.PUSH
if NotificationMethod.EMAIL not in user_settings:
    print("Email notifications are disabled.")
# Output: Email notifications are disabled.
```

If you inspect the variable `user_settings`, you see that it shows how it's combining the members `IN_APP` and `PUSH`, and its current value is 3:

```py
print(user_settings) # <NotificationMethod.IN_APP|PUSH: 3>
```

Instead of defining the powers of two by hand, it is much more convenient to set the member values automatically with `auto`:

```py
from enum import Flag, auto

class NotificationMethod(Flag):
    IN_APP = auto()
    PUSH = auto()
    EMAIL = auto()
    SMS = auto()

user_settings = NotificationMethod.IN_APP | NotificationMethod.PUSH
print(user_settings) # <NotificationMethod.IN_APP|PUSH: 3>
```

Additionally, if you would like to be able to use flag members as integers directly, the module `enum` also provides a class `IntFlag`.
The class `IntFlag` is to `Flag` what the class `IntEnum` is to `Enum`.

For an example of a flag enumeration in the real world, check `re.RegexFlag`:

```py
from re import RegexFlag

print(RegexFlag)  # <flag 'RegexFlag'>
print(RegexFlag.ASCII | RegexFlag.MULTILINE)  # re.ASCII|re.MULTILINE
```


### Enumerations with aliases

Different enumeration members can have the same value, in which case the two (or more) members that share the same value are considered aliases of one another:

```py
class EnumWithAliases(Enum):
    ONE = 1
    TWO = 2
    THREE = 3
    UNO = 1  # Spanish
    UM = 1  # Portuguese

print(EnumWithAliases.ONE == EnumWithAliases.UNO)  # True
```

When iterating over the enumeration class, you only get the unique members:

```py
print(list(EnumWithAliases))
# [<EnumWithAliases.ONE: 1>, <EnumWithAliases.TWO: 2>, <EnumWithAliases.THREE: 3>]
```

If you want to check all the members your enumeration has, including aliases, use the dunder attribute `__members__`, which maps member names to members:

```py
print(EnumWithAliases.__members__)
# {
#     'ONE': <EnumWithAliases.ONE: 1>,
#     'TWO': <EnumWithAliases.TWO: 2>,
#     'THREE': <EnumWithAliases.THREE: 3>,
#     'UNO': <EnumWithAliases.ONE: 1>,
#     'UM': <EnumWithAliases.ONE: 1>,
# }
```

If you would like to explicitly disable aliases, you can [use `enum.verify` to ensure your enumeration only has unique values](#check-value-uniqueness).


### Flags with aliases

Flag enumerations allow you to define aliases to certain masks – or groups – of flags.
For example, consider the following flag `Colour`, based on the RGB colour model:

```py
from enum import Flag

class Colour(Flag):
    RED = 1
    GREEN = 2
    BLUE = 4
```

Given these three flags, you may want to create aliases for common flag combinations, like:

 - a combination of all colour flags, which would make sense to call `white` (the colour white is `(255, 255, 255)` in RGB); and
 - the absence of all colour flags, which would make sense to call `black` (the colour black is `(0, 0, 0)` in RGB).

```py
black = Colour(0)
white = Colour.RED | Colour.GREEN | Colour.BLUE  # or Colour(7)
```

In the snippet above, the instantiation `Colour(0)` is the alias for “no flags set”, which is what you'd get if you intersected two disjoin sets of flags, for example:

```py
print(Colour.RED & Colour.GREEN)  # Colour(0)
```

The alias `white` has the numeric value `7`, because it's what you get if you combine the values `1`, `2`, and `4`, respectively from the members `RED`, `GREEN`, and `BLUE`:

```py
print(white.value)  # 7
```

This allows you to give names to flag combinations, or masks, that you will be using a lot.
But, if you are going to be using these aliases a lot, then it may make sense to define them within the enumeration:

``` {.py #colour-enum }
class Colour(Flag):
    BLACK = 0
    RED = 1
    GREEN = 2
    BLUE = 4
    WHITE = 7
```

This makes it so that the aliases are used automatically when certain operations with your flag members result in values that match those aliases.
Here are two examples:

```py
print(Colour.RED & Colour.GREEN)  # Colour.BLACK
print(Colour.RED | Colour.GREEN  | Colour.BLUE)  # Colour.WHITE
```


## Convenience tools

This section covers some convenience tools that the module `enum` provides to make it more ergonomic to work with enumerations.


### Check value uniqueness

The module `enum` provides a decorator `unique` for enumerations that raises an exception if the enumeration contains any aliases.
In other words, `unique` errors if the member values of an enumeration are not unique.

Going back to the enumeration `EnumWithAliases` from the [section on enumerations with aliases](#enumerations-with-aliases), wrapping that enumeration in the decorator `unique` raises an exception `ValueError`:

```py
from enum import Enum, unique

@unique
class EnumWithAliases(Enum):
    ONE = 1
    TWO = 2
    THREE = 3
    UNO = 1  # Spanish
    UM = 1  # Portuguese

"""Output:
ValueError: duplicate values found in <enum 'EnumWithAliases'>: UNO -> ONE, UM -> ONE
"""
```

As seen in the example above, the exception will let you know what are the aliases that are causing trouble.


### Verifying constraints

In Python 3.11, the decorator `verify` was added to the module `enum`.
Like `unique`, it's a decorator for enumeration classes and it can be used to enforce a number of restrictions on the enumeration that is decorated.

The decorator `verify` works with the following options:

 - `UNIQUE` – ensures that member values are unique (same as using `unique`).
 - `CONTINUOUS` – ensures that the member values form a contiguous sequence without any missing values. (Only useful for enumerations with integer values.)
 - `NAMED_FLAGS` – ensures that any flag groups are only made up of named flags. (Only useful for flag enumerations.)

The next subsections show [examples of `CONTINUOUS`](#examples-with-verifycontinuous) and [examples of `NAMED_FLAGS`](#examples-with-verifynamed_flags) to make it easier to understand how they work.


#### Examples with `verify(CONTINUOUS)`

The check for continuity is helpful to make sure you didn't skip an integer value by mistake and this works even if the first value isn't `1`.
The class `Nums` below passes the verification because it starts at `3` and ends at `6` and all integers in between (`4` and `5`) are associated with members:

```py
from enum import Enum, verify, CONTINUOUS

@verify(CONTINUOUS)
class Nums(Enum):
    THREE = 3
    FOUR = 4
    FIVE = 5
    SIX = 6
```

However, the class `NumsHole` below doesn't because the value `5` is not assigned to any member:

```py
from enum import Enum, verify, CONTINUOUS

@verify(CONTINUOUS)
class NumsHole(Enum):
    THREE = 3
    FOUR = 4
    SIX = 6

"""Output:
ValueError: invalid enum 'NumsHole': missing values 5
"""
```

For flag enumerations, the verification `CONTINUOUS` will only check for powers of two.
As such, the flag enumeration `MyFlag` shown below passes the verification, even though no members are associated with the values `5`, `6`, or `7`:

```py
from enum import Flag, verify, CONTINUOUS

@verify(CONTINUOUS)
class MyFlag(Flag):
    C = 4
    D = 8
```

However, the flag enumeration `MyFlagHole` from the next snippet doesn't pass verification because the value `8` ($2^3$) is missing between `4` ($2^2$) and `16` ($2^4$).

```py
from enum import Flag, verify, CONTINUOUS

@verify(CONTINUOUS)
class MyFlag(Flag):
    C = 4
    E = 16
```
```pycon
ValueError: invalid flag 'MyFlag': missing values 8
```


#### Examples with `verify(NAMED_FLAGS)`

To better understand the role of `NAMED_FLAGS`, first you need to understand that flag enumerations allow you to define aliases directly in the enumeration.
Read [the previous section on flags with aliases](#flags-with-aliases) if you need a refresher.

When using `NAMED_FLAGS` you are only allowed to create aliases that are composed of members.

Going back to [the flag `Colour` defined before](#colour-enum), imagine a scenario where you forget to define the member `Colour.GREEN`:

```py
from enum import Flag

class Colour(Flag):
    BLACK = 0
    RED = 1   # <- GREEN should go
    BLUE = 4  # <- between these.
    WHITE = 7
```

The alias `WHITE` still works, and it's composed of the flags `Colour.RED`, `Colour.BLUE`, and `Colour(2)`, since there is no concrete member with the value `2`:

```py
print(purple in Colour.WHITE)  # True
print(Colour.WHITE ^ purple)  # Colour(2)
```

If you use the constraint `NAMED_FLAGS`, creation of the flag `Colour` would fail because the alias `WHITE` uses `Colour(2)`, which is _not_ a named flag:

```py
from enum import Flag, verify, NAMED_FLAGS

@verify(NAMED_FLAGS)
class Colour(Flag):
    BLACK = 0
    RED = 1   # <- GREEN should go
    BLUE = 4  # <- between these.
    WHITE = 7
```
```pycon
ValueError: invalid Flag 'Colour': alias WHITE is missing value 0x2 [use enum.show_flag_values(value) for details]
```


### Global enumeration members

Another useful decorator that the module `enum` provides is `global_enum`, which uses dark magic[^2] to export the enumeration members to the global scope.
This works with any enumeration, and the snippet below shows it used in a flag enumeration:

[^2]: It's not really dark magic. It's just code.

```py
from enum import Flag, auto, global_enum

@global_enum
class Colour(Flag):
    RED = 1
    GREEN = 2
    BLUE = 4

print(BLUE == Colour.BLUE)  # True
```

This decorator was added in Python 3.11, so make sure you're running that version of Python or try implementing it yourself if you're not!
That would be quite an exercise!

! If you're using this in a package, you might want to add these global names to the list `__all__`.

## What was left out

This article gave you an overview of _some_ of the tools that are available in the module `enum` and that should cover the most common use cases.
In a way, I tried to show you the “20% of the tools that will take care of 80% of the use cases”.
But I left out a lot of interesting things:

 - the decorators `member` and `nonmember` that can be used to force something to be (or not to be) a member of the enumeration;
 - the enumeration `FlagBoundary` that lets you customise the behaviour of flag enumerations when dealing with values that are out of the range of the flag;
 - the metaclass `EnumMeta` which is used by all enumeration classes and that allow you to do things like `Enumeration[MEMBER_NAME]`;
 - the helper class `EnumDict`, which is a subclass of `dict` used by the internals of enumerations;
 - the class `ReprEnum`, used by `StrEnum` and `IntEnum`, that makes it more convenient to mix in other types with enumerations;
 - the decorator `enum.property`, which is very similar to the built-in `property`, that allows you to create member properties that have the same names as members themselves (it's what powers `.value` and `.name`);
 - other uncommon operations that enumerations also support;
 - and more.

<!-- (TODO) Add free e-mail course. -->


## Conclusion

Use enumerations when you need to group various constants together or when you need to define sets of options for other functions, classes, and more.
The classes `Enum` and `StrEnum` (for Python 3.11+) will be what you'll be using most of the time, and if you use `auto` to assign values automatically, it will be very difficult to mess it up.

<!-- (TODO) Add free e-mail course. -->
