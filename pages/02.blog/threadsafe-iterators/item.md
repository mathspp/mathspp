Learn how to write and use thread-safe iterators in Python 3.15.

===

This article shows how to write and use iterators in a thread-safe manner using the new tools from Python 3.15:

 - `threading.serialize_iterator`
 - `synchronized_iterator`

## 15 days of Python 3.15

To [celebrate the release of Python 3.15](/python-315), over the next 15 business days I'll be writing about a new 3.15 feature every day.
Explained clearly and with examples so you don't have to sift through the changelog.

Subscribe to receive this free email series:

{% include "forms/form.html.twig" with {form: forms("enroll")} %}

If you want, you can also [check the schedule](/python-315) of the upcoming emails.

## Iterators are not thread-safe

Most iterators are not thread-safe by default.
For example, generator iterators can raise a `ValueError` if accessed simultaneously from two different threads, as you'll see now.

First, you're going to define a slow generator:

```py
import time

def slow_counter():
    idx = 0
    while True:
        time.sleep(1)
        idx += 1
        yield idx

counter = slow_counter()
```

The generator `counter` takes a second to produce each successive number.
Then, you're going to write a function that is going to try to consume a value out of the counter in a dedicated thread:

```py
def count_consumer(name, counter):
    try:
        idx = next(counter)
    except ValueError as e:
        print(f"ValueError in {name}: {e}")
    else:
        print(f"{name} got {idx = }")
```

The function `count_consumer` will run in two different threads at the same time.
The idea is that the second thread will try to fetch a value out of `counter` while the first thread is still waiting for the first slow result.

By passing `count_consumer` to two different threads, you'll see that the second thread triggers an exception:

```py
t1 = threading.Thread(target=count_consumer, args=("t1", counter))
t2 = threading.Thread(target=count_consumer, args=("t2", counter))

t1.start()
t2.start()

t1.join()
t2.join()
```

The code above produces the following output:

```text
ValueError in t2: generator already executing
t1 got idx = 1
```

Thus, if you want to access a shared iterator across multiple threads, you must ensure that no two threads try to access the same iterator at the same time.

## Serialising an iterator

To make the generator from above thread-safe you can use `threading.serialize_iterator`, introduced in Python 3.15:

```py
counter = threading.serialize_iterator(slow_counter()) # <--

t1 = threading.Thread(target=count_consumer, args=("t1", counter))
t2 = threading.Thread(target=count_consumer, args=("t2", counter))

t1.start()
t2.start()

t1.join()
t2.join()
```

This modified version of the code produces the following output:

```text
t1 got idx = 1
t2 got idx = 2
```

The idea of `serialize_iterator` is surprisingly simple.
Just wrap the non-thread-safe iterator in an iterator that uses a lock around computing successive elements.
Something along the lines of

```py
import threading

class serialize_iterator:
    def __init__(self, iterable):
        self._iterator = iter(iterable)
        self._lock = threading.Lock()

    def __iter__(self):
        return self

    def __next__(self):
        with self._lock:
            return next(self._iterator)
```

In fact, [the real implementation](https://github.com/python/cpython/blob/03875414cb2993b609044004732b802fe73f39dd/Lib/threading.py#L849-L901) of `threading.serialize_iterator` is fairly similar.

## Thread-safe generator functions

The decorator `synchronized_iterator` can be used around functions that produce iterators to serialize them automatically.
This is useful when used in conjunction with `itertools`, for example, or when used around a generator:

```py
import time
import threading

@threading.synchronized_iterator  # <--
def slow_counter():
    idx = 0
    while True:
        time.sleep(1)
        idx += 1
        yield idx

counter = slow_counter()  # <--
```

By using `@synchronized_iterator` around the generator functino instead of using `serialize_iterator` after creating the generator iterator, `counter` is automatically thread-safe:

```py
t1 = threading.Thread(target=count_consumer, args=("t1", counter))
t2 = threading.Thread(target=count_consumer, args=("t2", counter))

t1.start()
t2.start()

t1.join()  # t1 got idx = 1
t2.join()  # t2 got idx = 2
```

As an exercise, try to implement your own version of `synchronized_iterator` (making use of `serialize_iterator`, of course).

## Python 3.15 brings much more to the table

From the two new built-ins `frozendict` and `sentinel`, to lazy imports, package startup configuration files, typing improvements, or more colour everywhere, Python 3.15 has a lot more to offer.
If you want to learn more about what's new in Python 3.15, [take a look at the “15 days of Python 3.15” series](/python-315) I'm running.
For 15 days, I'll explain a new Python 3.15 feature every day.

Get the free 3.15 email series:

{% include "forms/form.html.twig" with {form: forms("enroll_bottom")} %}

