Today I learned you can embed images in your module docstrings and they'll be rendered in the tooltips inside your IDE.

===


# Images in docstrings

[As it turns out](https://bsky.app/profile/physicssux.bsky.social/post/3lt4rudnbyc2v), VS Code (and likely other editors, too) render docstrings as Markdown.
That means you can add images to your docstrings using Markdown syntax and they will be rendered.

I used `uv init --package myproj` to initialise a project and this is what I put at the top of the file `src/myproj/__init__.py` as the module docstring:

```py
"""
Hey there!

![](https://mathspp.com/user/themes/myquark/images/rodrigo_256.png)
"""
```

Then, I created another file and typed `import myproj` at the top of the file.
When I hovered over the import line, the tooltip showed my face:

![Screenshot of VS Code showing a tooltip with the documentation for the module myproj that shows a static picture of my face.](_face.webp "A VS Code tooltip showing an image.")

This also works with GIFs, instead of plain images, as long as you're pointing to a URL on the Internet.
(It looks like it doesn't work with local files).
It also works on other objects that can be documented with docstrings...
For example, it also works with functions.

Here's a simple function I defined and documented inside `src/myproj/__init__.py`:

```py
def two():
    """![](https://mathspp.com/blog/til/images-in-docstrings/_two.gif)"""
    return 2
```

When I used it in my other file and hovered over its name, I saw the GIF animation inside VS Code:

![Screenshot of VS Code showing a tooltip with the documentation for the function myproj.two that includes a GIF of two celebreties, with the one on the right saying the word “two” while lifting their pointer and middle fingers in the typical gesture to represent the number two.](_two.webp "A VS Code tooltip showing a GIF.")

This is pretty hilarious but a reasonable use case might be to include diagrams for complex functions...

[Hats off to “MACE!!!” who indirectly showed me this on BlueSky](https://bsky.app/profile/physicssux.bsky.social/post/3lt4rudnbyc2v).
