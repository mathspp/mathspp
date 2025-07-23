Answer this quiz to test your knowledge about the Python language, community, andd the 2025 edition of EuroPython!

===

<script src="/user/themes/myquark/js/quiz.js"></script>
<link rel="stylesheet" href="/user/themes/myquark/css/quiz-custom.css">


These are the questions asked during [the EuroPython 2025 quiz](https://ep2025.europython.eu/session/python-quiz).
They will test your knowledge of the Python language, the community, and of EuroPython 2025.
(Unless explicitly stated, questions refer to CPython 3.13.)

At the conference, participants played to win two awesome prizes:

 1. 3D printer
 2. EuroPython 2026 ticket

A couple of PyCharm vouchers were also raffled among the participating players.


## Questions


<div class="quiz-question" data-correct="d">
  <div class="question-text"><p>Where is EuroPython 2025 being held?</p></div>
  <ul class="choices">
    <li data-option="a">Bikini Bottom</li>
    <li data-option="b">Camelot</li>
    <li data-option="c">Hogwarts</li>
    <li data-option="d">Prague</li>
  </ul>
  <p class="feedback"></p>
</div>


<div class="quiz-question" data-correct="b">
  <div class="question-text"><p>What's the source of inspiration for Python's name?</p></div>
  <ul class="choices">
    <li data-option="a">Ball Python (snake)</li>
    <li data-option="b">Monty Python (comedy troupe)</li>
    <li data-option="c">Python (psychedelic doom metal band)</li>
  </ul>
  <p class="feedback"></p>
</div>


<div class="quiz-question" data-correct="b">
  <div class="question-text"><p>Which of these was not a Summit held at EuroPython 2025?</p></div>
  <ul class="choices">
    <li data-option="a">C API Summit</li>
    <li data-option="b">PyScript Summit</li>
    <li data-option="c">Rust Summit</li>
    <li data-option="c">WebAssembly Summit</li>
  </ul>
  <p class="feedback"></p>
</div>


<div class="quiz-question" data-correct="c">
  <div class="question-text"><p>Which of these is not a module from the standard library?</p></div>
  <ul class="choices">
    <li data-option="a"><code>__future__</code></li>
    <li data-option="b"><code>antigravity</code></li>
    <li data-option="c"><code>dataclass</code></li>
    <li data-option="d"><code>this</code></li>
  </ul>
  <p class="feedback"></p>
</div>


<div class="quiz-question" data-correct="b">
  <div class="question-text"><p>Of these four developers, who's been a Python core developer the longest?</p></div>
  <ul class="choices">
    <li data-option="a">Diego Russo</li>
    <li data-option="b">Hynek Schlawack</li>
    <li data-option="c">Pablo Galindo Salgado</li>
    <li data-option="d">Savannah Bailey</li>
  </ul>
  <p class="feedback"></p>
</div>


<div class="quiz-question" data-correct="c">
  <div class="question-text"><p>Which of the following is not a Python keyword?</p></div>
  <ul class="choices">
    <li data-option="a"><code>_</code></li>
    <li data-option="b"><code>finally</code></li>
    <li data-option="c"><code>throw</code></li>
    <li data-option="d"><code>type</code></li>
  </ul>
  <p class="feedback"></p>
</div>


<div class="quiz-question" data-correct="c">
  <div class="question-text"><p>What's printed if you run this code?</p></div>

  <div markdown="1">
  ![Image containing a snippet of code that contains a function decorated by a lambda function.](_decorators.webp)
  </div>

  <ul class="choices">
    <li data-option="a"><code>...</code></li>
    <li data-option="b">20</li>
    <li data-option="c">Ellipsis</li>
    <li data-option="d">Nothing; you get a <code>SyntaxError</code></li>
  </ul>
  <p class="feedback"></p>
</div>


<div class="quiz-question" data-correct="b">
  <div class="question-text"><p>What's the output you get if you run this code in a fresh REPL session?</p></div>

  <div markdown="1">
  ![Image containing a snippet of code that contains the following code: >>> import builtins; >>> len(dir(builtins)); 159; >>> len(dir(builtins)); ???](_builtins.webp)
  </div>

  <ul class="choices">
    <li data-option="a">159</li>
    <li data-option="b">160</li>
    <li data-option="c"><code>RuntimeError</code></li>
    <li data-option="d"><code>ValueError</code></li>
  </ul>
  <p class="feedback"></p>
</div>


## Explanations

### Question 4 – standard library modules

`__future__` is a module from the standard library that allows you to write “future statements”.
Although it interacts with funky special cases in the compiler, there _is_ a module named `__future__` you can import.

The module `this` will print the Zen of Python and the module `antigravity` is an Easter Egg related to [xkcd comic 353](https://xkcd.com/353/).

You can verify all these by opening a 3.13 REPL and typing `import ???`.
`dataclass`, however, was just a play on the fact that the well-known module from the standard library is called `dataclasses`.

### Question 6 – Python keywords

Python 3.13 has four _soft_ keywords: `case`, `match`, `type`, ... and `_`!
Soft keywords are keywords that are only keywords in specific statements and can be used as regular variables elsewhere, and `_` is a soft keyword in the context of `case _:` as a catch-all pattern while [`type` is a soft keyword used for type aliases](/blog/til/type-statement-and-type-aliases).

`finally` is a keyword used in error handling.

This means `throw` can't be a Python keyword.

In any instance, you can verify this with the module `keyword` from the standard library:

```py
import keyword

assert "_" in keyword.softkwlist
assert "type" in keyword.softkwlist
assert "finally" in keyword.kwlist
assert "throw" not in (keyword.kwlist + keyword.softkwlist)
```


### Question 7 – crazy decorators

The question asks what's the output of this code:

```py
@lambda fn: lambda x: ...
def what(the):
    return 2 * the

print(what(10))
```

The at sign `@` syntax for decorators was extended in Python 3.9 so that any valid Python expression can be used as a decorator.
So, this is valid syntax, even though it broke my syntax highlighter.

`@lambda fn: (...)` is the decorator and the decorator returns `lambda x: ...`, which is the function that replaces the function `what`, which can be safely ignored.
The function returned, `lambda x: ...`, is a function that accepts a single argument and always returns the ellipsis `...`.
When `what(10)` is called, the `10` is passed as an argument to `lambda x: ...` which returns `...`.

The object `...` is printed and its string representation is `Ellipsis`, so that's the output you get.


### Question 8 – REPL session

The question asks what's the output of this code if ran in a fresh REPL session:

```py
>>> import builtins
>>> len(dir(builtins))
159
>>> len(dir(builtins))
???
```

In the REPL, [the underscore `_` allows you to recover the result of the previous expression](/blog/pydonts/usages-of-underscore#recovering-last-result-in-the-session).
So, when you first check for `len(dir(builtins))`, you get the result `159` and that also gets saved in the underscore `_` name...

As it turns out, this underscore `_` name doesn't go in the globals but in the built-ins:

```py
>>> "_" in globals()
False
>>> "_" in dir(builtins)
True
```

But `_` wasn't available in the beginning, so it got added afterwards, which means the length of the number of names available in `builtins` grew by 1.
That's how you go from 159 to 160.
