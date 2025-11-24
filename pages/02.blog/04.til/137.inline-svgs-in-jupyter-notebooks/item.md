Today I learned how to inline SVGs in Jupyter notebooks in two simple steps.

===


Today I learned how to inline SVGs in Jupyter notebooks in two simple steps:

 1. URL-encode the SVG markup. If you have an SVG file, open it and copy the contents of the file starting from the `<svg>` tag all the way up to the closing `</svg>` and encode it so it's safe to use in a URL. You can use [this URL encoder tool I created](https://tools.mathspp.com/url-encode).
 2. Add an image using Markdown syntax with `![ALT text](data:image/svg+xml,<URL-encoded string here>)`.

For any non-trivial SVG the URL-encoded string will look huge and nasty, as the image below shows:

![Screenshot of a Jupyter notebok with a Markdown cell being edited. The markup in the cell starts with “![](data:image/svg+xml,” and is followed by a very long string of weird-looking characters with lots of percent signs.](_markup.webp "The URL-encoded SVG.")

But when I “execute” the cell to render the Markdown, the SVG displays neatly:

![Screenshot of an SVG showing 8 diagrams with white and black squares arranged in the same shape but with different colouring schemes.](_svg.webp "The rendered SVG.")

This was an interesting endeavour because I thought I could just paste the SVG markup in the notebook cell and it would be rendered; I was under the impression that you could write arbitrary HTML in those cells.
I was either wrong or I did it in the wrong way!
