from pathlib import Path
import re


def find_files(folder):
    yield from Path(folder).rglob("item*.md")


def get_file_contents(filepath):
    contents = Path(filepath).read_text()
    if contents.startswith("---"):
        contents = contents.removeprefix("---")
        contents = contents[3 + contents.find("---") :]
    return contents


def split_code_and_text(contents):
    """Split a file into its pure text and a list of code snippets."""
    code_snippets = []

    def regex_replacer(match):
        code_snippets.append(match.group(0))
        return ""

    contents = re.sub("```.*?```", regex_replacer, contents, flags=re.DOTALL)
    return contents, code_snippets


def main():
    articles = code_snippets = loc = words = 0
    for file in find_files((Path(__file__) / "..").resolve()):
        text, code = split_code_and_text(get_file_contents(file))
        articles += 1
        code_snippets += len(code)
        loc += sum(len(snippet.splitlines()) for snippet in code) - 2 * len(code)
        words += len(text.split())

    print(f"{articles = }, {words = }")
    print(f"{code_snippets = }, {loc = }")


if __name__ == "__main__":
    main()
