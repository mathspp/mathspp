This article shares two skills you can add to your coding agents so they use uv workflows.

===

I have fully adopted uv into my workflows and most of the time I want my coding agents to use uv workflows as well, like when running any Python code or managing and running scripts that may or may not have dependencies.

To make this more convenient for me, I created two `SKILL.md` files for two of the most common workflows that the coding agents get wrong on the first few tries:

 1. `python-via-uv`: this skill tells the agent that it should use uv whenever it wants to run any piece of Python code, be it one-liners or scripts. This is relevant because I don't even have the command `python`/`python3` in the shell path, so whenever the LLM tries running something with `python ...`, it fails.
 2. `uv-script-workflow`: this skill is specifically for when the agent wants to create and run a script. It instructs the LLM to initalise the script with `uv init --script ...` and then tells it about the relevant commands to manage the script dependencies.

The two skills also add a note about sandboxing, since uv's default cache directory will be outside your sandbox.
When that's the case, the agent is already instructed to use a valid temporary location for the uv cache.

_Installing_ a skill usually just means dropping a Markdown file in the correct folder, but you should check the documentation for the tools you use.

Here are the two skills for you to download:

 1. [Skill for `python-via-uv`](./SKILL-python-via-uv.txt)
 2. [Skill for `uv-script-workflow`](./SKILL-uv-script-workflow.txt)

I also included the skills verbatim here, for your convenience:

<details markdown="1">
<summary>Skill for <code>python-via-uv</code></summary>

````markdown
---
name: python-via-uv
description: Enforce Python execution through `uv` instead of direct interpreter calls. Use when Codex needs to run Python scripts, modules, one-liners, tools, test runners, or package commands in a workspace and should avoid invoking `python` or `python3` directly.
---

# Python Via Uv

Use `uv` for every Python command.

Do not run `python`.
Do not run `python3`.
Do not suggest `python` or `python3` in instructions unless the user explicitly requires them and the constraint must be called out as a conflict.

## Execution Rules

When sandboxed, set `UV_CACHE_DIR` to a temporary directory the agent can write to before running `uv` commands.

Prefer these patterns:

- Run a script: `UV_CACHE_DIR=/tmp/uv-cache uv run path/to/script.py`
- Run a module: `UV_CACHE_DIR=/tmp/uv-cache uv run -m package.module`
- Run a one-liner: `UV_CACHE_DIR=/tmp/uv-cache uv run python -c "print('hello')"`
- Run a tool exposed by dependencies: `UV_CACHE_DIR=/tmp/uv-cache uv run tool-name`
- Add a dependency for an ad hoc command: `UV_CACHE_DIR=/tmp/uv-cache uv run --with <package> python -c "..."`

## Notes

Using `python` inside `uv run ...` is acceptable because `uv` is still the entrypoint controlling interpreter selection and environment setup.

If the workspace already defines a project-specific temporary cache directory, prefer that over `/tmp/uv-cache`.

If a command example or existing documentation uses `python` or `python3` directly, translate it to the closest `uv` form before executing it.

Use `uv help run` to get full help about the command `uv run` when needed.
````

</details>


<details markdown="1">
<summary>Skill for <code>uv-script-workflow</code></summary>

````markdown
---
name: uv-script-workflow
description: Create, run, and maintain standalone Python scripts through `uv` only. Use when Codex needs to make a new single-file Python script, update that script's dependencies, or explain how to manage a PEP 723 script without editing inline metadata manually.
---

# UV Script Workflow

Use `uv` whenever Python script creation or maintenance is involved.

Do not write inline metadata blocks by hand.
Do not modify existing inline metadata directly.
Do not use `python` or `python3` as the main entrypoint when `uv` can perform the task.

## Core Workflow

For a new standalone script:

1. Initialize it with `uv init --script path/to/script.py`.
2. Add dependencies with `uv add --script path/to/script.py <package>`.
3. Remove dependencies with `uv remove --script path/to/script.py <package>`.
4. Run it with `uv run path/to/script.py`.

Use `uv init --script` even when the script already exists conceptually and only needs to be created on disk.

## Operating Rules

- Treat `uv init --script` as the only supported way to create the script header and inline metadata.
- Treat `uv add --script ...` and `uv remove --script ...` as the only supported ways to change dependencies in that metadata.
- Edit the Python code in the script normally, but leave metadata management to `uv`.
- Use `uv help init` when the correct `uv init` flags are unclear.
- Use `uv help run` when the correct `uv run` invocation is unclear.

## Command Patterns

- Create a script: `uv init --script myscript.py`
- Add a dependency: `uv add --script myscript.py requests`
- Remove a dependency: `uv remove --script myscript.py requests`
- Run a script: `uv run myscript.py`

When sandboxed, set `UV_CACHE_DIR` to a writable temporary directory before running `uv` commands.

Example:

```bash
UV_CACHE_DIR=/tmp/uv-cache uv init --script myscript.py
UV_CACHE_DIR=/tmp/uv-cache uv add --script myscript.py httpx
UV_CACHE_DIR=/tmp/uv-cache uv run myscript.py
```

## Editing Guidance

- Initialize the script first, then edit its code.
- If dependencies change, update them with `uv add --script` or `uv remove --script` instead of patching the header.
- If a user asks to hand-edit metadata, call out that this conflicts with the skill and prefer the corresponding `uv` command.

## Scope

Use this skill for standalone scripts managed through PEP 723 metadata.

If the task is about a full Python project with `pyproject.toml`, use normal project-oriented `uv` workflows instead of this skill's script-specific commands.
````

</details>
