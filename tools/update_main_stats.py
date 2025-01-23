# /// script
# requires-python = ">=3.13"
# dependencies = [
#     "jinja2",
#     "pillow",
#     "ruamel-yaml",
# ]
# ///
import json
from pathlib import Path

from ruamel.yaml import YAML
from jinja2 import Environment, FileSystemLoader

TAGS_CACHE_FILE = Path(__file__).parent / "tags_cache.json"

MAIN_BLOG_TEMPLATE_FILE = "main-blog.md.template"
STATS_FEATURES_TEMPLATE_FILE = "stats-features.md.template"
JINJA_TEMPLATE_DIR = (Path(__file__).parent / "templates").resolve()
JINJA_ENV = Environment(loader=FileSystemLoader(JINJA_TEMPLATE_DIR))

BLOG_FOLDER = (Path(__file__).parent.parent / "pages/02.blog").resolve()
TAGS_FOLDER = (BLOG_FOLDER / "00.tags").resolve()

THUMBNAIL_TEMPLATE_PATH = (Path(__file__).parent / "thumbnail.template.png").resolve()

YAML_LOADER = YAML()

def load_cache(file_path: Path):
    with open(file_path, "r") as f:
        return json.load(f)


def main():
    tags_cache = load_cache(TAGS_CACHE_FILE)
    context = tags_cache["__all"]

    # Write the main `blog.md` file.
    template = JINJA_ENV.get_template(MAIN_BLOG_TEMPLATE_FILE)
    text = template.render(context)
    (BLOG_FOLDER / "blog.md").write_text(text)

    # Write the modular `00.tags/_stats/features.md` file.
    template = JINJA_ENV.get_template(STATS_FEATURES_TEMPLATE_FILE)
    text = template.render(context)
    (TAGS_FOLDER / "_stats/features.md").write_text(text)

if __name__ == "__main__":
    main()
