<?php

namespace app\base;

interface DataMapper
{
    public static function create(array $data): self;
    public static function update(int $id, array $data): self;
    public static function delete(int $id): void;
    public function save(): void;
}
