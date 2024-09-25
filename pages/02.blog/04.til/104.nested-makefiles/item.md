Today I learned how to call a makefile from within another makefile.

===


# Nested makefiles

I like to use [make](https://www.gnu.org/software/make/manual/make.html) and makefiles on my projects and today I needed to call a makefile from within another makefile...
I was pretty sure there was a “proper” way to do it and I was right.

A quick search revealed I should be using the variable `$(MAKE)` and that I could also use the option `-C` to change the directory from where I'm reading my makefile.

As a simple example, consider the following directory structure:

```
- root
|- Makefile
|- subfolder
 |- Makefile
```

Paste the following in `root/Makefile`:

```make
root:
	@echo "Root directory makefile."

nest:
	@echo "About to call nested makefile."
	$(MAKE) -C subfolder $@
```

Running `make root` from within the folder `root` should print “Root directory makefile.”.

Now, paste the following in `root/subfolder/Makefile`:

```make
nest:
    @echo "From nested Makefile"

up:
    @echo "About to call root makefile"
    $(MAKE) -C .. root
```

If you're in `root`, running `make nest` should print

```
About to call nested makefile.
/path/to/make -C subfolder nest
From nested Makefile
```

If you're in `subfolder`, running `make up` should print

```
About to call root makefile
path/to/make -C .. root
Root directory makefile.
```

Also note that in the top rule `nest` I used `$@` to refer to a rule with the same name in `subfolder/Makefile`, whereas in the rule `up` I used the explicit target `root`.
