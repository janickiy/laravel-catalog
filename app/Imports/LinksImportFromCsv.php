<?php

namespace App\Imports;

use App\Services\Admin\LinkImportProcessor;
use League\Csv\Reader;

final class LinksImportFromCsv
{
    private const DELIMITER = ';';

    public function __construct(private readonly LinkImportProcessor $processor) {}

    /**
     * Импортирует CSV/TXT файл построчно через общий процессор.
     */
    public function import(string $path): int
    {
        $csv = Reader::from($path, 'r');
        $csv->setDelimiter(self::DELIMITER);

        foreach ($csv as $row) {
            $this->processor->importRow($row);
        }

        return $this->processor->importedCount();
    }
}
