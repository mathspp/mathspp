from collections import Counter, defaultdict
from pathlib import Path
import re
import shutil

from ruamel.yaml import YAML
from jinja2 import Environment, FileSystemLoader
from PIL import Image, ImageDraw, ImageFont

BLOG_TEMPLATE_FILE = "blog.md.template"
JINJA_TEMPLATE_DIR = Path(__file__).parent
JINJA_ENV = Environment(loader=FileSystemLoader(JINJA_TEMPLATE_DIR))
BLOG_TEMPLATE = JINJA_ENV.get_template(BLOG_TEMPLATE_FILE)

TAGS_FOLDER = (Path(__file__).parent.parent / "pages/02.blog/00.tags").resolve()

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


def get_stats(contents: str):
    text, code = split_code_and_text(contents)
    return {
        "articles": 1,
        "code_snippets": len(code),
        "loc": sum(len(snippet.splitlines()) for snippet in code) - 2 * len(code),
        "words": len(text.split()),
    }


def build_blog_for_tag(folder, template, tag, context):
    text = template.render(context, tag=tag, capitalized_tag=tag.capitalize())
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
    title_text = f"{tag} articles".capitalize()

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
    aggregated_stats = defaultdict(Counter)
    for filepath in find_files(
        (Path(__file__).parent.parent / "pages/02.blog").resolve()
    ):
        contents, metadata = get_contents_and_metadata(filepath)
        if not metadata.get("published", True):
            continue
        stats = get_stats(contents)
        aggregated_stats["__all"].update(stats)
        for tag in get_tags(metadata):
            aggregated_stats[tag].update(stats)

    articles = aggregated_stats["__all"]["articles"]
    words = aggregated_stats["__all"]["words"]
    loc = aggregated_stats["__all"]["loc"]
    template = f"""\
<table class="stats-table">
    <thead>
        <tr>
            <th style="text-align: center;">{articles:,}</th>
            <th style="text-align: center;">{words:,}</th>
            {f'<th style="text-align: center;">{loc:,}</th>' if loc > 0 else ""}
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="text-align: center;">{"articles" if articles > 1 else "article"}</td>
            <td style="text-align: center;">words</td>
            {'<td style="text-align: center;">lines of code</td>' if loc > 0 else ""}
        </tr>
    </tbody>
</table>"""
    print(template)

    del aggregated_stats["__all"]

    for tag, stats in aggregated_stats.items():
        folder = TAGS_FOLDER / tag.replace(" ", "-")
        folder.mkdir(exist_ok=True)
        build_blog_for_tag(folder, BLOG_TEMPLATE, tag, stats)
        build_thumbnail_for_tag(folder, tag, stats)


if __name__ == "__main__":
    main()
