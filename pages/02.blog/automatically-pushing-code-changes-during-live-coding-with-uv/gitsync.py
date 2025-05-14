#!/usr/bin/env -S uv run

# /// script
# requires-python = ">=3.13"
# dependencies = [
#     "gitpython",
# ]
# ///

import os
from time import sleep

from git import Repo

def main() -> None:
    repo_folder = os.getcwd()
    print(f"gitsync.py starting at {repo_folder}")
    repo = Repo(repo_folder)

    while True:
        repo.index.add("*")
        repo.index.commit("Auto sync commit")
        repo.remote().push()
        sleep(60)


if __name__ == "__main__":
    main()
