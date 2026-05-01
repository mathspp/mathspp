# /// script
# requires-python = ">=3.13"
# dependencies = [
#     "markdown-it-py",
#     "ruamel-yaml",
# ]
# ///
from __future__ import annotations

import argparse
import io
import json
import tempfile
from dataclasses import dataclass
from datetime import datetime, timezone
from math import sqrt
from pathlib import Path
from urllib.parse import unquote, urlparse

from markdown_it import MarkdownIt
from ruamel.yaml import YAML

ROOT = Path(__file__).parent.parent.resolve()
BLOG_FOLDER = ROOT / "pages/02.blog"
DEFAULT_OUTPUT = BLOG_FOLDER / "graph/blog-graph.json"
SITE_ORIGIN = "https://mathspp.com"

YAML_LOADER = YAML()
MARKDOWN = MarkdownIt("commonmark")


@dataclass(frozen=True)
class Article:
    slug: str
    title: str
    url: str
    date: str
    tags: tuple[str, ...]
    url_paths: tuple[str, ...]
    filepath: Path
    contents: str

    @property
    def node_id(self) -> str:
        return f"article:{self.slug}"


def grav_slug(folder_name: str) -> str:
    prefix, dot, rest = folder_name.partition(".")
    if dot and prefix.isdigit() and rest:
        return rest
    return folder_name


def get_contents_and_metadata(filepath: Path) -> tuple[str, dict]:
    contents = filepath.read_text()
    metadata = {}
    frontmatter_file = filepath.parent / "frontmatter.yaml"
    if frontmatter_file.exists():
        metadata = YAML_LOADER.load(frontmatter_file.read_text()) or {}

    if contents.startswith("---"):
        contents = contents.removeprefix("---")
        yaml_data_str, _, contents = contents.partition("---")
        metadata.update(YAML_LOADER.load(yaml_data_str) or {})

    return contents, metadata


def is_hidden_path(filepath: Path, blog_folder: Path) -> bool:
    return any(part.startswith("_") for part in filepath.relative_to(blog_folder).parts)


def is_published(metadata: dict) -> bool:
    return metadata.get("published", True) is not False


def metadata_tags(metadata: dict) -> tuple[str, ...]:
    taxonomy = metadata.get("taxonomy") or {}
    tags = taxonomy.get("tag") or []
    if isinstance(tags, str):
        tags = [tags]
    return tuple(dict.fromkeys(str(tag) for tag in tags))


def article_route(filepath: Path, metadata: dict, blog_folder: Path) -> tuple[str, str]:
    article_dir = filepath.parent
    route_parts = [grav_slug(part) for part in article_dir.relative_to(blog_folder).parts]
    slug = str(metadata.get("slug") or route_parts[-1])
    route_parts[-1] = slug
    return slug, "/blog/" + "/".join(route_parts)


def normalize_route_path(path: str) -> str:
    return path.rstrip("/") or "/"


def article_url_paths(
    filepath: Path, metadata: dict, blog_folder: Path, url: str
) -> tuple[str, ...]:
    article_dir = filepath.parent
    folder_route = "/blog/" + "/".join(
        grav_slug(part) for part in article_dir.relative_to(blog_folder).parts
    )
    routes = metadata.get("routes") or {}
    route_values = [url, folder_route]
    for key in ("default", "canonical"):
        route = routes.get(key)
        if route:
            route_values.append(str(route))
    route_values.extend(str(route) for route in routes.get("aliases") or [])
    return tuple(dict.fromkeys(normalize_route_path(route) for route in route_values))


def find_articles(blog_folder: Path = BLOG_FOLDER) -> list[Article]:
    articles = []
    for filepath in sorted(blog_folder.rglob("item.md")):
        if is_hidden_path(filepath, blog_folder):
            continue
        contents, metadata = get_contents_and_metadata(filepath)
        if not is_published(metadata):
            continue
        slug, url = article_route(filepath, metadata, blog_folder)
        articles.append(
            Article(
                slug=slug,
                title=str(metadata.get("title") or slug.replace("-", " ").title()),
                url=url,
                date=str(metadata.get("date") or ""),
                tags=metadata_tags(metadata),
                url_paths=article_url_paths(filepath, metadata, blog_folder, url),
                filepath=filepath,
                contents=contents,
            )
        )
    return articles


def attrs_to_dict(attrs) -> dict[str, str]:
    if not attrs:
        return {}
    if isinstance(attrs, dict):
        return attrs
    return dict(attrs)


def extract_markdown_links(markdown: str) -> list[str]:
    links = []
    for token in MARKDOWN.parse(markdown):
        for child in token.children or []:
            if child.type == "link_open":
                href = attrs_to_dict(child.attrs).get("href")
                if href:
                    links.append(href)
    return links


def normalized_blog_path(target: str) -> str | None:
    parsed = urlparse(target)
    if parsed.scheme and parsed.scheme not in {"http", "https"}:
        return None
    if parsed.netloc and parsed.netloc.lower() not in {"mathspp.com", "www.mathspp.com"}:
        return None

    path = unquote(parsed.path).rstrip("/")
    if not path.startswith("/blog/"):
        return None
    return path


def edge_id(edge_type: str, source: str, target: str) -> str:
    return f"{edge_type}:{source}->{target}"


def tag_url(tag: str) -> str:
    return "/blog/tags/" + tag.lower().replace(" ", "-")


def node_size_for_degree(degree: int) -> int:
    return min(78, round(18 + sqrt(degree) * 5.5))


def annotate_node_degrees(nodes: list[dict], edges: list[dict]) -> None:
    degrees = {node["data"]["id"]: 0 for node in nodes}
    internal_link_degrees = {node["data"]["id"]: 0 for node in nodes}
    tag_degrees = {node["data"]["id"]: 0 for node in nodes}

    for edge in edges:
        edge_data = edge["data"]
        source = edge_data["source"]
        target = edge_data["target"]
        edge_type = edge_data["type"]
        degrees[source] += 1
        degrees[target] += 1
        if edge_type == "internal-link":
            internal_link_degrees[source] += 1
            internal_link_degrees[target] += 1
        elif edge_type == "tag":
            tag_degrees[source] += 1
            tag_degrees[target] += 1

    for node in nodes:
        node_data = node["data"]
        node_id = node_data["id"]
        degree = degrees[node_id]
        node_size = node_size_for_degree(degree)
        node_data["degree"] = degree
        node_data["internal_link_degree"] = internal_link_degrees[node_id]
        node_data["tag_degree"] = tag_degrees[node_id]
        node_data["size"] = node_size
        node_data["width"] = round(node_size * 1.45) if node_data["type"] == "article" else node_size
        node_data["height"] = node_size


def build_graph(blog_folder: Path = BLOG_FOLDER) -> dict:
    articles = find_articles(blog_folder)
    articles_by_url = {}
    for article in articles:
        for path in article.url_paths:
            previous = articles_by_url.setdefault(path, article)
            if previous != article:
                raise ValueError(
                    f"URL path {path!r} maps to both {previous.slug!r} and {article.slug!r}"
                )
    article_slugs = [article.slug for article in articles]
    if len(article_slugs) != len(set(article_slugs)):
        duplicates = sorted(
            slug for slug in set(article_slugs) if article_slugs.count(slug) > 1
        )
        raise ValueError(f"Duplicate article slugs: {duplicates}")

    nodes = []
    edges_by_id = {}
    tags = sorted({tag for article in articles for tag in article.tags}, key=str.lower)

    for article in articles:
        nodes.append(
            {
                "data": {
                    "id": article.node_id,
                    "type": "article",
                    "slug": article.slug,
                    "label": article.title,
                    "title": article.title,
                    "url": article.url,
                    "date": article.date,
                    "tags": list(article.tags),
                }
            }
        )

    for tag in tags:
        tag_id = f"tag:{tag}"
        nodes.append(
            {
                "data": {
                    "id": tag_id,
                    "type": "tag",
                    "label": tag,
                    "tag": tag,
                    "url": tag_url(tag),
                }
            }
        )

    for article in articles:
        for tag in article.tags:
            target = f"tag:{tag}"
            edge = {
                "data": {
                    "id": edge_id("tag", article.node_id, target),
                    "type": "tag",
                    "source": article.node_id,
                    "target": target,
                }
            }
            edges_by_id[edge["data"]["id"]] = edge

        for href in extract_markdown_links(article.contents):
            target_path = normalized_blog_path(href)
            if not target_path:
                continue
            target_article = articles_by_url.get(target_path)
            if not target_article or target_article == article:
                continue
            edge = {
                "data": {
                    "id": edge_id(
                        "internal-link", article.node_id, target_article.node_id
                    ),
                    "type": "internal-link",
                    "source": article.node_id,
                    "target": target_article.node_id,
                }
            }
            edges_by_id[edge["data"]["id"]] = edge

    edges = sorted(edges_by_id.values(), key=lambda edge: edge["data"]["id"])
    annotate_node_degrees(nodes, edges)

    graph = {
        "generated_at": datetime.now(timezone.utc).isoformat(timespec="seconds"),
        "site": SITE_ORIGIN,
        "nodes": nodes,
        "edges": edges,
        "stats": {
            "articles": len(articles),
            "tags": len(tags),
            "internal_links": sum(
                1 for edge in edges_by_id.values() if edge["data"]["type"] == "internal-link"
            ),
            "tag_edges": sum(
                1 for edge in edges_by_id.values() if edge["data"]["type"] == "tag"
            ),
        },
    }
    validate_graph(graph)
    return graph


def validate_graph(graph: dict) -> None:
    node_ids = [node["data"]["id"] for node in graph["nodes"]]
    edge_ids = [edge["data"]["id"] for edge in graph["edges"]]
    if len(node_ids) != len(set(node_ids)):
        raise ValueError("Graph contains duplicate node IDs.")
    if len(edge_ids) != len(set(edge_ids)):
        raise ValueError("Graph contains duplicate edge IDs.")

    node_id_set = set(node_ids)
    dangling = [
        edge["data"]["id"]
        for edge in graph["edges"]
        if edge["data"]["source"] not in node_id_set
        or edge["data"]["target"] not in node_id_set
    ]
    if dangling:
        raise ValueError(f"Graph contains dangling edges: {dangling[:10]}")


def write_graph(output_path: Path, graph: dict) -> None:
    output_path.parent.mkdir(parents=True, exist_ok=True)
    output_path.write_text(json.dumps(graph, indent=2, sort_keys=True) + "\n")


def write_fixture_article(
    folder: Path,
    slug: str,
    body: str,
    *,
    title: str | None = None,
    tags: list[str] | None = None,
    published: bool = True,
    routes: dict | None = None,
) -> None:
    article_folder = folder / slug
    article_folder.mkdir(parents=True)
    metadata = {
        "date": "01-01-2026",
        "slug": slug,
        "taxonomy": {"category": "blogpost", "tag": tags or ["python"]},
        "title": title or slug,
    }
    if not published:
        metadata["published"] = False
    if routes:
        metadata["routes"] = routes
    yaml_stream = io.StringIO()
    YAML_LOADER.dump(metadata, yaml_stream)
    article_folder.joinpath("frontmatter.yaml").write_text(yaml_stream.getvalue())
    article_folder.joinpath("item.md").write_text(body)


def run_self_tests() -> None:
    assert extract_markdown_links("[target](/blog/target)") == ["/blog/target"]
    assert extract_markdown_links('<a href="/blog/raw-html">raw</a>') == []
    assert normalized_blog_path("https://mathspp.com/blog/target?x=1#anchor") == "/blog/target"
    assert normalized_blog_path("/blog/tags/python") == "/blog/tags/python"
    assert normalized_blog_path("https://example.com/blog/target") is None

    with tempfile.TemporaryDirectory() as tmp:
        blog_folder = Path(tmp)
        write_fixture_article(
            blog_folder,
            "source",
            "\n".join(
                [
                    "[one](/blog/target)",
                    "[two](https://mathspp.com/blog/target?x=1#anchor)",
                    "[tag](/blog/tags/python)",
                    '<a href="/blog/raw-html">raw</a>',
                    "[draft](/blog/draft)",
                    "[alias](/blog/alias-target)",
                ]
            ),
            tags=["python", "graph theory"],
        )
        write_fixture_article(blog_folder, "target", "Target.", tags=["python"])
        write_fixture_article(
            blog_folder,
            "canonical-target",
            "Canonical target.",
            tags=["python"],
            routes={"canonical": "/blog/canonical-target", "aliases": ["/blog/alias-target"]},
        )
        write_fixture_article(blog_folder, "draft", "Draft.", published=False)

        graph = build_graph(blog_folder)
        validate_graph(graph)
        internal_edges = [
            edge for edge in graph["edges"] if edge["data"]["type"] == "internal-link"
        ]
        assert len(internal_edges) == 2
        internal_targets = {edge["data"]["target"] for edge in internal_edges}
        assert internal_targets == {"article:target", "article:canonical-target"}
        assert all(edge["data"]["source"] == "article:source" for edge in internal_edges)
        nodes_by_id = {node["data"]["id"]: node for node in graph["nodes"]}
        assert "article:draft" not in nodes_by_id
        assert nodes_by_id["article:source"]["data"]["degree"] == 4
        assert nodes_by_id["article:source"]["data"]["internal_link_degree"] == 2
        assert nodes_by_id["article:source"]["data"]["tag_degree"] == 2
        assert nodes_by_id["article:source"]["data"]["size"] > nodes_by_id["article:target"]["data"]["size"]


def parse_args() -> argparse.Namespace:
    parser = argparse.ArgumentParser(description="Build the blog graph JSON asset.")
    parser.add_argument("--blog-folder", type=Path, default=BLOG_FOLDER)
    parser.add_argument("--output", type=Path, default=DEFAULT_OUTPUT)
    parser.add_argument("--self-test", action="store_true")
    return parser.parse_args()


def main() -> None:
    args = parse_args()
    if args.self_test:
        run_self_tests()
        print("Self-tests passed.")
        return

    graph = build_graph(args.blog_folder.resolve())
    write_graph(args.output.resolve(), graph)
    print(
        "Wrote "
        f"{args.output} with {graph['stats']['articles']} articles, "
        f"{graph['stats']['tags']} tags, "
        f"{graph['stats']['internal_links']} internal links, and "
        f"{graph['stats']['tag_edges']} tag edges."
    )


if __name__ == "__main__":
    main()
