<?php

namespace app\base;

class Model implements DataMapper
{
    protected string       $tableName;
    protected array        $attributes = [];
    protected array        $fillable   = [];
    protected QueryBuilder $queryBuilder;

    public function __construct()
    {
        $this->queryBuilder = new MysqlQueryBuilder(static::class);
        $this->attributes   = $this->queryBuilder->from($this->tableName)->getSchema();
        $this->queryBuilder->reset();
        $this->queryBuilder->from($this->tableName);
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }
    }

    public function __set($name, $value): void
    {
        if (array_key_exists($name, $this->attributes)) {
            $this->attributes[$name] = $value;
        }
    }

    public static function query(): QueryBuilder
    {
        return (new static())->queryBuilder;
    }

    public static function where(string $field, $operator, $value = null): QueryBuilder
    {
        return (new static())->queryBuilder->where($field, $operator, $value);
    }

    public static function first(): ?DataMapper
    {
        return (new static())->queryBuilder->first();
    }

    public static function all(): Collection
    {
        return (new static())->queryBuilder->all();
    }

    public static function create(array $data): DataMapper
    {
        return (new static())->queryBuilder->create($data);
    }

    public static function update(int $id, array $data): DataMapper
    {
        return (new static())->queryBuilder->update($id, $data);
    }

    public function save(): void
    {
        $this->queryBuilder->save($this);
    }

    public static function delete(int $id): void
    {
        (new static())->queryBuilder->delete($id);
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    public function toJson(): string
    {
        return json_encode($this->attributes);
    }
}
