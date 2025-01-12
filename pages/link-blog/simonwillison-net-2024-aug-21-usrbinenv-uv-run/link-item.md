---
author: Simon Willison
date: 10-01-2025 15:43
link: https://simonwillison.net/2024/Aug/21/usrbinenv-uv-run/
taxonomy:
    category: link
title: "#!/usr/bin/env -S uv run"
via: https://bsky.app/profile/andrew-jones.com/post/3lfcjfwm3os2j
---

I started using uv recently and I keep getting impressed by everything I can do in such a convenient way with uv.
I've been using uv to manage scripts and script dependencies but using the shebang `#!/usr/bin/env -S uv run` in scripts and then making them executable (with `chmod 755`) is on a whole new level.

Essentially, you want your scripts' header to look like this:

```py
#!/usr/bin/env -S uv run
# /// script
# requires-python = ">=3.13"
# dependencies = [
#     ...,
# ]
# ///

# code here...
```

If you think about it, what this means is that you get shell scripts written in Python with self-contained dependencies that uv manages for you...
