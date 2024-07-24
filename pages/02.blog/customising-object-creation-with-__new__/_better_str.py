def better_str(self):
    return f"<{self.__class__.__name__} object>"

class BetterStrMeta(type):
    def __new__(cls, *args, **kwargs):
        print("BetterStrMeta.__new__")
        cls_object = super().__new__(cls, *args, **kwargs)
        cls_object.__str__ = better_str
        return cls_object

class A1:
    ...

class A2(metaclass=BetterStrMeta):
    ...

print(A1())  # <__main__.A1 object at 0x46616501090>
print(A2())  # <A2 object>  <- cleaner!
