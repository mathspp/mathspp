# /// script
# requires-python = ">=3.11"
# dependencies = [
#     "ruamel-yaml>=0.19.1",
# ]
# ///

"""Validate and complete SEO frontmatter for staged Grav blog articles."""

from __future__ import annotations

import re
import sys
from io import StringIO
from pathlib import Path

from ruamel.yaml import YAML
from ruamel.yaml.comments import CommentedMap


BLOG_ROOT = Path("pages/02.blog")
AUTHOR = "Rodrigo Girão Serrão"
SITE_URL = "https://mathspp.com"
ITEM_PATTERN = "item*.md"

yaml = YAML()
yaml.preserve_quotes = True
yaml.indent(mapping=4, sequence=4, offset=2)
yaml.width = 1_000


def unordered_name(path: Path) -> str:
    """Strip Grav's numeric ordering prefix from a directory name."""
    return re.sub(r"^\d+\.", "", path.name)


def article_directories(filenames: list[str]) -> list[Path]:
    """Return unique article directories represented by pre-commit filenames."""
    directories: set[Path] = set()

    for filename in filenames:
        path = Path(filename)
        if not path.is_file() or BLOG_ROOT not in path.parents:
            continue
        if path.match(ITEM_PATTERN):
            directories.add(path.parent)
        elif path.name == "frontmatter.yaml" and any(path.parent.glob(ITEM_PATTERN)):
            directories.add(path.parent)

    return sorted(directories)


def load_frontmatter(article_dir: Path) -> tuple[Path, CommentedMap, str | None]:
    """Load separate or embedded frontmatter.

    The third return value contains the Markdown body for embedded frontmatter.
    """
    separate = article_dir / "frontmatter.yaml"
    if separate.is_file():
        data = yaml.load(separate.read_text(encoding="utf-8")) or CommentedMap()
        return separate, data, None

    item_files = sorted(article_dir.glob(ITEM_PATTERN))
    if not item_files:
        raise ValueError("article has no item Markdown file")

    item = item_files[0]
    text = item.read_text(encoding="utf-8")
    if not text.startswith("---\n"):
        raise ValueError("article has neither frontmatter.yaml nor embedded YAML frontmatter")

    try:
        raw_yaml, body = text[4:].split("\n---", 1)
    except ValueError as error:
        raise ValueError("embedded YAML frontmatter is missing its closing ---") from error

    data = yaml.load(raw_yaml) or CommentedMap()
    return item, data, body


def dump_frontmatter(path: Path, data: CommentedMap, body: str | None) -> None:
    stream = StringIO()
    yaml.dump(data, stream)
    rendered = stream.getvalue()
    if body is not None:
        rendered = f"---\n{rendered}---{body}"
    path.write_text(rendered, encoding="utf-8")


def page_url(article_dir: Path, slug: str) -> str:
    relative_parts = article_dir.relative_to(BLOG_ROOT).parts
    parent_parts = [unordered_name(Path(part)) for part in relative_parts[:-1]]
    route = "/".join(["blog", *parent_parts, slug])
    return f"{SITE_URL}/{route}/thumbnail.webp"


def local_thumbnail(article_dir: Path) -> Path | None:
    for filename in ("thumbnail.webp", "thumbnail.png", "thumbnail.jpg", "thumbnail.jpeg"):
        candidate = article_dir / filename
        if candidate.is_file():
            return candidate
    return None


def ensure_map(parent: CommentedMap, key: str) -> CommentedMap:
    value = parent.get(key)
    if value is None:
        value = CommentedMap()
        parent[key] = value
    if not isinstance(value, dict):
        raise ValueError(f"`{key}` must be a YAML mapping")
    return value


def validate_article(article_dir: Path) -> tuple[list[str], list[str]]:
    """Inject deterministic metadata and return (injected fields, errors)."""
    path, data, body = load_frontmatter(article_dir)
    injected: list[str] = []
    errors: list[str] = []

    slug = data.get("slug")
    if not slug:
        slug = unordered_name(article_dir)
        insertion_point = list(data).index("taxonomy") if "taxonomy" in data else len(data)
        data.insert(insertion_point, "slug", slug)
        injected.append("slug")

    metadata = ensure_map(data, "metadata")
    if not metadata.get("author"):
        metadata.insert(0, "author", AUTHOR)
        injected.append("metadata.author")

    thumbnail = local_thumbnail(article_dir)
    og_image = metadata.get("og:image")
    twitter_image = metadata.get("twitter:image")
    fallback_image = og_image or twitter_image
    if not fallback_image and thumbnail:
        fallback_image = page_url(article_dir, str(slug)).replace(
            "thumbnail.webp", thumbnail.name
        )
    if not og_image and fallback_image:
        insertion_point = (
            list(metadata).index("description") + 1
            if "description" in metadata
            else len(metadata)
        )
        metadata.insert(insertion_point, "og:image", fallback_image)
        injected.append("metadata.og:image")
    if not twitter_image and fallback_image:
        insertion_point = (
            list(metadata).index("og:image") + 1
            if "og:image" in metadata
            else len(metadata)
        )
        metadata.insert(insertion_point, "twitter:image", fallback_image)
        injected.append("metadata.twitter:image")

    taxonomy = data.get("taxonomy")
    if not isinstance(taxonomy, dict):
        errors.append("taxonomy must be a mapping with category and tag values")
    else:
        category = taxonomy.get("category")
        categories = category if isinstance(category, list) else [category]
        if "blogpost" not in categories:
            errors.append("taxonomy.category must include `blogpost`")
        tags = taxonomy.get("tag")
        if not isinstance(tags, list) or not any(str(tag).strip() for tag in tags):
            errors.append("taxonomy.tag must contain at least one tag")

    required_editorial = {
        "date": data.get("date"),
        "title": data.get("title"),
        "metadata.description": metadata.get("description"),
    }
    for field, value in required_editorial.items():
        if not value or not str(value).strip():
            errors.append(f"{field} is required")

    description = metadata.get("description")
    if description and len(str(description)) > 160:
        errors.append(
            f"metadata.description is {len(str(description))} characters; maximum is 160"
        )

    if not metadata.get("og:image") or not metadata.get("twitter:image"):
        errors.append(
            "social images are required; add a thumbnail or set both "
            "metadata.og:image and metadata.twitter:image"
        )

    if injected:
        dump_frontmatter(path, data, body)

    return injected, errors


def main() -> int:
    directories = article_directories(sys.argv[1:])
    if not directories:
        return 0

    failed = False
    for article_dir in directories:
        try:
            injected, errors = validate_article(article_dir)
        except Exception as error:
            print(f"SEO metadata error in {article_dir}: {error}", file=sys.stderr)
            failed = True
            continue

        if injected:
            print(
                f"SEO metadata updated in {article_dir}: {', '.join(injected)}.",
                file=sys.stderr,
            )
            print("Stage the updated frontmatter and commit again.", file=sys.stderr)
            failed = True

        if errors:
            print(f"SEO metadata missing or invalid in {article_dir}:", file=sys.stderr)
            for error in errors:
                print(f"  - {error}", file=sys.stderr)
            failed = True

    return 1 if failed else 0


if __name__ == "__main__":
    raise SystemExit(main())
