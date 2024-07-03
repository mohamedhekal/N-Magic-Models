# Noouh Auto Model

A Laravel package to auto-generate models and migrations from table definitions.

## About the Company

**Noouh For Integrated Solutions** is dedicated to providing innovative software solutions. We specialize in developing high-quality applications tailored to meet the specific needs of our clients.

## About the Author

**Eng. Mohamed Hekal** is a senior developer at Noouh For Integrated Solutions. With years of experience in PHP and Laravel, Eng. Mohamed is passionate about creating efficient and scalable software solutions.

## Installation

To install the Noouh Auto Model package, you can require it using Composer:

```bash
composer require noouh/auto-model
```

## Publish the Stubs

After installing the package, publish the stubs using the following command:

```bash
php artisan vendor:publish --tag=stubs
```

This will publish the `model.stub` and `migration.stub` files to your project's `stubs` directory.

## Usage

Create a JSON file (e.g., `tables.json`) with your table definitions and relationships. Here is an example structure:

```json
[
    {
        "table": "users",
        "columns": [
            {"name": "name", "type": "string"},
            {"name": "email", "type": "string"}
        ]
    },
    {
        "table": "posts",
        "columns": [
            {"name": "title", "type": "string"},
            {"name": "content", "type": "text"},
            {"name": "user_id", "type": "unsignedBigInteger"}
        ],
        "relationships": [
            {"type": "belongsTo", "relatedTable": "users", "foreignKey": "user_id"}
        ]
    }
]
```

Run the command to generate models and migrations:

```bash
php artisan generate:models /path/to/tables.json
```

## Example

Given the following `tables.json` file:

```json
[
    {
        "table": "users",
        "columns": [
            {"name": "name", "type": "string"},
            {"name": "email", "type": "string"}
        ]
    },
    {
        "table": "posts",
        "columns": [
            {"name": "title", "type": "string"},
            {"name": "content", "type": "text"},
            {"name": "user_id", "type": "unsignedBigInteger"}
        ],
        "relationships": [
            {"type": "belongsTo", "relatedTable": "users", "foreignKey": "user_id"}
        ]
    }
]
```

Running the command:

```bash
php artisan generate:models /path/to/tables.json
```

Will generate:

- A `User` model in `app/Models/User.php`
- A `Post` model in `app/Models/Post.php` with a `belongsTo` relationship to the `User` model
- A migration file for the `users` table
- A migration file for the `posts` table with a foreign key to the `users` table

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).
