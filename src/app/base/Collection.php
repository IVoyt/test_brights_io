<?php

namespace app\base;

use Iterator;

class Collection implements Iterator
{
    /** @var Model[] */
    private array $items;
    private int $position = 0;
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function toJson(): string
    {
        $items = [];
        foreach ($this->items as $item) {
            $items[] = $item->toJson();
        }

        return json_encode($items);
    }

    public function toArray(): array
    {
        $items = [];
        foreach ($this->items as $item) {
            $items[] = $item->toArray();
        }

        return $items;
    }

    public function current(): mixed
    {
        return $this->items[$this->position];
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function key(): mixed
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }
}
