<?php

namespace app\base;

use Exception;
use JetBrains\PhpStorm\NoReturn;
use PDO;
use PDOException;

/**
 *
 */
final class MysqlQueryBuilder extends QueryBuilder
{
    public function __construct(string $modelClassName)
    {
        $this->modelClassName = $modelClassName;
        $application          = Application::getInstance();
        $dbConfig             = $application->getDbConfig();

        try {
            $this->pdo = new PDO(
                "{$dbConfig['connection']}:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']}",
                $dbConfig['username'],
                $dbConfig['password']
            );
        } catch (PDOException $exception) {
            $this->renderError('Unable to connect to DB!', $exception);
        }
    }

    public function getSchema(): array
    {
        $attributes = [];
        try {
            $schema = $this->pdo->query("SHOW COLUMNS FROM {$this->tableName};")->fetchAll();
        } catch (PDOException $exception) {
            $this->renderError('Table was not found. Run migration first!', $exception);
        }

        foreach ($schema as $value) {
            $attributes[$value['Field']] = null;
        }

        return $attributes;
    }

    /**
     * @throws Exception
     */
    public function create(array $data): DataMapper
    {
        return $this->dataManipulationQuery(true, $data);
    }

    /**
     * @throws Exception
     */
    public function update(int $id, array $data): DataMapper
    {
        $this->where[] = ['AND' => ['id' => $id]];

        return $this->dataManipulationQuery(false, $data, $id);
    }

    public function save(DataMapper $dataMapper): DataMapper {}

    protected function prepareInsert(): string
    {
        if (empty($this->fields)) {
            throw new Exception('Fields must be set on INSERT query!');
        }

        $fields = [];
        foreach ($this->fields as $field => $value) {
            $fields[] = "{$field}='{$value}'";
        }

        return str_replace(
            [
                '%FIELD_VALUE%',
            ],
            [
                implode(',', $fields),
            ],
            "INSERT INTO {$this->tableName} SET %FIELD_VALUE%;"
        );
    }

    protected function prepareUpdate(): string
    {
        if (empty($this->fields)) {
            throw new Exception('Fields must be set on INSERT query!');
        }

        $fields = [];
        foreach ($this->fields as $field => $value) {
            $fields[] = "{$field}='{$value}'";
        }

        return str_replace(
            [
                '%FIELD_VALUE%',
                '%WHERE%',
            ],
            [
                implode(',', $fields),
                implode("\n", $this->where),
            ],
            "UPDATE {$this->tableName} SET %FIELD_VALUE% WHERE %WHERE%;"
        );
    }

    #[NoReturn] private function renderError(string $message, PDOException $exception): void
    {
        $title           = 'DB Error...';
        $originalMessage = $exception->getMessage();
        ob_start();
        include_once view_path('error.php');
        ob_get_contents();
        die();
    }
}
