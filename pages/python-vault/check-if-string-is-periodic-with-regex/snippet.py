import re

_PERIODIC_RE = re.compile(r"^(.+)\1+$")

def is_periodic(string):
    """Check if this string can be formed by repeating one of its prefixes.
    
    Examples:
        >>> is_periodic("ababab")
        True
        >>> is_periodic("aba")
        False
    """
    return bool(_PERIODIC_RE.fullmatch(string))
