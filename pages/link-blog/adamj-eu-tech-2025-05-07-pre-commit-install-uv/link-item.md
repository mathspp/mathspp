---
author: Adam Johnson
date: 07-05-2025 14:25
link: https://adamj.eu/tech/2025/05/07/pre-commit-install-uv/
taxonomy:
    category: link
title: "pre-commit: install with uv"
via: https://www.linkedin.com/posts/adamchainz_pre-commit-install-with-uv-activity-7325861937706164224-2m8J
---

In this post, Adam shares how he uses uv to install pre-commit using uv's `tool` command:

```bash
> uv tool install pre-commit
```

What Adam adds, and that I didn't know about, is the plugin `pre-commit-uv`, which makes pre-commit use uv to manage its Python-related hooks, speeding up the pre-commit checks.
After reading this article, I reinstalled pre-commit:

```bash
> uv tool install pre-commit --with pre-commit-uv
```
