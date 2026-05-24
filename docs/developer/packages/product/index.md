# Product Package

`gildsmith/product` contains the product catalog resources: products,
attributes, attribute values, blueprints, and product collections.

The package follows the Gildsmith resource conventions:

- records are addressed by `code`;
- writes go through facades or model relationships;
- routes are protected with Laravel `can` middleware;
- blueprint attributes define which attributes a product blueprint expects.

## Reference

- [API Reference](/packages/product/api-reference) documents the HTTP routes
  exposed by the package.
