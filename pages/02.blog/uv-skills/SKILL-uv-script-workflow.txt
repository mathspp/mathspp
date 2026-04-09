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
