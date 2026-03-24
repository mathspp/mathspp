This article covers a useful LLM pattern where you ask the LLM to write code to solve a problem instead of asking it to solve the problem directly.

===

## The problem of merging two transcripts

I had two files that contained two halves of the transcript of an audio recording and I wanted to use an LLM to merge the two halves.
There were three reasons that stopped me from simply copying part 2 and pasting it after part 1:

 1. the two transcripts overlapped (the end of part 1 was after the start of part 2);
 2. the timestamps for part 2 started from 0, so they were missing an offset; and
 3. speaker identification was not consistent.

I uploaded the two halves into ChatGPT and asked it to merge the two transcripts, fix the timestamps and the speaker identification, but to not change the text.

The result I got back was a ridiculous attempt at providing the full transcript, with two sections that supposedly represented parts of either transcript I could just copy and paste confidently, and a couple of other ridiculous blunders.

Instead of fighting ChatGPT, I decided to use a very useful pattern I learned about last year.

## Ask the LLM to write code for it

Instead of asking ChatGPT to merge the transcripts, I could ask it to analyse them, find the solutions to the three problems listed above, and then write code that would merge the transcripts.

Since I was confident that ChatGPT could

 1. identify the overlap between the two files;
 2. use the overlap information to compute the timestamp offset required for part 2; and
 3. figure out you had to swap the two speakers in part 2,

I knew ChatGPT would be able to write a Python script that could read from both files and apply a couple of string operations to the second part.

This yielded much better results in two ways.
ChatGPT was able to find the solutions for the three problems above and write a script that fixed them automatically.
That was the goal.

On top of that, since ChatGPT had a very clear implicit goal — get the final merged transcript — and since running Python code is something that ChatGPT can do, ChatGPT even ran the script for me and produced two artifacts at the end:

 1. the full Python script I could run against the two halves if I wanted; and
 2. the final, fixed transcript.

This is an example application of a really useful LLM pattern:

 > Don't ask the LLM to solve a problem. Instead, ask it to write code that solves the problem.

As another visual example, it's much easier to ask an LLM to write a Python script that draws a path that solves a maze (that's just a couple hundred of lines of code) than it is to upload an image and ask the LLM to draw a _valid path_ on the picture of a maze.
Try it yourself!
