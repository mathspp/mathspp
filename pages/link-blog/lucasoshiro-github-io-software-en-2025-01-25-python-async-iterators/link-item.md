---
author: Lucas Seiki Oshiro
date: 09-02-2025 15:26
link: https://lucasoshiro.github.io/software-en/2025-01-25-python_async_iterators/
taxonomy:
    category: link
title: "Python: re-creating async in 10 lines using generators"
via: https://pycoders.com/issues/667
---

In this article, Lucas reimplements asynchronous programming in Python using generators as a learning exercise.
Using generators and threads, Lucas mimics the way promises work in JavaScript: when you call a generator function (or when you create a generator expression), you don't get the results right away.
You get an object (the generator) that knows how to compute the result.

This is similar to a promise (in JS and other languages).
You get a promise that you'll get a result back.
With the generator, you also have this “promise” that you'll get the result, you just need to call `next` on the generator.

The article is fairly short and straightforward and I just wanted to propose a different implementation that leans on generator functions instead of short-circuiting and generator expression “hacks”, so I rewore the final code in the article to both test my understanding and check if I could clean those “hacks”.
On a second look, I realised I could [write a decorator](/blog/pydonts/decorators) that turns any “normal” function into an “async” function of this generator-plus-threads flavour:

```py
from threading import Thread

def into_async(callable):
    def async_gen_function(*args, **kwargs):
        return_value = []
        thread = Thread(target=lambda: return_value.append(callable(*args, **kwargs)))
        thread.start()

        def gen():
            thread.join()
            yield return_value[0]

        return gen()

    return async_gen_function
```

<details markdown="1">
<summary>The generator <code>into_async</code> used in the context of the code of the original post.</summary>

```py
from threading import Thread

def into_async(callable):
    def async_gen_function(*args, **kwargs):
        return_value = []
        thread = Thread(target=lambda: return_value.append(callable(*args, **kwargs)))
        thread.start()

        def gen():
            thread.join()
            yield return_value[0]

        return gen()

    return async_gen_function

@into_async
def async_lines(path):
    print("Reading file", path)
    with open(path) as f:
        return_value = list(f)
    print("Finished reading", path)

    return return_value

# NOTE: See the original link for these two files.
# (long1 has ints from range(50_000_000) and long2 has
# ints from range(0, 100_000_000, 2).)
async_lines1 = async_lines('long1.txt')
async_lines2 = async_lines('long2.txt')

def result_gen():
    lines1 = next(async_lines1)
    lines2 = next(async_lines2)
    yield int(lines1[-2]) + int(lines2[-2])

result = result_gen()

print("Result generator created.")
print(next(result))
```

</details>
