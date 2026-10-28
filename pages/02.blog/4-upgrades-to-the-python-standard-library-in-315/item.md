Learn about 4 of the most exciting updates made to the Python standard library in 3.15.

===

This article highlights 4 of the most exciting updates made to the Python standard library in 3.15:

 - **new `object_hook` in `json.load`**: together with `frozendict` this unlocks a very powerful use case
 - **new module `math.integer`**: new module that introduces integer arithmetic functions
 - **new `re.prefixmatch` function**: clarifies some confusion that typically arises when using `re.match`
 - **new `asyncio.TaskGroup.cancel` function**: ergonomic way of cancelling all the tasks in a task group

## 15 days of Python 3.15

To [celebrate the release of Python 3.15](/python-315), over the next 15 business days I'll be writing about a new 3.15 feature every day.
Explained clearly and with examples so you don't have to sift through the changelog.

Subscribe to receive this free email series:

{% include "forms/form.html.twig" with {form: forms("enroll")} %}

If you want, you can also [check the schedule](/python-315) of the upcoming emails.

## New `object_hook` in `json.load`

The function `json.load` now has a parameter `object_hook`, which can be set to a callable.
When set, JSON objects that are decoded into dictionaries are passed to `object_hook` and replaced by their result.

For example, suppose you have the following JSON data in a file `data.json`:

```json
{
  "name": {
    "first": "Rodrigo",
    "last": "Girão Serrão"
  },
  "interests": [
    "Python",
    "Rust",
    "AI"
  ]
}
```

Define an object hook and use it when loading the data:

```py
import json

def object_hook(d):
    print(f"Inside object_hook with {d = }.")
    return d

with open("data.json", "r") as f:
    data = json.load(f, object_hook=object_hook)
```

Running this code produces two lines of output:

```text
Inside object_hook with d = {'first': 'Rodrigo', 'last': 'Girão Serrão'}.
Inside object_hook with d = {'name': {'first': 'Rodrigo', 'last': 'Girão Serrão'}, 'interests': ['Python', 'Rust', 'AI']}.
```

Similar to `object_hook`, `json.load` has a parameter `array_hook` that is called when a JSON array is processed into a list.
By using both and [by leveraging the new type `frozendict`](/blog/pydonts/frozendict-builtin), you can load JSON data into an immutable structure:

```py
import json

def load_immutable_json(filepath):
    with open(filepath, "r") as f:
        data = json.load(f, object_hook=frozendict, array_hook=tuple)
    return data

immutable_data = load_immutable_json("data.json")
print(immutable_data)
```
```
frozendict({
    'name': frozendict({
        'first': 'Rodrigo',
        'last': 'Girão Serrão'
    }),
    'interests': ('Python', 'Rust', 'AI')
})
```

## New module `math.integer`

The new module `math.integer` provides mathematical functions that operate on integer arguments and return integer results.
As of 3.15, the module defines six functions:

| Function | Behaviour |
| :- | :- |
| `comb(n, k)` | Determines the number of `k`-item groups of `n` objects |
| `perm(n, k=None)` | Determines the number of `k`-item permutations of `n` objects |
| `gcd(*integers)` | Computes the greatest common divisor of all arguments |
| `lcm(*integers)` | Computes the least common multiple of all arguments |
| `isqrt(n)` | Computes the integer square root of the argument |
| `factorial(n)` | Computes the factorial of the argument |

## New `re.prefixmatch` function

What's the output of the following snippet of code?

```py
import re

integer_pattern = "[0-9]+"

string = "Reports that Phelps had a 12000 calorie/day diet are wrong."
if (num := re.match(integer_pattern, string)):
    print(f"Number {num.group()} found in the string!")
else:
    print("No number found")
```

If you think the result is `"Number 12000 found in the string!"`, then you're not alone!
And you're wrong.

The function `re.match` finds matches _at the beginning of the string_ only, which is not obvious from the name `re.match`.
It also doesn't help that other programming languages use the name `match` to look for matches _anywhere_ on the string, which is done with the function `re.search`.

To help alleviate this confusion, the function `re.prefixmatch` was introduced in 3.15.
It does the exact same thing as `re.match`, but it has a clearer name.
`re.match` has been around for _so_ long that it won't be removed, but it was soft deprecated.
This means that new code should use `re.prefixmatch` but `re.match` will remain for backwards compatibility.

## New `asyncio.TaskGroup.cancel` method

Task groups, introduced in Python 3.11, provide a convenient way of managing multiple asynchronous tasks.
Surprisingly, there wasn't an ergonomic way of cancelling all the tasks of a task group.
The new method `TaskGroup.cancel` fixes this.

Suppose you are managing a lab instrument that needs tasks to read measurements, to monitor the instrument status, and to keep the connection alive.
When you're done with the instrument, you want to cancel all tasks.
Before `TaskGroup.cancel`, you'd do something like:

```py
import asyncio, contextlib

class LabInstrument:
    async def start(self):
        self._tasks = [
            asyncio.create_task(self.read_measurements()),
            asyncio.create_task(self.monitor_status()),
            asyncio.create_task(self.keep_connection_alive()),
        ]

    async def stop(self):
        for task in self._tasks:
            task.cancel()
            with contextlib.suppress(asyncio.CancelledError):
                await task
```

Note how the most convenient solution doesn't even use a task group.
If you were using a task group, your code would become _more_ cumbersome, not less:

```py
import asyncio, contextlib

class LabInstrument:
    async def start(self):
        self._context = contextlib.AsyncExitStack()
        self._task_group = await self._context.enter_async_context(asyncio.TaskGroup())
        self._tasks = [
            self._task_group.create_task(self.read_measurements()),
            self._task_group.create_task(self.monitor_status()),
            self._task_group.create_task(self.keep_connection_alive()),
        ]

    async def stop(self):
        for task in self._tasks:
            task.cancel()
        await self._context.aclose()
```

With the introduction of `TaskGroup.cancel` you no longer need to keep track of individual tasks, simplifying the code:

```py
import asyncio, contextlib

class LabInstrument:
    async def start(self):
        self._context = contextlib.AsyncExitStack()
        self._task_group = await self._context.enter_async_context(asyncio.TaskGroup())
        self._task_group.create_task(self.read_measurements()),
        self._task_group.create_task(self.monitor_status()),
        self._task_group.create_task(self.keep_connection_alive()),

    async def stop(self):
        self._task_group.cancel()
        await self._context.aclose()
```

## Other exciting additions to the standard library

Python 3.15 brings other major additions to the standard library that I covered in their own separate articles:

 - [new built-in `frozendict`](/blog/pydonts/frozendict-builtin)
 - [new built-in `sentinel`](/blog/pydonts/sentinel-builtin)
 - [new module `profiling`](/blog/module-profiling-overview) and the [new profiler Tachyon](/blog/tachyon-sampling-profiling-in-python) (`profiling.sampling`)
 - [improvements to `typing`](/blog/typing-improvements-in-python-315) ([better `TypedDict`](/blog/typeddict-with-extra-items), new `TypeForm`, new `@disjoint_base`, ...)

## Python 3.15 brings much more to the table

From the two new built-ins `frozendict` and `sentinel`, to lazy imports, package startup configuration files, typing improvements, or more colour everywhere, Python 3.15 has a lot more to offer.
If you want to learn more about what's new in Python 3.15, [take a look at the “15 days of Python 3.15” series](/python-315) I'm running.
For 15 days, I'll explain a new Python 3.15 feature every day.

Get the free 3.15 email series:

{% include "forms/form.html.twig" with {form: forms("enroll_bottom")} %}

