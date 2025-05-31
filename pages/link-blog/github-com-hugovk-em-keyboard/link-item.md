---
# author:
date: 31-05-2025 15:42
link: https://github.com/hugovk/em-keyboard
taxonomy:
    category: link
title: "GitHub - hugovk/em-keyboard: The CLI emoji keyboard"
via: https://hugovk.dev/blog/2025/my-most-used-command-line-commands/
---

This CLI written in Python lets you work with emoji from the comfort of your terminal.

You can get emoji by name and it's automatically copied to your clipboard:

```zsh
$ em rocket
Copied! 🚀
```

You can get the emoji but not copy it if you want to use it in scripts:

```zsh
$ em "chocolate bar" --no-copy
🍫
```

You can also search for emoji by colour:

```zsh
$ em -s brown
🤎  brown_heart
🐴  horse_face
🍂  fallen_leaf
👞  man_s_shoe
🟤  brown_circle
🟫  brown_square
🍄‍🟫  brown_mushroom
```

If your search query only returns one result, it's automatically copied to the clipboard as well:

```zsh
$ em -s portugal
Copied! 🇵🇹  flag_portugal
```
