"""
Utility script for the blogpost at https://mathspp.com/blog/solutions/counting-squares.
Draw all the different squares that exist in a square grid.
"""

import argparse
import pathlib
from PIL import Image, ImageDraw

if __name__ == "__main__":
    # python draw_squares.py thumbnail.png 61 -o "thumbnail_frames" --offset-x 2 --offset-y 2 --colour 255 0 0
    parser = argparse.ArgumentParser()

    parser.add_argument("image", help="The image wih the grid.", type=str)
    parser.add_argument("unit",
        help="The width, in pixels, of the unit square of the image.",
        type=int,
    )
    parser.add_argument("-o", "--output-folder",
        help="Folder to draw frames to.",
        default=".",
    )
    parser.add_argument("--spacing",
        help="How many pixels of spacing between each unit square.",
        default=2,
        type=int,
    )
    parser.add_argument("--offset-x",
        help="Offset in the x direction in pixels, to start drawing.",
        default=1,
        type=int,
    )
    parser.add_argument("--offset-y",
        help="Offset in the y direction in pixels, to start drawing.",
        default=1,
        type=int,
    )
    parser.add_argument("--colour",
        help="The RGB colour of the frame in the range 0-255, e.g. (100, 150, 20)",
        nargs=3,
        default=(0, 0, 0),
        type=int,
    )
    parser.add_argument("--width",
        help="The width of the rectangle drawn.",
        default=5,
        type=int,
    )

    args = parser.parse_args()
    print(args)

    im = Image.open(args.image)
    outpath = pathlib.Path(args.output_folder)
    outpath.mkdir(parents=True, exist_ok=True)

    lx = im.width//args.unit
    ly = im.height//args.unit
    maxl = min(lx, ly)
    colour = tuple(args.colour)

    for l in range(1, maxl + 1):
        for y in range(ly - l + 1):
            for x in range(lx - l + 1):
                # Start square from (x, y) corner.
                copy = im.copy()
                draw = ImageDraw.Draw(copy)
                ox = x*args.spacing + args.offset_x
                oy = y*args.spacing + args.offset_y
                draw.rectangle(
                    [
                        (ox + x*args.unit, oy + y*args.unit),
                        (ox + (x+l)*args.unit + (l-1)*args.spacing + 1, oy + (y+l)*args.unit + (l-1)*args.spacing + 1)
                    ],
                    outline = colour,
                    width = args.width
                )
                filename = outpath.joinpath(f"frame_{l}_{y*(lx-l+1) + x}.png")
                copy.save(filename)
