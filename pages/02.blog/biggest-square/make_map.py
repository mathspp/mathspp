import random


def create_map(map_path: str, width: int, height: int, obstacle_prob: float) -> None:
    """Creates a map with the given size."""
    with open(map_path, "w") as file:
        for _ in range(height):
            for _ in range(width):
                char = "o" if random.random() < obstacle_prob else "."
                file.write(char)
            file.write("\n")


if __name__ == "__main__":
    create_map("map", 100, 25, 0.01)
