Today I learned how to run the black Python code formatter as a pre-commit hook on git.

===

![A picture of a hook from a construction crane.](thumbnail.png "Photo by Grace To on Unsplash.")


# Pre-commit hooks

Pre-commit hooks are scripts that git runs _right before_ you commit something.
The idea is that you will install scripts that help verify that,
whatever is about to be committed,
is fine.

Pre-commit hooks are just a subset of all the hooks that git can run for you.
You can (and probably should) check [the documentation][hooks] on this subject,
or you might want to check the `.git/hooks` directory of a git repository you have lying around.
What you will notice is that the `hooks` directory,
that was created automatically by git,
contains a series of files ending with `.sample` that represent samples of the various hooks that you can configure.

The hook scripts, by the way, can be any executable you want.
For example, they can be Python scripts with a shebang,
making them executable as well.


# Formatting Python code with `black`

If you want to make sure your Python project has its code formatted according to `black`'s recommendatitons,
you can define your pre-commit hook to run `black` on your code _before_ the code is committed!
This would ensure that no code is committed that isn't formatted properly.

In order to create this pre-commit hook,
just go to `.git/hooks` and create a file called `pre-commit`,
then put this in:

```sh
#!/bin/sh
exec black . --check
```

That's it, that's more than enough!
Of course, you can get all sorts of fancy and configure `black` in a non-standard way,
run the code-formatting check on a specific directory,
or actually tell `black` to _format_ the code,
instead of _checking_ if the formatting is appropriate.

Quite cool, right?


That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe

[hooks]: https://git-scm.com/book/en/v2/Customizing-Git-Git-Hooks
[black]: https://github.com/psf/black
