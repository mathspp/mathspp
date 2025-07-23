This article explains why a user would need to use enums in their code and shows how to do it with a simple example.

===


## What are enums and why do you need them?

Let me show you with a clear example.
Consider the code below.
The function `greet` will accept the name of a person and then it will greet them:

```py
def greet(user, greeting_type):
    if greeting_type == "plain":
        return f"Hello, {user}."
    elif greeting_type == "warm":
        return f"My dear friend, {user}, how are you doing?"
    elif greeting_type == "casual":
        return f"What's up, {user}?"

print(greet("Rodrigo", "casual"))
# "What's up, Rodrigo?"
```

The name can be any string, but the greeting type is supposed to be one of three pre-defined values:

 1. `plain`
 2. `warm`
 3. `casual`

These three values represent _options_.
The user is supposed to supply one of those, otherwise the function won't know what to do (it should probably raise an error, or something of the sort).

Now, imagine this scenario: I'm using the code and I call the function like this `greet("Rodrigo", "PLAIN")`.
What happens?

What happens is that the code doesn't work! 🤡
I messed up the casing, which should've been lowercase.

Or I could write a typo when writing the option name, like I do so often: `greet("Rodrigo", "WARN")`.
This would also not work.

Or I could just forget which options are available, which also happens frequently to me when working on projects with more than 3 functions!

Another thing that could also happen is me mixing up the names of the options when writing another function that should accept the same values.
For example, I could create a function `say_goodbye` and then, by mistake, expect these three different options:

 1. `plain`
 2. `friendly` (instead of `warm`)
 3. `casual`

If I do this, I end up with two functions that should accept the same greeting types, but don't!

Enums (short for enumerations) are useful to prevent all these mistakes.


## Using an enumeration for options

Here's the same code, but using an `Enum` from the module `enum`, instead of harcoded strings:

```py
from enum import auto, Enum

class GreetingType(Enum):
    PLAIN = auto()
    WARM = auto()
    CASUAL = auto()

def greet(user, greeting_type):
    if greeting_type is GreetingType.PLAIN:
        return f"Hello, {user}."
    elif greeting_type is GreetingType.WARM:
        return f"My dear friend, {user}, how are you doing?"
    elif greeting_type is GreetingType.CASUAL:
        return f"What's up, {user}?"

print(greet("Rodrigo", GreetingType.WARM))
# 'My dear friend, Rodrigo, how are you doing?'
```

The class `Enum` is what you inherit from, when creating an enumeration, and the function `auto` is an auxiliary function that you can use to populate the values automatically.
What you really care about is the names `GreetingType.PLAIN`, `WARM`, and `CASUAL`, not the actual value that's stored inside, so `auto` will make sure that you get a unique value for each name without having to worry about it.

So, why is this alternative better?

All of the previous problems are easily solved!
My IDE will auto-complete the greeting type, so there will be no problems with lowercase vs uppercase or typos.
And because I created an explicit enumeration, it is much easier to reuse these options elsewhere!

Use enums!
Save lives!
