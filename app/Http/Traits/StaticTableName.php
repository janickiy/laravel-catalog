<?php

namespace App\Http\Traits;

trait StaticTableName
{
    /**
     * Возвращает имя таблицы Eloquent-модели.
     */
    public static function getTableName(): string
    {
        return with(new static)->getTable();
    }
}
