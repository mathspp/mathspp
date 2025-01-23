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
from PIL import Image, ImageDraw, ImageFont

THUMBNAILS_CACHE_FILE = Path(__file__).parent / "thumbnails_cache.json"
TAGS_CACHE_FILE = Path(__file__).parent / "tags_cache.json"

TAG_DESCRIPTIONS_FILE = (Path(__file__).parent.parent / "languages/en.yaml").resolve()
MAIN_BLOG_TEMPLATE_FILE = "main-blog.md.template"
BLOG_TEMPLATE_FILE = "blog.md.template"
JINJA_TEMPLATE_DIR = (Path(__file__).parent / "templates").resolve()
JINJA_ENV = Environment(loader=FileSystemLoader(JINJA_TEMPLATE_DIR))
BLOG_TEMPLATE = JINJA_ENV.get_template(BLOG_TEMPLATE_FILE)

BLOG_FOLDER = (Path(__file__).parent.parent / "pages/02.blog").resolve()
TAGS_FOLDER = (BLOG_FOLDER / "00.tags").resolve()

THUMBNAIL_TEMPLATE_PATH = (Path(__file__).parent / "thumbnail.template.png").resolve()

YAML_LOADER = YAML()

# Load fonts to draw the blog thumbnails.
TABLE_FONT_PATH = "Atkinson-Hyperlegible-Regular-102.otf"
TABLE_FONT = ImageFont.truetype(TABLE_FONT_PATH, 50)
TABLE_HEADER_FONT_PATH = "Atkinson-Hyperlegible-Bold-102.otf"
TABLE_HEADER_FONT = ImageFont.truetype(TABLE_HEADER_FONT_PATH, 50)
# Load the RobotoSerif font for the title
TITLE_FONT_PATH = (
    "RobotoSerif-VariableFont_GRAD,opsz,wdth,wght.ttf"  # Replace with the correct path
)
TITLE_FONT = ImageFont.truetype(TITLE_FONT_PATH, 100)  # Adjust font size as needed

CAPITALISATION_OVERRIDES = {
    "Apl": "APL",
    "Uv": "uv",
    "Mathspp blog": "mathspp blog",
}


def load_cache(file_path: Path):
    with open(file_path, "r") as f:
        return json.load(f)


def save_cache(cache, file):
    with open(file, "w") as f:
        json.dump(cache, f, indent=2)


def build_main_blog(context):
    template = JINJA_ENV.get_template(MAIN_BLOG_TEMPLATE_FILE)
    text = template.render(context)
    (BLOG_FOLDER / "blog.md").write_text(text)


def build_blog_for_tag(folder, template, tag, context, description):
    tag = tag.capitalize()
    tag = CAPITALISATION_OVERRIDES.get(tag, tag)
    title_text = f"{tag} blog articles"
    text = template.render(context, tag=tag, description=description, title=title_text)
    (folder / "blog.md").write_text(text)


def build_thumbnail_for_tag(folder, tag, stats):
    COL_DELTA = 40  # Spacing between columns.
    ROW_DELTA = 10  # Spacing between rows.
    image = Image.open(str(THUMBNAIL_TEMPLATE_PATH))
    draw = ImageDraw.Draw(image)

    articles = stats["articles"]
    words = stats["words"]
    loc = stats["loc"]
    table_text = [
        [f"{articles:,}", f"{words:,}"],
        ["articles" if articles != 1 else "article", "words"],
    ]
    if loc:
        table_text[0].append(f"{loc:,}")
        table_text[1].append("lines of code" if loc != 1 else "line of code")

    # Define the title text
    tag = tag.capitalize()
    tag = CAPITALISATION_OVERRIDES.get(tag, tag)
    title_text = f"{tag} articles"

    # Calculate the size of the title
    title_width, title_height = draw.textbbox((0, 0), title_text, font=TITLE_FONT)[2:]

    # Calculate the position to center the title above the image
    image_width, image_height = image.size
    title_x = (image_width - title_width) // 2
    title_y = (image_height - title_height) // 3  # Position above the center

    # Draw the title on the image
    draw.text((title_x, title_y), title_text, font=TITLE_FONT, fill=(255, 255, 255))

    # Calculate the size of the entire table
    cell_widths = [
        max(draw.textbbox((0, 0), cell, font=TABLE_FONT)[2] for cell in col)
        for col in zip(*table_text)
    ]
    cell_heights = [
        max(draw.textbbox((0, 0), cell, font=TABLE_FONT)[3] for cell in row)
        for row in table_text
    ]

    # Total width and height of the table
    table_width = (
        sum(cell_widths) + (len(cell_widths) - 1) * COL_DELTA
    )  # 10 pixels gap between columns
    table_height = (
        sum(cell_heights) + (len(cell_heights) - 1) * ROW_DELTA
    )  # 10 pixels gap between rows

    # Calculate the starting position for centering the table at the bottom of the image
    start_x = (image_width - table_width) // 2
    start_y = 7 * (image_height - table_height) // 10  # Below the centre.

    # Draw the text on the image
    for row_index, row in enumerate(table_text):
        y_position = start_y
        row_font = TABLE_FONT if row_index > 0 else TABLE_HEADER_FONT
        start_x = (image_width - table_width) // 2
        for col_index, cell in enumerate(row):
            x_position = (
                start_x
                + (
                    cell_widths[col_index]
                    - draw.textbbox((0, 0), cell, font=row_font)[2]
                )
                // 2
            )
            draw.text(
                (x_position, y_position), cell, font=row_font, fill=(255, 255, 255)
            )
            start_x += (
                cell_widths[col_index] + COL_DELTA
            )  # The x offset increases by one column.
        start_y += (
            cell_heights[row_index] + ROW_DELTA
        )  # The y offset increases by one row.

    # Save the edited image
    image.save(str(folder / "thumbnail.webp"))


def main():
    tags_cache = load_cache(TAGS_CACHE_FILE)
    thumbnails_cache = load_cache(THUMBNAILS_CACHE_FILE)

    tag_descriptions = YAML_LOADER.load(TAG_DESCRIPTIONS_FILE.read_text())[
        "TAG_DESCRIPTIONS"
    ]

    for tag, stats in tags_cache.items():
        if tag == "__all":
            continue
        cached_stats = thumbnails_cache.get(tag, {})
        if cached_stats != stats:
            print(f"Redoing thumbnail for {tag}")
            folder = TAGS_FOLDER / tag.replace(" ", "-")
            folder.mkdir(exist_ok=True)
            build_blog_for_tag(folder, BLOG_TEMPLATE, tag, stats, tag_descriptions.get(tag))
            build_thumbnail_for_tag(folder, tag, stats)
            thumbnails_cache[tag] = stats

    # Build main thumbnail separately.
    stats = tags_cache.get("__all", {})
    cached_stats = thumbnails_cache.get("__all", {})
    if cached_stats != stats:
        print("Redoing main thumbnail")
        build_thumbnail_for_tag(BLOG_FOLDER, "mathspp blog", stats)
        thumbnails_cache["__all"] = stats

    save_cache(thumbnails_cache, THUMBNAILS_CACHE_FILE)

    build_main_blog(tags_cache["__all"])


if __name__ == "__main__":
    main()
