Learn how to use exception handling in Python effectively with the `try` block and the statements `except`, `else`, and `finally`.

===

![A code snippet with the definition of a Python class and a method called “__dunder_method__” with no code whatsoever.](thumbnail.png)

(If you are new here and have no idea what a Pydon't is, you may want to read the
[Pydon't Manifesto][manifesto].)


# Introduction



The short answer is “yes”, and that happens through **dunder methods**,
the object of study in this Pydon't.
In this Pydon't, you will

 - 


<!--v-->
!!! You can get all the Pydon'ts as a [free ebook with over +400 pages and hundreds of tips](/books/pydonts). [Download the ebook “Pydon'ts – write elegant Python code” here](/books/pydonts).
<!--^-->


I. Introduction

Brief overview of exception handling
Importance of exception handling in writing robust code


II. Basics of try block

Syntax of try block
Explanation of how try block works


III. Exception handling with except block

Syntax of except block
Explanation of how except block handles exceptions
Examples of using except block with various types of exceptions


IV. Handling multiple exceptions with except block

Syntax of handling multiple exceptions with except block
Explanation of how to handle multiple exceptions with except block
Examples of using except block to handle multiple exceptions


V. Using else statement with try block

Syntax of else statement with try block
Explanation of how else statement works with try block
Examples of using else statement to handle exceptions


VI. Using finally block

Syntax of finally block
Explanation of how finally block works
Examples of using finally block


VII. Nesting try blocks

Explanation of nesting try blocks
Examples of using nested try blocks


VIII. Best practices for exception handling with try block

Avoid using except block without specifying the type of exception
Handle exceptions at the appropriate level of abstraction
Avoid swallowing exceptions
Use finally block to release resources


IX. Conclusion

Summary of key points covered in the article
Importance of exception handling in writing robust code.



# Bare except clause

except statement
The except is used to indicate what error(s) you can handle and how to handle them.
You should avoid using except by itself, which are typically called bare excepts.
Instead, you should strive to write down exactly what exception(s) you want to handle.
For example, consider this mock code that is trying to connect to Twitter to download the most recent image I tweeted about Python to save it in my computer.
When doing that, I might get an exception TweetHasNoImageException if my last tweet did not share an image...
I might also get an QuotaException if I exceeded my API quota and called that function too many times this month.
If I get those errors, I do not want to save any images, so I might write this:

```py
user = twitter_api.connect(my_credentials)
try:
    tweeted_image = user.get_last_tweet().image
except:
    pass
```

Now, if the user tried pressing Ctrl + C to stop the program while we were waiting for Twitter to reply, the program would not stop because the bare except also captured that exception and tried handling it.



# Conclusion

Here's the main takeaway of this Pydon't, for you, on a silver platter:

 > “**”

This Pydon't showed you that:

 - 

<!-- v -->
If you liked this Pydon't be sure to leave a reaction below and share this with your friends and fellow Pythonistas.
Also, [don't forget to subscribe to the newsletter][subscribe] so you don't miss
a single Pydon't!
<!-- ^ -->

[subscribe]: https://mathspp.com/subscribe
[manifesto]: /blog/pydonts/pydont-manifesto
[gumroad-pydonts]: https://gum.co/pydonts
