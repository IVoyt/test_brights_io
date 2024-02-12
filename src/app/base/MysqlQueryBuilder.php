<?php

namespace app\base;

use app\models\ListContact;
use Exception;
use PDO;

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

        $this->pdo            = new PDO(
            "{$dbConfig['connection']}:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']}",
            $dbConfig['username'],
            $dbConfig['password']
        );
    }

    public function getSchema(): array
    {
        $attributes = [];
        $schema     = $this->pdo->query("SHOW COLUMNS FROM {$this->tableName};")->fetchAll();
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
}
