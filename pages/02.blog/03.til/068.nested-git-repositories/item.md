Today I learned how to create nested git repositories through the `submodules` command.

===

# The problem

I'm working on my [Textual tutorial for EuroPython 2023](https://ep2023.europython.eu/session/build-a-terminal-todo-app-with-textual) and I am saving all the tutorial materials (code, slides, instructions, etc) in a GitHub repository under my company's organisation.

The repository lives under my company because I am giving the tutorial in their name.
However, I would still like to show that tutorial on my [talks repository](https://github.com/mathspp/talks).
So, I thought “Wouldn't it be nice if I could nest git repositories and just list the tutorial repository under my talks repository?”.

As it turns out, you _can_ nest git repositories!


# How do you nest two repositories?

To nest a repository inside another one, you use the command `git submodule add`.
When you add the nested repository, that git refers to as a “submodule”, you need to specify the URL of the original repository, so that git can point to it.

Suppose that you have two repositories, `parent` and `child`, and you want to nest `child` inside `parent`.
Here is how you could do this:

```bash
~:$ cd parent  # Open the `parent` folder.
~/parent:$ git clone https://github.com/xxx/child  # Clone the `child` inside the parent
# This will create a folder `child` inside `parent`.
~/parent:$ git submodule add https://github.com/xxx/child child
#             repository URL ^    local path for the repo ^^^^^
~/parent:$ git commit -m "Add child submodule."  # Commit the submodule.
~/parent:$ git push
```

That's all it takes!
Quite neat, hun?
If I learn other useful things about submodules I will let you know.


That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
