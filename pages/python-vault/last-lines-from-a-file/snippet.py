from collections import deque

with open("/path/to/file.txt", "r") as f:
    last_lines = deque(f, maxlen=10)  # Last 10 lines from the file.
