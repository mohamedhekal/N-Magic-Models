<?php

namespace Noouh\AutoModel\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateModelsCommand extends Command
{
    protected $signature = 'generate:models {filePath}';
    protected $description = 'Generate models and migrations based on a JSON file';

    public function handle()
    {
        $filePath = $this->argument('filePath');
        if (!File::exists($filePath)) {
            $this->error("File does not exist: $filePath");
            return;
        }

        $fileContent = File::get($filePath);
        $tableDefinitions = json_decode($fileContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error("Invalid JSON in file: $filePath");
            return;
        }

        foreach ($tableDefinitions as $tableData) {
            $tableName = $tableData['table'];
            $columns = $tableData['columns'];
            $relationships = $tableData['relationships'] ?? [];
            $this->generateModel($tableName, $columns, $relationships);
            $this->generateMigration($tableName, $columns, $relationships);
        }
    }

        protected function generateModel($table, $columns, $relationships)
    {
        $modelTemplate = file_get_contents(__DIR__ . '/../stubs/model.stub');
        $modelContent = str_replace(['DummyModel', 'dummy_table'], [ucfirst($table), $table], $modelTemplate);

        $fillableColumns = $this->generateFillableColumns($columns);
        $modelContent = str_replace('// Add fillable columns here', $fillableColumns, $modelContent);

        $relationshipsMethods = $this->generateRelationshipsMethods($relationships);
        $modelContent = str_replace('// Add relationships here', $relationshipsMethods, $modelContent);

        File::put(app_path("/Models/".ucfirst($table).".php"), $modelContent);
    }

        protected function generateFillableColumns($columns)
    {
        $fillable = array_map(function($column) {
            return "'{$column['name']}'";
        }, $columns);

        return 'protected $fillable = [' . implode(', ', $fillable) . '];';
    }

    protected function generateMigration($table, $columns, $relationships)
    {
        $migrationTemplate = file_get_contents(__DIR__ . '/../stubs/migration.stub');
        $migrationContent = str_replace('dummy_table', $table, $migrationTemplate);

        $columnsDefinition = $this->generateColumnsDefinition($columns);
        $migrationContent = str_replace('// Add columns here', $columnsDefinition, $migrationContent);

        $foreignKeys = $this->generateForeignKeys($relationships);
        $migrationContent = str_replace('// Add foreign keys here', $foreignKeys, $migrationContent);

        $timestamp = date('Y_m_d_His');
        File::put(database_path("/migrations/{$timestamp}_create_{$table}_table.php"), $migrationContent);
    }

    protected function generateColumnsDefinition($columns)
    {
        $columnsDefinition = '';
        foreach ($columns as $column) {
            $type = $column['type'];
            $name = $column['name'];
            $columnsDefinition .= "\$table->$type('$name');
            ";
        }
        return $columnsDefinition;
    }

    protected function generateRelationshipsMethods($relationships)
    {
        $methods = '';
        foreach ($relationships as $relationship) {
            $type = $relationship['type'];
            $relatedTable = ucfirst($relationship['relatedTable']);
            $foreignKey = $relationship['foreignKey'];

            if ($type == 'belongsTo') {
                $method = "
        public function $relatedTable()
        {
            return \$this->belongsTo('App\\Models\\$relatedTable', '$foreignKey');
        }
                ";
            } elseif ($type == 'hasMany') {
                $method = "
        public function " . strtolower($relatedTable) . "s()
        {
            return \$this->hasMany('App\\Models\\$relatedTable', '$foreignKey');
        }
                ";
            }

            $methods .= $method;
        }
        return $methods;
    }


    protected function generateForeignKeys($relationships)
    {
        $foreignKeys = '';
        foreach ($relationships as $relationship) {
            if ($relationship['type'] == 'belongsTo') {
                $foreignKey = $relationship['foreignKey'];
                $relatedTable = $relationship['relatedTable'];

                $foreignKeys .= "\$table->foreign('$foreignKey')->references('id')->on('$relatedTable');
            ";
            }
        }
        return $foreignKeys;
    }
}