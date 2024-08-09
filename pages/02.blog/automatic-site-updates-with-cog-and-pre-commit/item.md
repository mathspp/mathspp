This article outlines how I use a pre-commit hook and cog to keep my blog stats updated automatically.

===


# Automatic site updates with cog and pre-commit

Some months ago I introduced some [basic stats in my blog](/blog) that show how many articles I've published, along with how many words and lines of code those articles contain.
I [wrote an article explaining how I wrote a script that gathers these blog stats for me](/blog/adding-stats-to-my-blog) but that's a script I still need to run and then manually update the stats.

I wanted to automate this process but I couldn't bring myself to do it.
And then, I came to the silly realisation that I could use ChatGPT to help me with the set up that I was dreading to do.


## Rewriting the stats with cog

[Cog](https://nedbatchelder.com/code/cog) is “a file generation tool” that lets you use snippets of Python code to fill in parts of other files.
For this particular project, I wanted it to rewrite [the Markdown table that lists the blog stats](https://github.com/mathspp/mathspp/blob/876da9f7b38708f928e0940247cfa1e10ba8c68a/pages/02.blog/blog.md?plain=1#L16-L18), that looks like this:

```markdown
...

| 364 | 401,577 | 33,251 |
| :-: | :-: | :-: |
| articles | words | lines of code |

...
```

I already have a script `stats.py` that computes the stats, so I started by changing it to produce the output in this exact format.
Then, I tweaked the markdown file to include a cog tag that runs the script and inserts its output in the markdown file.
The markdown file now looks like this:

```markdown
...

<!--
[[[cog
import cog, subprocess
result = subprocess.run(["python", "pages/02.blog/stats.py"], text=True, capture_output=True)
cog.outl(result.stdout)
]]]-->
| 364 | 401,577 | 33,251 |
| :-: | :-: | :-: |
| articles | words | lines of code |
<!--[[[end]]]--->

...
```

The `[[[cog` part tells cog where my Python code starts and the `]]]` tells it where it ends.
In my case, I'm just using the module `subprocess` to run the script I already have, I'm capturing its output, and I'm reemitting the output through cog.

The output that I produce with `cog.outl` is then inserted between the `]]]` and the `[[[end]]]`, which is where the table ends up being.
In case you're wondering, for every run cog will start by clearing the previous table and then will write the new one.

Finally, note that the cog tags are surrounded by HTML multi-line comments, so that the cog tags themselves do not show up in the final page.
At least, not visibly.
If you inspect the source code of [my blog page](/blog) you will see the HTML comments with the cog tags there.


## Triggering cog automatically with pre-commit

The thing with cog is that I still have to run `cog -r blog.md` to update the table, so my next step is to automate the step that calls cog.
For that, I asked ChatGPT to help me set up [a pre-commit hook](https://pre-commit.com) that is triggered when my blog pages change.

After a couple of exchanges with ChatGPT, I ended up with [the following `.pre-commit-config.yaml` file](https://github.com/mathspp/mathspp/blob/876da9f7b38708f928e0940247cfa1e10ba8c68a/.pre-commit-config.yaml):

```yaml
repos:
  - repo: local
    hooks:
      - id: update-blog-stats
        name: Update blog stats
        entry: bash -c 'cog -r pages/02.blog/blog.md && git add pages/02.blog/blog.md'
        language: system
        files: '^pages/02\.blog/'
        types: [markdown]
        stages: [pre-commit]
```

This simple hook looks for changes in Markdown files under my blog folder.
If there are any, we run cog on my blog main page and then add that page so that it gets committed with the other changes.
