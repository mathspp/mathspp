Today I learned how you can do beautiful console logging by using the module `rich`.

===


# How to do beautiful console logging

The standard way to do logging in Python is by using the module `logging` that comes in the standard library.
To do beautiful console logging with very little effort you just need to use the [`rich`][rich] package.

It can be as simple as copying the “setup” code included in the documentation:

```py
import logging
from rich.logging import RichHandler

FORMAT = "%(message)s"
logging.basicConfig(
    level="NOTSET", format=FORMAT, datefmt="[%X]", handlers=[RichHandler()]
)

log = logging.getLogger("rich")
log.info("Hello, World!")
```


# Example of logging with `rich` and `RichHandler`

Using the setup code above, I wrote a little script:

```py
import logging
from rich.logging import RichHandler

FORMAT = "%(message)s"
logging.basicConfig(
    level="NOTSET", format=FORMAT, datefmt="[%X]", handlers=[RichHandler()]
)

log = logging.getLogger("rich")

log.info("Logging set up.")


def division(a, b):
    log.debug(f"Dividing {a} by {b}.")
    try:
        return a / b
    except ZeroDivisionError:
        log.exception("Oh noes!")


division(3, 2)
division(5, 0)
```

If I run this script, here is the output I get:

![A terminal window with some beautiful coloured text on it. The text is clearly divided in four columns, although there are no explicit separators. On the leftmost column we can see timestamps. The second column contains the level of the logging, like “INFO”, “DEBUG”, or “ERROR”, in different colours. The third column contains the error messages and the fourth column contains filename/line information about where that logging comes from.](_example.png "Screenshot of some `rich` logging.")

Your exact colours might be different, but the essence of your output will be just like mine
(I wrote an article on [how I set up my terminal to use a nice colour scheme][til-starship]):

 - on the left, a the timestamp of the message;
 - colour-code level messages (these come from `logging`, but `rich` colours them);
 - the actual log message; and
 - the line/file where the log message came from.

All of this at the distance of a copy & paste operation!
Isn't this amazing?

This is a very simple example of what you can do with `logging` + `rich`.
You might want to check their documentations (links in the references) to learn more!


[rich]: https://github.com/Textualize/rich
[til-starship]: /blog/til/039

That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
