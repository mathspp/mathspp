Want a sneak peek?  Here's a taste of the kind of code you'll write:

```python
# Compute the output of a single neuron with a sigmoid activation
import math

def sigmoid(x: float) -> float:
    return 1 / (1 + math.exp(-x))

def neuron(inputs, weights, bias):
    z = sum(w * x for w, x in zip(weights, inputs)) + bias
    return sigmoid(z)

# Example usage
output = neuron([0.5, 0.8], [1.2, -0.3], bias=0.1)
# 'output' contains the neuron's activation
```

Throughout the book you'll build on this foundation, wiring neurons into layers, stacking layers into networks and teaching them to learn from data.