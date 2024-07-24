from functools import wraps

def case_insensitive_decorator(method):
    @wraps(method)
    def case_insensitive_method(*args, **kwargs):
        args = tuple(CIStr(arg) if isinstance(arg, str) else arg for arg in args)
        kwargs = {
            key: CIStr(value) if isinstance(value, str) else value
            for key, value in kwargs.items()
        }
        return_value = method(*args, **kwargs)
        if isinstance(return_value, str):
            return_value = CIStr(return_value)
        elif isinstance(return_value, list | tuple):
            type_ = type(return_value)
            return_value = type_(
                CIStr(element) if isinstance(element, str) else element
                for element in return_value
            )
        return return_value

    return case_insensitive_method

def patch_string_methods(cls):
    for attribute_name in dir(cls):
        if attribute_name.startswith("__"):
            continue
        attribute = getattr(cls, attribute_name)
        if callable(attribute):
            setattr(cls, attribute_name, case_insensitive_decorator(attribute))

    return cls


@patch_string_methods
class CIStr(str):
    def __eq__(self, other):
        return str.__eq__(self.casefold(), other.casefold())

    def __ne__(self, other):
        return str.__ne__(self.casefold(), other.casefold())

    def __lt__(self, other):  # <
        return str.__lt__(self.casefold(), other.casefold())

    def __le__(self, other):  # <=
        return str.__le__(self.casefold(), other.casefold())

    def __gt__(self, other):  # >
        return str.__gt__(self.casefold(), other.casefold())

    def __ge__(self, other):  # >=
        return str.__ge__(self.casefold(), other.casefold())

assert CIStr("hello") == "HELlo"
assert CIStr("Hello") > "abracadabra"
assert CIStr("Hello").upper() == "hello"
hello, world = CIStr("Hello, world!").split(", ")
assert hello == "HELLO"
