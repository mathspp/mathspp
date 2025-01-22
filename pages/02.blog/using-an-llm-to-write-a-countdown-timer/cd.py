#!/usr/bin/env -S uv run
# /// script
# requires-python = ">=3.13"
# dependencies = [
#     "pygame",
# ]
# ///
import sys
import pygame
import time
from pygame.locals import QUIT, KEYDOWN, K_SPACE

# Initialize pygame
pygame.init()

# Screen dimensions and colors
SCREEN_WIDTH, SCREEN_HEIGHT = 400, 200
WHITE = (255, 255, 255)
BLACK = (0, 0, 0)

# Fonts and other settings
FONT = pygame.font.Font(None, 80)
FPS = 60

# Parse command-line argument for the timer duration
if len(sys.argv) != 2:
    print("Usage: python countdown.py <minutes(:seconds)?>")
    sys.exit(1)

minutes, _, seconds = sys.argv[1].partition(":")
remaining_time = 1 + int(minutes) * 60 + int(seconds or "0")
end_time = time.time() + remaining_time

# Set up the display
screen = pygame.display.set_mode((SCREEN_WIDTH, SCREEN_HEIGHT))
pygame.display.set_caption("Countdown Timer")

# Clock for controlling the frame rate
clock = pygame.time.Clock()

reverse_colors = False
color_timer = 0

running = True
while running:
    # Event handling
    for event in pygame.event.get():
        if event.type == QUIT:
            running = False
        elif event.type == KEYDOWN:
            if event.key == K_SPACE:
                end_time = max(time.time() + 1, end_time) + 60
                reverse_colors = False  # In case timer was restarted while reversed

    # Timer logic
    current_time = time.time()
    if current_time + 1 >= end_time:
        # Reverse colors every second when the timer hits 0
        color_timer += 1 / FPS
        if color_timer >= 1:
            reverse_colors = not reverse_colors
            color_timer = 0
        remaining_time = 0
    else:
        remaining_time = end_time - current_time

    # Set colors based on reverse state
    bg_color = BLACK if reverse_colors else WHITE
    text_color = WHITE if reverse_colors else BLACK

    # Render the timer
    screen.fill(bg_color)
    minutes_display = int(remaining_time // 60)
    seconds_display = int(remaining_time % 60)
    time_text = f"{minutes_display:02}:{seconds_display:02}"
    text_surface = FONT.render(time_text, True, text_color)
    text_rect = text_surface.get_rect(center=(SCREEN_WIDTH // 2, SCREEN_HEIGHT // 2))
    screen.blit(text_surface, text_rect)

    # Update the display
    pygame.display.flip()

    # Cap the frame rate
    clock.tick(FPS)

# Quit pygame
pygame.quit()
