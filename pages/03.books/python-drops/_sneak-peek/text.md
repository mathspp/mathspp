## Sneak peek

Here is tip 24, “longest word in a string”:

 > The built-in `max` has a keyword parameter `key` that determines how objects are compared, allowing flexible comparisons.
 > 
 > For example, the idiom `max(..., key=len)` lets you find the longest item in a collection, namely, the longest word in a string:
 > 
 > ```py
 > s = "These are just some sensational words"
 > print(
 >     max(s.split(), key=len)
 > )  # sensational
 > ```
 > 
 > The built-ins `min` and `sorted` also have this keyword parameter.
