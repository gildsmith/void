# Product API Reference

The product package exposes Laravel routes for catalog resources. Routes are
protected with `can` middleware and use the shared Gildsmith resource abilities.
The application using the package decides what those abilities mean through
Laravel policies or gates.

Examples use JSON request bodies. Responses mirror the current Laravel/Eloquent
serialization of package models.

## Product Shape

```json
{
  "id": 1,
  "code": "linen-shirt",
  "name": {
    "en": "Linen Shirt",
    "pl": "Lniana koszula"
  },
  "blueprint_id": 1,
  "is_complete": true,
  "created_at": "2026-05-21T10:00:00.000000Z",
  "updated_at": "2026-05-21T10:00:00.000000Z",
  "deleted_at": null
}
```

## Products

### List Products

```http
GET /products
```

```json
[
  {
    "id": 1,
    "code": "linen-shirt",
    "name": {
      "en": "Linen Shirt"
    },
    "blueprint_id": 1,
    "is_complete": true
  }
]
```

### Create Product

```http
POST /products
Content-Type: application/json
```

```json
{
  "code": "linen-shirt",
  "name": {
    "en": "Linen Shirt"
  },
  "blueprint_id": 1
}
```

Returns the created product.

### Get Product

```http
GET /products/{code}
```

Returns the product matching `{code}`.

### Update Product

```http
PATCH /products/{code}
Content-Type: application/json
```

```json
{
  "name": {
    "en": "Washed Linen Shirt"
  }
}
```

`PUT /products/{code}` uses the same controller. Returns the updated product, or
`null` when the product does not exist.

### Trash, Restore, And Delete Product

```http
POST /products/{code}/trash
POST /products/{code}/restore
DELETE /products/{code}
GET /products/trashed
```

Trash, restore, and delete endpoints return a boolean-like response. Trashed
listing returns an array of soft-deleted products.

## Attributes

Attributes define product characteristics such as `colour`, `size`, or
`material`.

```http
GET /attributes
POST /attributes
GET /attributes/{code}
PUT /attributes/{code}
PATCH /attributes/{code}
POST /attributes/{code}/trash
POST /attributes/{code}/restore
DELETE /attributes/{code}
GET /attributes/trashed
```

Create request:

```json
{
  "code": "colour",
  "name": {
    "en": "Colour"
  }
}
```

Response:

```json
{
  "id": 1,
  "code": "colour",
  "name": {
    "en": "Colour"
  },
  "deleted_at": null
}
```

## Attribute Values

Attribute values are nested under attributes.

```http
GET /attributes/{attribute}/values
POST /attributes/{attribute}/values
GET /attributes/{attribute}/values/{value}
PUT /attributes/{attribute}/values/{value}
PATCH /attributes/{attribute}/values/{value}
POST /attributes/{attribute}/values/{value}/trash
POST /attributes/{attribute}/values/{value}/restore
DELETE /attributes/{attribute}/values/{value}
GET /attributes/{attribute}/values/trashed
```

Create request:

```json
{
  "code": "red",
  "name": {
    "en": "Red"
  }
}
```

Response:

```json
{
  "id": 1,
  "attribute_id": 1,
  "code": "red",
  "name": {
    "en": "Red"
  },
  "deleted_at": null
}
```

## Blueprints

Blueprints describe the expected structure for products.

```http
GET /blueprints
POST /blueprints
GET /blueprints/{code}
PUT /blueprints/{code}
PATCH /blueprints/{code}
POST /blueprints/{code}/trash
POST /blueprints/{code}/restore
DELETE /blueprints/{code}
GET /blueprints/trashed
```

Create request:

```json
{
  "code": "chair",
  "name": {
    "en": "Chair"
  }
}
```

Response:

```json
{
  "id": 1,
  "code": "chair",
  "name": {
    "en": "Chair"
  },
  "deleted_at": null
}
```

## Blueprint Attributes

Blueprint attributes define which attributes belong to a blueprint and whether
they are required.

### List Blueprint Attributes

```http
GET /blueprints/{code}/attributes
```

Response:

```json
[
  {
    "id": 1,
    "code": "colour",
    "name": {
      "en": "Colour"
    },
    "pivot": {
      "blueprint_id": 1,
      "attribute_id": 1,
      "required": true
    }
  }
]
```

### Attach Blueprint Attributes

```http
POST /blueprints/{code}/attributes/{attribute}
Content-Type: application/json
```

```json
{
  "required": true
}
```

`{code}` is the blueprint code. `{attribute}` is the attribute code. Attaching a
required attribute marks products using the blueprint incomplete. Returns the
blueprint's attributes.

### Update Blueprint Attribute Requirements

```http
PATCH /blueprints/{code}/attributes/{attribute}
Content-Type: application/json
```

```json
{
  "required": false
}
```

Setting an existing attribute to required marks products using the blueprint
incomplete. Returns the blueprint's attributes.

### Detach Blueprint Attributes

```http
DELETE /blueprints/{code}/attributes/{attribute}
```

Detaching an attribute removes matching product attribute values from products
using the blueprint. The endpoint returns a boolean-like response.

## Product Collections

Product collections group products for arbitrary catalog purposes.

```http
GET /collections
POST /collections
GET /collections/{code}
PUT /collections/{code}
PATCH /collections/{code}
POST /collections/{code}/trash
POST /collections/{code}/restore
DELETE /collections/{code}
GET /collections/trashed
```

Create request:

```json
{
  "code": "summer",
  "type": "seasonal",
  "name": {
    "en": "Summer"
  }
}
```

Response:

```json
{
  "id": 1,
  "code": "summer",
  "type": "seasonal",
  "name": {
    "en": "Summer"
  },
  "created_at": "2026-05-21T10:00:00.000000Z",
  "updated_at": "2026-05-21T10:00:00.000000Z",
  "deleted_at": null
}
```
