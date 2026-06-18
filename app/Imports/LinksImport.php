<?php

namespace App\Imports;

use App\Services\Admin\LinkImportProcessor;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithColumnLimit;
use Maatwebsite\Excel\Row;

final class LinksImport implements OnEachRow, SkipsEmptyRows, WithChunkReading, WithColumnLimit
{
    private const CHUNK_SIZE = 500;

    private const END_COLUMN = 'H';

    public function __construct(private readonly LinkImportProcessor $processor) {}

    /**
     * Передает очередную строку таблицы в процессор импорта.
     */
    public function onRow(Row $row): void
    {
        $this->processor->importRow($row->toArray(null, false, false, self::END_COLUMN));
    }

    /**
     * Возвращает размер чанка для чтения больших файлов.
     */
    public function chunkSize(): int
    {
        return self::CHUNK_SIZE;
    }

    /**
     * Ограничивает чтение таблицы последней нужной колонкой.
     */
    public function endColumn(): string
    {
        return self::END_COLUMN;
    }

    /**
     * Возвращает количество успешно импортированных строк.
     */
    public function importedCount(): int
    {
        return $this->processor->importedCount();
    }
}
