# /// script
# requires-python = ">=3.14"
# dependencies = [
#     "pillow",
# ]
# ///

import argparse
from PIL import Image

def jpeg_to_txt(input_path: str, output_path: str, threshold: int = 128):
    """
    Convert a black & white JPEG image into a text file with 0s and 1s.

    0 → white (pixel >= threshold)
    1 → black (pixel < threshold)
    """
    img = Image.open(input_path).convert("L")
    width, height = img.size

    with open(output_path, "w") as f:
        for y in range(height):
            row_bits = []
            for x in range(width):
                pixel = img.getpixel((x, y))
                bit = "1" if pixel < threshold else "0"
                row_bits.append(bit)
            f.write("".join(row_bits) + "\n")


def main():
    parser = argparse.ArgumentParser(
        description="Convert a black & white JPEG image into a text file with 0s and 1s."
    )
    parser.add_argument("input", help="Path to the input JPEG image.")
    parser.add_argument("output", help="Path to the output text file.")
    parser.add_argument(
        "-t", "--threshold",
        type=int,
        default=128,
        help="Threshold for deciding black (1) vs white (0). Default: 128."
    )

    args = parser.parse_args()

    jpeg_to_txt(args.input, args.output, args.threshold)


if __name__ == "__main__":
    main()
