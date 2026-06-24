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
     * Passes the next spreadsheet row to the import processor.
     */
    public function onRow(Row $row): void
    {
        $this->processor->importRow($row->toArray(null, false, false, self::END_COLUMN));
    }

    /**
     * Returns the chunk size for reading large files.
     */
    public function chunkSize(): int
    {
        return self::CHUNK_SIZE;
    }

    /**
     * Limits spreadsheet reading to the last required column.
     */
    public function endColumn(): string
    {
        return self::END_COLUMN;
    }

    /**
     * Returns the number of successfully imported rows.
     */
    public function importedCount(): int
    {
        return $this->processor->importedCount();
    }
}
