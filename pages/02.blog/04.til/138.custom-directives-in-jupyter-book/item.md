Today I learned how to create and register a simple Sphinx extension to use as a custom directive in a Jupyter Book project.

===

I wanted to create a custom directive that I could use in a [Jupyter Book](https://jupyterbook.org/v1/start/overview.html) project that would look like this:

    Some prose goes here.

    ```{mypy} snippet.py
    ```

Then, the directive `{mypy}` would run mypy against the file `snippet.py` and include the mypy output in the book.

With the help of ChatGPT I was able to quickly whip up a Sphinx extension that defines this directive, including the ability to infer the location of my Python snippets based on the concrete structure I have for this project I'm working on: a file `snippet.py` mentioned in a chapter called `xx.my-chapter.md` can be found in `snippets/my-chapter/snippet.py`, where `snippets` is at the root of the project.

After a bit of back and forth and some manual tweaks, this is the directive I ended up with:

<details markdown="1">
<summary><code>mypy_directive.py</code></summary>

````py
"""
Creates a Sphinx directive {mypy} that runs mypy on the given file and includes the output.
When used as ```{mypy} script.py in a file called `xx.some-chapter.md`, this directive
will try to find the file in `snippets/some-chapter/script.py`.
If the file argument contains slashes, it is interpreted as an absolute path.
"""

from __future__ import annotations

import subprocess
from pathlib import Path
from typing import Any, List

from docutils import nodes
from sphinx.util.docutils import SphinxDirective


class MypyDirective(SphinxDirective):
    """
    Usage (MyST):
        ```{mypy} path/to/file.py
        :flags: --strict --show-error-codes
        ```
    """

    required_arguments = 1  # the file path
    optional_arguments = 0
    has_content = False

    option_spec = {
        "flags": lambda s: s,  # pass extra mypy CLI flags as a single string
    }

    def run(self) -> List[nodes.Node]:
        script_arg = self.arguments[0]
        env = self.env

        # Get current document filename (e.g. "given-chapter")
        # env.docname is like "chapters/given-chapter" (no extension)
        _, _, chapter_name = Path(env.docname).name.partition(".")

        # If the user passed just "script.py", infer snippets/<chapter>/<script.py>.
        # If they passed a path with a slash, treat it as explicit.
        if "/" not in script_arg and "\\" not in script_arg:
            inferred_rel = str(Path("snippets") / chapter_name / script_arg)
        else:
            inferred_rel = script_arg

        # Sphinx helper: resolve filenames relative to doc, and track dependencies
        _, abs_path_str = env.relfn2path(inferred_rel)
        abs_path = Path(abs_path_str)

        # Ensure rebuilds happen when the file changes
        env.note_dependency(str(abs_path))

        if not abs_path.exists():
            msg = f"[mypy] File not found: {abs_path}"
            return [nodes.literal_block(text=msg)]

        flags = self.options.get("flags", "").strip()
        cmd: list[str] = ["mypy", str(abs_path)]
        if flags:
            cmd.extend(flags.split())

        proc = subprocess.run(
            cmd,
            stdout=subprocess.PIPE,
            stderr=subprocess.STDOUT,
            text=True,
            cwd=abs_path.parent,
        )

        output = proc.stdout.rstrip()
        if not output:
            output = "[mypy] (no output)"

        output = f"$ mypy {abs_path.name}\n" + output

        # Render as a literal block (monospace). “language” here is just for CSS/classes.
        block = nodes.literal_block(output, output)
        block["language"] = "text"
        return [block]


def setup(app: Any) -> dict[str, Any]:
    app.add_directive("mypy", MypyDirective)
    return {"version": "0.1", "parallel_read_safe": True, "parallel_write_safe": True}
````

</details>

To be able to use it, I had to tweak the book configuration to tell it where to find my extension:

```yaml
sphinx:
  ...
  local_extensions:
    mypy_directive: _ext
  extra_extensions:
    - mypy_directive
```

I find LLMs to be great for this sort of stuff.
Without the help of ChatGPT I _could_ still do this but it would take me _so_ much time to research and figure out how to do it that I would either waste hours on this or not do it at all!
