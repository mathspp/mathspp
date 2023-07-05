Today I learned how to create an alias to activate my Python virtual environments with a single word.

===

# Shell aliases

[All][^1] shells allow you to create aliases, which essentially act as shortcuts for commands you type often.
How you create an alias differs from shell to shell, but it is common to have a configuration file in which you type something like:

```zsh
alias command="long or annoying command you want to create the alias for"
```


# Activate a virtual environment with an alias

I usually create Python virtual environments with the command `python -m venv .venv`, which means I type the command `source .venv/bin/activate` to activate the virtual environment many times.

I knew about aliases for a while, but it _just_ dawned on me that I don't need to type `source .venv/bin/activate`, which is FOUR words, if I can just type `activate` after creating a shell alias!

So, I went to my shell configuration file `~/.zshrc` (I use [Oh My Zsh](https://ohmyz.sh)) and I added the following:

```zsh
alias activate="source .venv/bin/activate"
```

Now, I can activate my virtual environments with the single-word command `activate`!
Neat, right?


[^1]: Maybe, just _maybe_, there is a shell that does not allow you to create aliases...

That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
