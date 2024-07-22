Sure! Here's the updated `README.md` file to reflect the changes and provide clear instructions on how to use the package:

### README.md

```markdown
# Noouh Auto Model - Fillable V2

A Laravel package to auto-generate fillable properties for models based on table definitions from a JSON file.

## Installation

You can install the package via composer:

```bash
composer require noouh/auto-model-fillable
```

## Usage

To generate fillable properties for all models based on table definitions from a JSON file, use the following command:

```bash
php artisan noouh:generate-fillable path/to/your/json/file.json
```

### Example JSON File

The JSON file should contain the table definitions with the columns. Here is an example structure:

```json
[
  {
    "table": "users",
    "columns": [
      { "name": "id", "type": "int", "primaryKey": true, "autoIncrement": true },
      { "name": "name", "type": "string" },
      { "name": "email", "type": "string" }
    ]
  },
  {
    "table": "posts",
    "columns": [
      { "name": "id", "type": "int", "primaryKey": true, "autoIncrement": true },
      { "name": "title", "type": "string" },
      { "name": "content", "type": "text" },
      { "name": "user_id", "type": "unsignedBigInteger" }
    ],
    "relationships": [
      { "type": "belongsTo", "relatedTable": "users", "foreignKey": "user_id" }
    ]
  }
]
```

### How It Works

1. **Loop Through Model Files:** The script loops through all model files in the `app/Models` directory.
2. **Extract Table Name:** It extracts the table name from each model file using the `$table` property.
3. **Get Columns from JSON:** It retrieves the columns for the corresponding table from the provided JSON file.
4. **Update Fillable Properties:** It updates the model file with the fillable properties if the table and columns are found.

### About the Company

Noouh For Integrated Solutions is dedicated to providing innovative software solutions. We specialize in developing high-quality applications tailored to meet the specific needs of our clients.

Email: info@noouh.com
```
