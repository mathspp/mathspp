Today I learned how to optimise my website images for the web and I automated that process.

===

## Image optimisation

Up until today, most of the images on this website and on this blog were PNG files.
I also had some JPGs here and there, but I had mostly PNG files.

Today, this changed.
I had read in a couple of places already that WEBP files were better for the internet because they tended to achieve smaller file sizes for the same images.
So, I decided to go ahead and do it.
I optimised all my images and converted most of them to WEBP files...

But I didn't want to do it by hand, obviously, so I looked for tools to help me out.


## CLI tool for image optimisation

The first thing I looked for was a CLI tool that would allow me to optimise my images, and I found [optimizt].
This was great because optimizt allows me to compress the files I already had, it lets me pick whether I want the compression to be lossless or not, and it lets me convert image files to WEBP.


## Optimising images

### Lossless compression

I started by compressing all of my images losslessly but I preserved their extensions.
This was done with the command

```bash
optimizt --lossless directory
```

This meant I could just apply the compression and I wouldn't have to worry about fixing the links inside my pages.

Optimizt compressed 372 images in total and it saved me an average of 20% for each image, if I remember correctly.
This was merged in [a huge PR](https://github.com/mathspp/mathspp/pull/151).


### Convert to WEBP

After compressing everything losslessly, I decided to convert everything to WEBP.
The way optimizt does this is by looking for images and creating new WEBP images out of those.
So, when optimizt is done, you end up with either:

 - only the original image if the WEBP was heavier; or
 - the original and the new WEBP version.

I used the command

```bash
optimizt --lossless --webp pages
```

(In hindsight, I don't think I needed the option `--lossless` there.)

After creating the WEBP images, I had to fix the links to the images, which would mean modifying thousands of links if I did it by hand.

I couldn't use regular expressions because optimizt only creates the WEBP file when it is lighter than the original one, and I knew not _all_ images had been converted to WEBP, so I couldn't blindly search for `.png` and `.jpg` and replace with `.webp`.

So, what I did was write a short script that uses the module `pathlib` to go over all of the WEBP images it created, figure out what was the original image, open files that may contain links, and fix those links.

The modifications made by this script shouldn't create broken links, but it isn't guaranteed that it will fix _all_ of the links.
If a page references an image from another page, this won't find that broken link.

Either way, now I will use another tool to scan my blog for broken image links and then manually fix those.
Hopefully, there won't be too many of those!

For reference, this is the script I wrote and ran:

```py
from functools import lru_cache
from itertools import chain
from pathlib import Path
from typing import Iterable


IMAGE_EXTENSIONS = {".gif", ".jpg", ".jpeg", ".png"}
CONTENT_FILE_EXTENSIONS = {".md", ".yaml", ".yml", ".html"}


@lru_cache(maxsize=128)
def get_content_files(folder: Path) -> list[Path]:
    """Get all content files within a folder."""
    return list(chain.from_iterable(
        folder.glob(f"*{file_ext}") for file_ext in CONTENT_FILE_EXTENSIONS
    ))


@lru_cache(maxsize=128)
def get_other_images(image: Path) -> Iterable[str]:
    """Get possible paths of unoptimised images."""
    return {image.with_suffix(img_ext).name for img_ext in IMAGE_EXTENSIONS}


webp_images = Path("pages").rglob("*.webp")
for image in webp_images:
    image_name = image.name
    folder = image.parent
    for content_file in get_content_files(folder):
        original = content = content_file.read_text()
        for other_name in get_other_images(image):
            content = content.replace(other_name, image_name)
        if original != content:
            print(f"Modifying {content_file}")
        content_file.write_text(content)
    for other_name in get_other_images(image):
        image.with_name(other_name).unlink(missing_ok=True)
```


[optimizt]: https://github.com/funbox/optimizt


That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
