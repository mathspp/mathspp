---
# author:
date: 30-08-2025 16:48
link: https://cyclopts.readthedocs.io/en/latest/
taxonomy:
    category: link
title: "Cyclopts"
# via:
---

Cyclopts is “a modern, easy-to-use command-line interface (CLI) framework that aims to provide an intuitive & efficient developer experience.”
The idea behind Cyclopts is also to “what you thought Typer was”.
Although that sounds like a dig on Typer, I guess you can just interpret it as a statement in favour of Cyclopts's features, like making use of docstrings and supporting more complex types like literals and unions.

Here is an example of a tiny Cyclopts application taken from the docs:

```py
import cyclopts
from typing import Literal

app = cyclopts.App()

@app.command
def deploy(
    env: Literal["dev", "staging", "prod"],
    replicas: int | Literal["default", "performance"] = "default",
):
    """Deploy code to an environment.

    Parameters
    ----------
    env
        Environment to deploy to.
    replicas
        Number of workers to spin up.
    """
    if replicas == "default":
        replicas = 10
    elif replicas == "performance":
        replicas = 20

    print(f"Deploying to {env} with {replicas} replicas.")


if __name__ == "__main__":
    app()
```

Here's what it looks like:

```shell
$ my-script deploy --help
Usage: my-script.py deploy [ARGS] [OPTIONS]

Deploy code to an environment.

╭─ Parameters ────────────────────────────────────────────────────────────────────────────────────╮
│ *  ENV --env            Environment to deploy to. [choices: dev, staging, prod] [required]      │
│    REPLICAS --replicas  Number of workers to spin up. [choices: default, performance] [default: │
│                         default]                                                                │
╰─────────────────────────────────────────────────────────────────────────────────────────────────╯

$ my-script deploy staging
Deploying to staging with 10 replicas.

$ my-script deploy staging 7
Deploying to staging with 7 replicas.

$ my-script deploy staging performance
Deploying to staging with 20 replicas.

$ my-script deploy nonexistent-env
╭─ Error ────────────────────────────────────────────────────────────────────────────────────────────╮
│ Error converting value "nonexistent-env" to typing.Literal['dev', 'staging', 'prod'] for "--env".  │
╰────────────────────────────────────────────────────────────────────────────────────────────────────╯

$ my-script --version
0.0.0
```
