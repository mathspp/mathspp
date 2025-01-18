This article teaches the decorator pattern in Python, why it exists, how to use it, and when to use it to write efficient and idiomatic Python code.

===

![](thumbnail.webp)

# Decorators

The decorator pattern is a functional pattern that Python developers leverage to write more modular and composable functions.
In this Pydon't, you will learn exactly why the decorator pattern matters, how to use it, and when to use it.
You will also learn how to implement your custom decorators and more advanced use cases of decorators.

In this Pydon't, you will learn:

 - bla bla bla

<!--v-->
!!! You can get all the Pydon'ts as a [free ebook with over +400 pages and hundreds of tips](/books/pydonts). [Download the ebook “Pydon'ts – write elegant Python code” here](/books/pydonts).
<!--^-->


## A function that did too much

The decorator pattern is a pattern that lets you complement a function with behaviour that is useful but that is orthogonal to the original objective of the function.
This pattern is relevant because you do not want to [overcrowd your functions](/blog/pydonts/functions-a-complete-reference#what-goes-into-a-function-and-what-doesnt), and at the same time it allows you to define this useful behaviour in a way that is reusable by other functions.

As an example, consider how you might have implemented the mathematical operation factorial before it was introduced in the module `math`:

```py
# In modern Python: from math import factorial
def factorial(n):
    r = 1
    while n > 1:
        r *= n
        n -= 1
    return r
```

If you are calling this function a lot with a few large integers as arguments, you may want to cache the results you compute.
For this effect, you may want to use a dictionary that maps inputs to outputs:

```py
_factorial_cache = {}

def factorial(n):
    if n not in _factorial_cache:
        _n = n
        r = 1
        while _n > 1:
            r *= _n
            _n -= 1
        _factorial_cache[n] = r

    return _factorial_cache[n]
```

This solution is far from ideal, since you introduced a function cache that is only loosely coupled to the function it's relevant for, while also introducing code in the function that is not really relevant for the original objective of the function.

Instead of baking caching into the function, which is a poor one-time solution for something I might want to do with several functions, I can write a higher-order function that adds caching to any function I want.
Let me walk you through this transformation.

## Factoring out the orthogonal behaviour

Instead of slapping you with a decorator right off the bat, let me refactor the previous function.
I will take the original function `factorial`, without the cache, and I will define a second function called `cached_factorial` that adds a cache around the function `factorial`:

```py
def factorial(n):
    r = 1
    while n > 1:
        r *= n
        n -= 1
    return r

_factorial_cache = {}
def cached_factorial(n):
    if n not in _factorial_cache:
        _factorial_cache = factorial(n)
    return _factorial_cache[n]
```

This second solution isn't perfect yet.
But it is clearly better in one sense: the pattern of wrapping the original function in a second function – without modifying the implementation of the original function – is more general.
Here, “without modifying the implementation of the original function” is the key idea.

I said that in modern Python you can just import the function `factorial` from the module `math`.
If you do, and if you still want to add a cache to the function `factorial`, you have to use the approach of the `cached_factorial` function:

```py
from math import factorial

_factorial_cache = {}
def cached_factorial(n):
    if n not in _factorial_cache:
        _factorial_cache[n] = factorial(n)
    return _factorial_cache[n]
```

Using this approach, you can add caches to other functions without modifying their implementation.
Take the function `fibonacci` from below, which you cannot find in the module `math`, and add a cache to it without modifying the code I wrote:

```py
def fibonacci(n):
    if n < 2:
        return n
    return fibonacci(n - 1) + fibonacci(n - 2)
```

If you understood the pattern correctly, you should have written something like the following:

```py
_fibonacci_cache = {}
def cached_fibonacci(n):
    if n not in _fibonacci_cache:
        _fibonacci_cache[n] = fibonacci(n)
    return _fibonacci_cache[n]
```

Now comes the interesting part.
This is almost exactly the same code...
Maybe you can do something to avoid this structural duplication?


## A factory of cached functions

Instead of writing a cached function for each function we want to cache, I will take the code from the functions `cached_factorial` and `cached_fibonacci`, which are eerily similar, and I will use it to write a factory of cached functions.
This factory accepts a function as input (the function we want to add a cache to) and it spits out the new cached function:

```py
def cached_function_factory(f):
    # Build the cache:
    cache = {}
    # Build the cached function:
    def cached_f(n):
        if n not in cache:
            cache[n] = f(n)
        return cache[n]

    # Return the new function from the factory:
    return cached_f
```

With this new cached function factory we can create `cached_factorial` and `cached_fibonacci` more easily:

```py
from math import factorial

def cached_function_factory(f):
    cache = {}
    def cached_f(n):
        if n not in cache:
            cache[n] = f(n)
        return cache[n]

    return cached_f

cached_factorial = cached_function_factory(factorial)

def fibonacci(n):
    if n < 2:
        return n
    return fibonacci(n - 1) + fibonacci(n - 2)

cached_fibonacci = cached_function_factory(fibonacci)
```

Now, you can check that the values are being cached by checking how much faster it is to call the function a second time:

```py
# ...

import time

t0 = time.perf_counter()
cached_factorial(1_000_000)
t1 = time.perf_counter()
cached_factorial(1_000_000)
t2 = time.perf_counter()

print(f"1st run took {t1 - t0:.3f}s and 2nd took {t2 - t1:.3f}s.")
# 1st run took 7.828s and 2nd took 0.000s.
```

This shows the function `cached_factorial` is definitely being cached.
A similar snippet of code shows that `cached_fibonacci` is also caching its values:

```py
t0 = time.perf_counter()
cached_fibonacci(40)
t1 = time.perf_counter()
cached_fibonacci(40)
t2 = time.perf_counter()

print(f"1st run took {t1 - t0:.3f}s and 2nd took {t2 - t1:.3f}s.")
# 1st run took 9.989s and 2nd took 0.000s.
```

Why did you have to time the functions instead of looking at the cache dictionaries?
If the cache dictionaries are being populated, then the results are being cached.
Well, can you even access the cache dictionaries?
No![^1]

[^1]: Actually, you can. If there was _no_ single reference to the cache dictionary, the garbage collector would clean it up. To access the cache dictionary of `cached_fibonacci` you could do `_fib_cache = cached_fibonacci.__closure__[0].cell_contents`.

The caching mechanism works thanks to a language feature called a [closure](/blog/pydonts/functions-a-complete-reference#closures).
Thanks to closures, the function `cached_function` has access to the variables `cache` and `f`, which are defined inside the function `cached_function_factory`.
A closure is like a bubble that forms around `cached_function` that keeps the variables `cache` and `f` alive even after `cached_function` is returned from inside `cached_function_factory`.


## A factory of timed functions

Now that you want to time your functions to check the efficacy of their caches, you might want to create a factory of timed functions, to make these tests easier:

```py
import time

def timed_function_factory(f):
    def timed_function(n):
        # Measure time right before running f.
        start_time = time.perf_counter()
        # Compute the result.
        result = f(n)
        # Measure time right after running f.
        end_time = time.perf_counter()
        # Return the original result and the timing.
        return result, end_time - start_time

    return timed_function

timed_factorial = timed_function_factory(cached_factorial)
timed_fibonacci = timed_function_factory(cached_fibonacci)

_, t1 = timed_factorial(999_999)
_, t2 = timed_factorial(999_999)
print(f"1st took {t1:.3f}s and 2nd took {t2:.3f}s.")
# 1st took 7.826s and 2nd took 0.000s.

_, t1 = timed_fibonacci(41)
_, t2 = timed_fibonacci(41)
print(f"1st took {t1:.3f}s and 2nd took {t2:.3f}s.")
# 1st took 16.133s and 2nd took 0.000s.
```

This pattern of creating a higher-order function that accepts a function and builds a new function based off of it is quite useful.
As it turns out, it's the decorator pattern.


## The decorator pattern

The decorator pattern is the name of this pattern we leveraged twice already: you implement a function factory that accepts a function as input and wraps that function with some extra functionality, like caching or profiling.
Then, the factory returns that wrapper function and that's what you use.

“Decorator” is the name you give to the factory function and Python provides special syntax to apply decorators to functions.
When you are defining your function, you can use the at sign `@` together with the decorator name to apply that decorator:

```py
# Apply the decorator `cached_function_factory` to the function `fibonacci`:
@cached_function_factory
def fibonacci(n):
    if n < 2:
        return n
    return fibonacci(n - 1) + fibonacci(n - 2)
```

When using this syntax, the cached function is assigned to the same name as the original function.
So, the syntax with the at sign `@` does not magically create another function with another name.
Instead, it saves the decorated function to the same name.
Here, `fibonacci` will now be cached.

The at sign `@` can only be used to apply a decorator if you have access to the definition of the function.
If you don't have access to the definition of the function, for example if you are importing it from another module, then you have to pass the function to the decorator by hand:

```py
from math import factorial

factorial = cached_function_factory(factorial)
```

The two ways of applying a decorator are equivalent.

In case it helps you, remember that the word “decorate” means “make (something) look more attractive by adding extra items or images to it”, and that is what your Python decorators do.
They “make (a function) more useful by adding extra behaviour to it”.
And, in the case of the at sign `@` syntax, the decorator really is the cherry on top; the thing that makes your function absolutely incredible.


## Caching even more

The module `itertools` has an iterable called `combinations` that accepts two arguments, an iterable `it` and an integer `r`, and then “return[s] successive r-length combinations of elements in the iterable [`it`]”.
In some mathematical contexts, you need to know the length of this result without caring about the actual elements, so you can write a function that computes this:

```py
from itertools import combinations

@cached_function_factory
def combinations_len(n, r):
    return len(list(combinations(range(n), r)))
```

The implementation I shared above uses `combinations` from `itertools` to produce all results and then count them, but you could also use a mathematical formula to compute the answer (and you probably should!).

However, something in my code is wrong...
The expression in the body of the function is right:

```py
n, r = 10, 4
print(len(list(combinations(range(n), r))))  # 210 – correct
```

But the cached function doesn't work:

```py
print(combinations_len(10, 4))
"""
Traceback (most recent call last):
  File "<python-input-77>", line 1, in <module>
    print(combinations_len(10, 4))
          ~~~~~~~~~~~~~~~~^^^^^^^
TypeError: cached_function_factory.<locals>.cached_f() takes 1 positional argument but 2 were given
"""
```

## Implementing general decorators

The issue lies in our implementation of the decorator `cached_function_factory`:

```py
def cached_function_factory(f):
    cache = {}
    def cached_f(n):
        if n not in cache:
            cache[n] = f(n)
        return cache[n]

    return cached_f
```

Do you see the signature of the cached function `cached_f`?
It expects a single argument `n`.
This worked for our functions `fibonacci` and `factorial`, but it doesn't work for `combinations_len` because the function `combinations_len` is supposed to take two arguments.

The problem is that the decorator `cached_function_factory` is not general enough.
To make it more general, you need to modify the signature of the function `cached_f` to accept _any_ number of arguments and to pass those along to `f`.

I will show you what this means in code, and I'll also take this opportunity to rename the decorator:
(I am tired of typing the 23 characters of “`cached_function_factory`”...)

```py
def cache(f):
    cache = {}
    # `cached_f` accepts an arbitrary number of arguments...
    def cached_f(*args):
        if args not in cache:
            cache[args] = f(*args)  # ... and passes them along to f.
        return cache[args]

    return cached_f
```

This new version will work with `factorial`, `combinations_len`, and plenty of other functions:

```py
@cache
def combinations_len(n, r):
    return len(list(combinations(range(n), r)))

print(combinations_len(10, 4))  # 210
```

This decorator `cache` looks useful, right?
If you are thinking about adding it to your toolbox, don't worry.
It's already there:

```py
from functools import cache
```

The decorator `cache` from the module `functools`, that you reimplemented here in part, adds a cache to a function.
The decorator from the module is more robust than yours, though, so prefer that one whenever possible.

As the cache decorator example showed, the inner function of the decorator will _typically_ use `*args` to accept an arbitrary number of positional arguments.
This makes the decorator more flexible.
The same thing is also true of `**kwargs`.

To make the timing decorator as flexible as possible, and to make sure it works with any function whatsoever, you must use `*args` and `**kwargs` in the inner function:

```py
def timed(f):
    def timed_f(*args, **kwargs):
        start_time = time.perf_counter()
        result = f(*args, **kwargs)
        end_time = time.perf_counter()
        return result, end_time - start_time

    return timed_f
```


## Anatomy of a decorator

At this point you already have a pretty solid understanding of the anatomy of a decorator.
Here is a quick recap:

```py
def decorator(function_argument):  # 1
    # 2                # 3
    def inner_function(*args, **kwargs):
        ...  # 4
        result = function_argument(*args, **kwargs)  # 5
        ...  # 6
        return result  # 7

    return inner_function  # 8
```

 1. A decorator is typically a higher-order function that accepts an arbitrary function as argument.
 2. Inside the body of a decorator we typically define an inner function to wrap the function argument.
 3. The inner function accepts `*args` and `**kwargs` so it's general and works with any type of function argument provided.
 4. The inner function can run arbitrary code before the original function has to run.
 5. The inner function calls the original function, typically with its original arguments.
 6. The inner function can run arbitrary code after the original function runs.
 7. The inner function returns its result, typically the result that the original function produced.
 8. The decorator returns the inner function that it built, so that the inner function can be used in place of the original one.

TODO: DIAGRAM HERE

What is important for you to realise is that this represents a generic recipe.
None of the bullet points above are rules that are set in stone and you can come up with situations and contexts where you'll need decorators that break those bullet points.
And you also don't need all bullet points.
Some decorators will do work before calling the original function but not after, and some decorators do the opposite, for example.

My suggestion is that you go through the decorators `cache` and `timed` that you implemented above and try to fit their code into the bullet points from above.
(Notice how the decorator `timed` breaks the guideline 7., since it doesn't just return the output of the inner function, but also the timing information.)

This picture of the anatomy of a decorator is almost complete.
You are only missing a small detail.

## `functools.wraps` – the decorator for your decorators

Functions are regular Python objects.
You can check a function's name or docstring, for example:

```py
def foo():
    """bar"""

print(foo.__name__)  # foo
print(foo.__doc__)  # bar
```

When you use a decorator around `foo`, you are essentially replacing `foo` with a new function from inside the decorator:

```py
def decorator(f):
    def inner_func():
        pass

    return inner_func

@decorator
def foo():
    """bar"""
```

At this point, what do you get if you print the name of the function `foo`?
Remember that using the at sign `@` is equivalent to doing this:

```py
def foo():
    """bar"""

foo = decorator(foo)
```

You are assigning the result of `decorator(foo)` to the variable `foo`, and the result of `decorator(foo)` is the inner function `inner_func`, so `foo.__name__` is `inner_func` after applying the decorator.
For a similar reason, the docstring of `foo` seems to be gone:

```py
@decorator
def foo():
    """bar"""

print(foo.__name__)  # inner_func
print(foo.__doc__)  # None
```

To preserve useful attributes like `__name__` and `__doc__`, the module `functools` provides the decorator `wraps`.
This decorator can be used _inside your decorators_ to make sure that your inner functions retain the useful information that the original functions had.

Here is `wraps` applied to the do-nothing decorator I defined above:

```py
from functools import wraps

def decorator(f):
    @wraps(f)
    def inner_func():
        pass

    return inner_func

@decorator
def foo():
    """bar"""

print(foo.__name__)  # foo
print(foo.__doc__)  # bar
```

On the one hand, note how `foo` has the name and the docstring from the original function.
On the other hand, what the heck is going on?!
The decorator `wraps` takes arguments?!

Well, yes.
You are applying `wraps` to the inner function `inner_func`, but you need to tell `wraps` what is the original function that contains the information you want to pass on to the inner function, and that is why you pass `f` as an argument to `wraps`.

I will tell you a bit more about decorators that accept arguments in a second.
For now, let me update the general anatomy of a decorator:

```py
from functools import wraps


def decorator(function_argument):
    @wraps(function_argument)  # <--
    def inner_function(*args, **kwargs):
        ...  # Set-up work.
        result = function_argument(*args, **kwargs)
        ...  # Finalise whatever you need.
        return result

    return inner_function
```

When creating a decorator, you will often want to use `wraps` within the inner function.


## Intermission

I want to tell you about decorators with arguments now.
And I want to show you a couple of other neat things.
But if this is the first time you are learning about decorators, I recommend you take a break now.

Get up, go stretch your legs.

When you come back, open an editor.
Can you implement a decorator `cache` by yourself?
Start by making it work with functions that only accept one argument, like `factorial` or `fibonacci`.
Then, make it general.
How do you make it general?
And why do you have to do that?
Reread sections of this Pydon't if you need to.

After you've implemented `cache`, even if you got a little help from the Pydon't, can you implement a decorator `timed` by yourself?
There is no point in going further if you are not comfortable with these ideas around decorators.


## Decorators with arguments

I want to tell you a little bit about decorators that accept arguments, just like `wraps` does.
But `wraps` uses attributes that you and I don't encounter very often, so I want to go back to a simpler example.
Let us talk about caching. Again.

The decorator `cache` that you implemented uses a dictionary to keep track of cached values.
If you call the cached function with many different inputs, the dictionary grows a lot.
And there is no limit to how much it can grow.
Worst case scenario, you run out of memory and your computer hangs!

To prevent this problem, you come up with a brilliant fix: prevent the cache dictionary from having more than 1000 items.
Here is your new implementation:

```py
from functools import wraps  # ;)
def cache(f):
    cache = {}

    @wraps(f)
    def cached(*args):
        if args not in cache:
            if len(cache) >= 1000:
                cache.popitem()  # Pops an item from the dictionary.
            cache[args] = f(*args)

        return cache[args]

    return cached
```

This solves your problem, but you quickly realise that having the cache constrained to 1000 items is a bit arbitrary.
In scenarios where you have little resources, you'd like to have even smaller caches.
When you feel like splurging memory or caching really slow functions that you use a lot, you want to have a larger cache.

That's an easy fix, right?
All you have to do is make the cache size a variable you can control:

```py
from functools import wraps

CACHE_SIZE = 1000

def cache(f):
    cache = {}

    @wraps(f)
    def cached(*args):
        if args not in cache:
            if len(cache) >= CACHE_SIZE:
                cache.popitem()  # Pops an item from the dictionary.
            cache[args] = f(*args)

        return cache[args]

    return cached
```

Well, yes...
But actually, no!
The cache size is now configurable, but the cache size is the same across all caches!
You have slightly more control, but not enough control.

What you really ought to do is to make the cache size a parameter of the function `cache`:

```py
from functools import wraps

def cache(f, cache_size):
    cache = {}

    @wraps(f)
    def cached(*args):
        if args not in cache:
            if len(cache) >= cache_size:
                cache.popitem()  # Pops an item from the dictionary.
            cache[args] = f(*args)

        return cache[args]

    return cached
```

Now, you can use the cache with different cache sizes:

```py
from math import sin, cos, factorial

sin = cache(sin, 1000)
cos = cache(cos, 1000)
factorial = cache(factorial, 100)
```

This works just fine!
Until you decide to use the cool at sign `@` syntax I just taught you.
How do you even do that?
I wrote down a couple of attempts below, and none work:

```py
@cache(fibonacci, 10_000)
def fibonacci(n):
    ...
```

```py
@cache(10_000)
def fibonacci(n):
    ...
```

```py
@cache(f, 10_000)
def fibonacci(n):
    ...
```

The problem is that the thing in front of the at sign `@` will be called with the function as its _only_ argument, and currently the decorator `cache` takes two arguments:

```py
def cache(f, cache_size):
    ...
```

What we need to do is split the two parameters into two levels, so that passing in the cache size digs into the next level, where the decorator expects the function.
“Digging into the next level” means that the first level, that expects the cache size, must return the decorator that expects the function.

```py
from functools import wraps

def cache(cache_size):  # Level 1 expects the cache size.
    def cache_with_fixed_size(f):  # Level 2 expects the function.
        cache = {}

        @wraps(f)
        def cached(*args):
            if args not in cache:
                if len(cache) >= cache_size:
                    cache.popitem()  # Pops an item from the dictionary.
                cache[args] = f(*args)

            return cache[args]

        return cached

    return cache_with_fixe_size  # Level 1 must return level 2.
```

If it helps, you can look at this outer function as a factory of decorators, since the code that is inside looks pretty much like the decorator `cache` from before.

TODO: DIAGRAM HERE

Is this completely bonkers?
It is a bit mind-bending in the beginning, but this is a useful pattern.
Many decorators accept arguments.

As a few worthy examples, `functools.lru_cache` is the cache decorator from the standard library that does what we were trying to do (a cache with a maximum size) in the proper way.
When you work with web frameworks like Flask, Django, or FastAPI, you use decorators to determine what URLs map to what functions.
And more.


## Anything can be a decorator

Things weren't always as crazy as they are now, but you can use the at sign `@` syntax with a lot of different things.
It doesn't have to be a name of a function you defined with `def`.
Remember that the at sign `@` is syntactic sugar:

```py
# This...
@<random code here>
def foo():
    pass

# ... is syntactic sugar for this:
foo = <random code here>(foo)
```

This shows that the only thing that you need from `<random code here>` is that it is callable.
In other words, you can write any Python expression you want, as long as it evaluates to a callable.[^2]
Writing a function name is just a standard way of doing it.

[^2]: This is true for Python 3.9+. From Python 3.9 onward, what goes in front of the at sign `@` can be any expression that evaluates to a callable.

An anonymous function would be another way to do it.
Here is a do-nothing decorator implemented with the keyword `lambda`:

```py
@lambda f: f
def foo():
    return 42

print(foo())  # 42
```

Is it an excellent idea to use an anonymous function as a decorator?
Absolutely not, because it's a pain to define even simple decorators with lambda functions.

Whatever you use in front of the at sign `@` doesn't even have to return a function.
Can you guess what is the output of the snippet of code below?
You can only guess correctly if you really grasped how Python interprets the syntax with `@`.

```py
@strf
def foo():
    return

print(type(foo))
```

As another fun example, let me go back to the sized cache decorator I wrote


DECORATE CLASSES

CLASSES AS DECORATORS



## Conclusion

Here's the main takeaway of this Pydon't, for you, on a silver platter:

 > “*bla bla*”

This Pydon't showed you that:

 - bla bla bla

<!-- v -->
If you liked this Pydon't be sure to leave a reaction below and share this with your friends and fellow Pythonistas.
Also, [don't forget to subscribe to the newsletter][subscribe] so you don't miss a single Pydon't!
<!-- ^ -->
