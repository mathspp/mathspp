Today I learned about PyScript, a tool that lets you run Python in your HTML!

===

<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
<script defer src="/user/themes/myquark/js/pyscript_alpha.min.js"></script>

<py-script>
import datetime

output = Element("py-script-target")
now = format(datetime.datetime.now(), "%H:%M, %A, %d %B %Y.")
output.write(now)
</py-script>


# PyScript

## Run Python in Your HTML

Today I learned about [PyScript], a tool that lets you run Python from within your HTML!

I got so excited when I found out about this tool
(which was presented to the world today, if I am not mistaken!)
that I had to give it a try right away!

For example, you opened this page, it loaded, and then it inserted a timestamp here: <span id="py-script-target"></span>

(If the timestamp doesn't show right away, wait a couple of seconds.
If it still doesn't show, take into account that the PyScript version I am using here has only been tested on Chrome.)

This timestamp, which corresponds roughly to when the page finished loading,
was put there by Python.
I just had to write some code in the HTML of this page:

```html
<py-script>
import datetime

output = Element("py-script-target")
now = format(datetime.datetime.now(), "%D %M %Y")
output.write(now)
</py-script>
```

I am very excited to see what people build with this tool!
Just bear in mind that it is still in its early stages of development.

By the way, I found out about PyScript from a tweet:

<blockquote class="twitter-tweet"><p lang="en" dir="ltr"><a href="https://twitter.com/hashtag/PyConUS2022?src=hash&amp;ref_src=twsrc%5Etfw">#PyConUS2022</a> <a href="https://twitter.com/pwang?ref_src=twsrc%5Etfw">@pwang</a> Keynote: Announcing Py-script!!!<br>It&#39;s Python! inside HTML!!! 🤯 <a href="https://t.co/paDsibNQtt">pic.twitter.com/paDsibNQtt</a></p>&mdash; Mariatta 🤦 (@mariatta) <a href="https://twitter.com/mariatta/status/1520432987359399936?ref_src=twsrc%5Etfw">April 30, 2022</a></blockquote>


[PyScript]: https://pyscript.net/

That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
