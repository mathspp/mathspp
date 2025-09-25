Today I learned about the shoelace formula to compute the area of arbitrary simple polygons.

===

If you have a polygon with no holes and that doesn't intersect itself you can use the shoelace formula to compute its area. If $P_i = (x_i, y_i), i = 1, \cdots, n$ are the vertices of the polygon, then the area is given by

$$
A = \frac12 \left| \sum_{i = 1}^{n} x_iy_{i + 1} - y_i x_{i + 1} \right|
$$

In the formula above, $P_{n + 1}$ is $P_1$.

The formula is super practical and easy to compute, which I find amusing given that it works for any (simple) polygon!
