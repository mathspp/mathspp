from dataclasses import dataclass


@dataclass
class Square:
    """Represents a possible square region for the platform."""

    x: int
    y: int
    length: int


def read_map(pathname: str) -> list[str]:
    """Reads the map from a file."""
    with open(pathname, "r") as file:
        return [line.strip() for line in file]


def expand(x: int, y: int, map: list[str]) -> int:
    """Finds the largest possible square with top-left corner at (x, y)."""
    length = 2
    while y + length - 1 < len(map) and x + length - 1 < len(map[0]):
        for dx in range(length):
            if map[y + length - 1][x + dx] != ".":
                return length - 1
        for dy in range(length):
            if map[y + dy][x + length - 1] != ".":
                return length - 1
        length += 1
    return length - 1


def find_biggest_square(map: list[str]) -> Square:
    """Find the biggest free square region in the given map."""
    bsq_so_far = Square(0, 0, 0)
    for y in range(len(map)):
        for x in range(len(map[0])):
            if map[y][x] != ".":
                continue

            length = expand(x, y, map)
            if length > bsq_so_far.length:
                bsq_so_far.length = length
                bsq_so_far.x = x
                bsq_so_far.y = y

            if x + bsq_so_far.length >= len(map[0]):
                break
        if y + bsq_so_far.length >= len(map):
            break
    return bsq_so_far


def print_map_with_square(map: list[str], bsq: Square) -> None:
    """Prints the map with the largest square marked."""
    for y in range(bsq.y):
        print(map[y])
    for y in range(bsq.y, bsq.y + bsq.length):
        print(map[y][: bsq.x] + "x" * bsq.length + map[y][bsq.x + bsq.length :])
    for y in range(bsq.y + bsq.length, len(map)):
        print(map[y])


if __name__ == "__main__":
    map = read_map("map.txt")
    bsq = find_biggest_square(map)
    print_map_with_square(map, bsq)
