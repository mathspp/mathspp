# /// script
# requires-python = ">=3.13"
# dependencies = [
#     "ruamel-yaml",
# ]
# ///
from collections import Counter, defaultdict
import json
from pathlib import Path
import re

from ruamel.yaml import YAML

STATS_CACHE_FILE = Path(__file__).parent / "stats_cache.json"
TAGS_CACHE_FILE = Path(__file__).parent / "tags_cache.json"

YAML_LOADER = YAML()

def load_cache(file_path: Path):
    with open(file_path, "r") as f:
        return json.load(f)


def save_cache(cache, file):
    with open(file, "w") as f:
        json.dump(cache, f, indent=2)


def find_files(folder: Path):
    yield from folder.rglob("item.md")


def get_contents_and_metadata(filepath: Path):
    contents = filepath.read_text()
    frontmatter_file = filepath.parent / "frontmatter.yaml"
    if frontmatter_file.exists():
        metadata = YAML_LOADER.load(frontmatter_file.read_text())
    else:
        metadata = {}
    if contents.startswith("---"):
        contents = contents.removeprefix("---")
        yaml_data_str, _, contents = contents.partition("---")
        metadata.update(YAML_LOADER.load(yaml_data_str))
    return contents, metadata


def split_code_and_text(contents: str) -> tuple[str, str]:
    """Split a file into its pure text and a list of code snippets."""
    code_snippets = []

    def regex_replacer(match):
        code_snippets.append(match.group(0))
        return ""

    contents = re.sub("```.*?```", regex_replacer, contents, flags=re.DOTALL)
    return contents, code_snippets


def get_tags(metadata) -> list[str]:
    return metadata["taxonomy"]["tag"]


def get_files_that_invalidate_cache(folder: Path):
    yield from folder.glob("*.md")
    yield from folder.glob("*.yml")
    yield from folder.glob("*.yaml")


def get_file_stats_and_tags(cache: dict, filepath: Path) -> dict:
    # Can I use the cache or do I need to recalculate?
    folder = filepath.parent
    cached_values = cache.get(str(folder), {})
    cached_timestamp = cached_values.get("timestamp", 0)
    last_timestamp = max(
        file_path.stat().st_mtime for file_path in get_files_that_invalidate_cache(folder)
    )
    if last_timestamp > cached_timestamp:
        contents, metadata = get_contents_and_metadata(filepath)
        if not metadata.get("published", True):
            return {}, []
        stats = get_stats(contents)
        tags = get_tags(metadata)
        cached_values = {
            "timestamp": last_timestamp,
            "tags": tags,
            "stats": stats,
        }
        cache[str(folder)] = cached_values

    return cached_values["stats"], cached_values["tags"]


def get_stats(contents: str):
    text, code = split_code_and_text(contents)
    return {
        "articles": 1,
        "code_snippets": len(code),
        "loc": sum(len(snippet.splitlines()) for snippet in code) - 2 * len(code),
        "words": len(text.split()),
    }


def main():
    aggregated_stats = defaultdict(Counter)
    cache = load_cache(STATS_CACHE_FILE)
    for filepath in find_files(
        (Path(__file__).parent.parent / "pages/02.blog").resolve()
    ):
        stats, tags = get_file_stats_and_tags(cache, filepath)
        aggregated_stats["__all"].update(stats)
        for tag in tags:
            aggregated_stats[tag].update(stats)

    save_cache(cache, STATS_CACHE_FILE)
    save_cache(aggregated_stats, TAGS_CACHE_FILE)

if __name__ == "__main__":
    main()
