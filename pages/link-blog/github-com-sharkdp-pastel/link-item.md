---
# author:
date: 20-03-2025 00:46
link: https://github.com/sharkdp/pastel
taxonomy:
    category: link
title: "GitHub - sharkdp/pastel: A command-line tool to generate, analyze, convert and manipulate colors"
# via:
---

The command line tool `pastel` appears to be a brilliant CLI to work with colours in the command line.
For some reason, I find myself wanting to mix colours together quite frequently, for example, and I can do this with pastel:

```bash
pastel mix f8f8f2 darkblue | pastel format rgb
# rgb(148, 122, 192)
```

The reason I'm piping `pastel mix` into `pastel format` is because the output of `pastel mix` is actually more complete:

![Screenshot of my terminal after running the command `pastel mix f8f8f2 darkblue`, showing a rectangle on the screen with the resulting colour, while also displaying the hex, RGB, and HSL, values of that colour, together with the 3 most similar named colours.](_screenshot.webp "Output of `pastel mix`.")
