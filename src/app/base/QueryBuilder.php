<?php

namespace app\base;

use app\models\ListContact;
use Exception;
use PDO;

abstract class QueryBuilder
{
    protected PDO    $pdo;
    protected string $tableName;
    protected string $modelClassName;
    protected array  $fields    = [];
    protected array  $join      = [];
    protected array  $where     = [];
    protected array  $orderBy   = [];
    protected ?int   $limit     = null;
    protected int    $offset    = 0;

    abstract public function getSchema(): array;

    abstract public function create(array $data): DataMapper;

    abstract public function update(int $id, array $data): DataMapper;

    abstract public function save(DataMapper $dataMapper): DataMapper;

    public function delete(int $id): void
    {
        $this->pdo->query("DELETE FROM {$this->tableName} WHERE id = {$id}");
    }

    public function reset(): void
    {
        $this->fields = $this->where = $this->join = [];
    }

    public function select(array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    public function from(string $tableName): self
    {
        $this->tableName = $tableName;

        return $this;
    }

    public function where(string $field, $operator, $value = null): self
    {
        [$operator, $value] = $this->prepareWhere($operator, $value);

        $where = "{$field} {$operator} {$value}";
        if (!empty($this->where)) {
            $where = " AND {$where}";
        }
        $this->where[] = $where;

        return $this;
    }

    public function orWhere(string $field, $operator, $value = null): self
    {
        [$operator, $value] = $this->prepareWhere($operator, $value);

        $this->where[] = " OR {$field} {$operator} {$value}";

        return $this;
    }

    public function orderBy(string $column, string $sortOrder = 'ASC'): self
    {
        $sortOrder     = strtoupper($sortOrder);
        if (!in_array($sortOrder, ['ASC', 'DESC'])) {
            $sortOrder = 'ASC';
        }
        $this->orderBy[$column] = $sortOrder;

        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    public function first(): ?DataMapper
    {
        $this->limit  = 1;
        $this->offset = 0;
        $queryString  = $this->prepare();

        $query = $this->pdo->prepare($queryString);

        foreach ($this->where as $field => $value) {
            $query->bindColumn(":{$field}", $value);
        }

        return $this->pdo->prepare($queryString)->fetchObject($this->tableName);
    }

    public function all(): Collection
    {
        $queryString = $this->prepare();

        $items = $this->pdo
            ->query($queryString)
            ->fetchAll(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                $this->modelClassName
            );

        return new Collection($items);
    }

    abstract protected function prepareInsert(): string;

    abstract protected function prepareUpdate(): string;

    /**
     * @throws Exception
     */
    protected function dataManipulationQuery(bool $create, array $data, int $id = 0)
    {
        $this->fields = $data;

        $queryString = $create
            ? $this->prepareInsert()
            : $this->prepareUpdate();

        $query = $this->pdo->prepare($queryString);

        foreach ($this->fields as $field => $value) {
            $query->bindColumn(":{$field}", $value);
        }

        $query->execute();

        if ($create) {
            $id = (int)$this->pdo->lastInsertId();
        }

        // todo error processing
        $query = $this->pdo->query("SELECT * FROM {$this->tableName} WHERE id = {$id}");
        $query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->modelClassName);
        return $query->fetch();
    }

    private function prepare(): string
    {
        if (empty($this->fields)) {
            $this->fields = [$this->tableName . '.*'];
        }

        $orderBy = [];
        foreach ($this->orderBy as $column => $direction) {
            $orderBy[] = "{$column} {$direction}";
        }
        $queryString = str_replace(
            [
                '%SELECT%',
                '%JOIN%',
                '%WHERE%',
                '%ORDER_BY%',
                '%LIMIT_OFFSET%',
            ],
            [
                implode(',', $this->fields),
                implode("\n", $this->join),
                $this->where
                    ? 'WHERE ' . implode("\n", $this->where)
                    : '',
                "ORDER BY " . implode(', ', $orderBy),
                $this->limit
                    ? "LIMIT {$this->limit} OFFSET {$this->offset}"
                    : ''
            ],
            "SELECT %SELECT% FROM {$this->tableName} %JOIN% %WHERE% %ORDER_BY% %LIMIT_OFFSET%"
        );

        return $queryString;
    }

    private function prepareWhere($operator, $value): array
    {
        if ($value === null) {
            $value    = $operator;
            $operator = '=';
        }

        if (is_array($value)) {
            $operator = 'IN';
            $value    = '(' . implode(',', $value) . ')';
        }

        return [$operator, $value];
    }
}
