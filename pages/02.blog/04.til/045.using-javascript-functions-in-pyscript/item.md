Today I learned how to use JavaScript functions in PyScript.

===

<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
<script defer src="/user/themes/myquark/js/pyscript_alpha.min.js"></script>
<py-script>
import datetime

from js import setInterval
from pyodide import to_js

def update_timestamp(elem):
    elem.write(
        format(datetime.datetime.now(), "%H:%M:%S, %A, %d %B %Y")
    )

elem = Element("timestamp_updater")
_ = setInterval(
    to_js(lambda: update_timestamp(elem)),
    1000  # Update every 1000 ms
)
</py-script>

# What is PyScript?

In case you missed, yesterday I learned about [PyScript][pyscript-til],
a tool that allows you to use Python inside your HTML!
In other words, PyScript enables you to run Python on the client side.


# How to use JavaScript functions in PyScript?

In order to use JavaScript functions in PyScript, you can import them from the available `js` module.
For example, to use `console.log` from within PyScript, you just type `from js import console` in your PyScript tag.
Then, you can call `console.log` regularly:

```html
<py-script>
from js import console

console.log("Hey there, from 'console.log' inside PyScript!")
</py-script>
```

If you include that in one of your pages and then check the console,
you will find the message `Hey there, from 'console.log' inside PyScript!` in there.

Let me show another example that is slightly more involved.


# Running a Python function periodically with `setInterval`

JavaScript has a function `setInterval` that lets you run a function periodically.
If we write `from js import setInterval`, that function becomes available to us.
That is what is being used to update the timestamp in the next paragraph.

!!! It is now <span id="timestamp_updater"></span>.

(If the paragraph above doesn't contain a timestamp that updates every second,
something is broken!
Right now, PyScript is in alpha, so that might be it...
But it may be my fault as well 🙃)

What code did I use to get that to run?
Not much!
In this blog post, I just had to link to the PyScript JavaScript file,
and then I added the following HTML/Python code:

```html
<py-script>
import datetime

from js import setInterval
from pyodide import to_js

def update_timestamp(elem):
    elem.write(
        format(datetime.datetime.now(), "%H:%M:%S, %A, %d %B %Y")
    )

elem = Element("timestamp_updater")
_ = setInterval(
    to_js(lambda: update_timestamp(elem)),
    1000  # Update every 1000 ms
)
</py-script>
```

Pretty cool, right?

I learned this by reading a thread that was published on Twitter:

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">Going to be tweeting the session on <a href="https://twitter.com/pyodide?ref_src=twsrc%5Etfw">@pyodide</a> now. This is the app on top of which PyScript is built. Speakers are Hood Chatham and <a href="https://twitter.com/RomanYurchak?ref_src=twsrc%5Etfw">@RomanYurchak</a> <a href="https://twitter.com/hashtag/PyConUS2022?src=hash&amp;ref_src=twsrc%5Etfw">#PyConUS2022</a></p>&mdash; Playful Python (@playfulpython) <a href="https://twitter.com/playfulpython/status/1520763819152519168?ref_src=twsrc%5Etfw">May 1, 2022</a></blockquote>


[pyscript-til]: /blog/til/pyscript

That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
