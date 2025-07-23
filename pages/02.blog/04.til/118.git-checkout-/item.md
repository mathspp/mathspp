Today I learned how to quickly switch back and forth between two different git branches.

===


## git checkout -

Suppose you're in a git branch called `fix-1523-very-important-high-priority` to fix issue 1523 that is very important and high priority.
Then, you checkout the main branch to pull the most recent changes, or something like that, with `git checkout main`.
You do what you have to do on `main` and then you have to checkout the other branch again...

One of two things will happen:

 1. you don't want to type the whole branch name again; or
 2. you don't even remember the exact branch name in the first place.

You can solve both issues with `git checkout -`.
This will checkout the most recent branch you had checked out.
Isn't this amazing?

When I shared this online, someone noted this is the same with the command to change directories, `cd`.
When you run `cd -`, you switch back to the previous directory you were in.
