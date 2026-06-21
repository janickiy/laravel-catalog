<?php

namespace App\Services\Admin;

use App\Helpers\StringHelper;
use App\Imports\LinksImport;
use App\Imports\LinksImportFromCsv;
use App\Repositories\LinksRepository;
use Illuminate\Http\UploadedFile;
use InvalidArgumentException;
use Maatwebsite\Excel\Excel as ExcelReader;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;

class LinkImportExportService
{
    private const CSV_EXTENSIONS = ['csv', 'txt'];

    private const IMPORT_EXTENSIONS = ['csv', 'txt', 'xls', 'xlsx'];

    private const SPREADSHEET_READER_TYPES = [
        'xls' => ExcelReader::XLS,
        'xlsx' => ExcelReader::XLSX,
    ];

    public function __construct(
        private readonly LinksRepository $links,
        private readonly LinkImportProcessor $importProcessor,
    ) {}


    /**
     * Импортирует ссылки из CSV, XLS или XLSX файла.
     *
     * @param UploadedFile $file
     * @return int
     */
    public function import(UploadedFile $file): int
    {
        set_time_limit(0);

        $this->importProcessor->reset();

        return $this->importPath($file->getRealPath(), $file->getClientOriginalExtension());
    }

    /**
     * Импортирует ссылки из ZIP-архива с CSV, TXT, XLS или XLSX файлами.
     *
     * @param UploadedFile $file
     * @return int
     */
    public function importArchive(UploadedFile $file): int
    {
        set_time_limit(0);

        $zip = new ZipArchive;
        $openResult = $zip->open($file->getRealPath());

        if ($openResult !== true) {
            throw new InvalidArgumentException('Unable to open import archive.');
        }

        $this->importProcessor->reset();
        $temporaryDirectory = $this->temporaryDirectory();
        $hasImportFiles = false;

        try {
            for ($index = 0; $index < $zip->numFiles; $index++) {
                $name = $zip->getNameIndex($index);

                if (! is_string($name) || str_ends_with($name, '/')) {
                    continue;
                }

                $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                if (! in_array($extension, self::IMPORT_EXTENSIONS, true)) {
                    continue;
                }

                $hasImportFiles = true;
                $path = $this->copyArchiveEntry($zip, $name, $extension, $temporaryDirectory);
                $this->importPath($path, $extension);
            }
        } finally {
            $zip->close();
            $this->removeDirectory($temporaryDirectory);
        }

        if (! $hasImportFiles) {
            throw new InvalidArgumentException('Import archive does not contain supported files.');
        }

        return $this->importProcessor->importedCount();
    }

    /**
     * Импортирует файл по абсолютному пути без сброса состояния процессора.
     *
     * @param string|false $path
     * @param string $extension
     * @return int
     */
    private function importPath(string|false $path, string $extension): int
    {
        if (! is_string($path) || $path === '') {
            throw new InvalidArgumentException('Import file path is invalid.');
        }

        $extension = strtolower($extension);

        if (in_array($extension, self::CSV_EXTENSIONS, true)) {
            return (new LinksImportFromCsv($this->importProcessor))->import($path);
        }

        $import = new LinksImport($this->importProcessor);
        Excel::import($import, $path, null, $this->spreadsheetReaderType($extension));

        return $import->importedCount();
    }

    /**
     * Формирует HTTP-ответ с экспортом ссылок в текстовом или XLSX формате
     *
     * @param int|null $catalogId
     * @param string $type
     * @param string $compress
     * @return Response|BinaryFileResponse
     */
    public function export(?int $catalogId, string $type, string $compress): Response|BinaryFileResponse
    {
        $links = $this->links->publishedForExport($catalogId);
        $filename = 'emailexport'.date('d_m_Y').($type === 'excel' ? '.xlsx' : '.txt');
        $contents = $type === 'excel'
            ? $this->excelContents($links)
            : $this->textContents($links);

        if ($compress === 'zip') {
            return $this->zipResponse($filename, $contents);
        }

        return response($contents, 200, [
            'Content-Disposition' => 'attachment; filename='.$filename,
            'Cache-Control' => 'max-age=0',
            'Content-Type' => StringHelper::getMimeType(pathinfo($filename, PATHINFO_EXTENSION)),
        ]);
    }

    /**
     * Преобразует коллекцию ссылок в текстовый экспорт.
     *
     * @param iterable $links
     * @return string
     */
    private function textContents(iterable $links): string
    {
        $contents = '';

        foreach ($links as $link) {
            $contents .= implode(';', [
                $link->city ?? '',
                $link->name,
                $link->catalog->name ?? '',
                $link->url,
                $link->phone ?? '',
                $link->email ?? '',
            ])."\r\n";
        }

        return $contents;
    }

    /**
     * Преобразует коллекцию ссылок в XLSX-файл в памяти.
     *
     * @param iterable $links
     * @return string
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    private function excelContents(iterable $links): string
    {
        $spreadsheet = new Spreadsheet;
        $spreadsheet->getProperties()
            ->setCreator('Alexander Yanitsky')
            ->setLastModifiedBy('My links manager')
            ->setTitle('Office 2007 XLSX Document')
            ->setSubject('Office 2007 XLSX Document')
            ->setDescription('Document for Office 2007 XLSX, generated using PHP classes.')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('Links export file');

        $sheet = $spreadsheet->setActiveSheetIndex(0);
        $sheet
            ->setCellValue('A1', __('interface.common.city'))
            ->setCellValue('B1', __('interface.common.name'))
            ->setCellValue('C1', __('interface.common.category'))
            ->setCellValue('D1', 'URL')
            ->setCellValue('E1', __('interface.common.phone'))
            ->setCellValue('F1', 'Email');

        $i = 1;

        foreach ($links as $row) {
            $i++;
            $sheet
                ->setCellValue('A'.$i, $row->city ?? '')
                ->setCellValue('B'.$i, $row->name)
                ->setCellValue('C'.$i, $row->catalog->name ?? '')
                ->setCellValue('D'.$i, $row->url)
                ->setCellValue('E'.$i, $row->phone)
                ->setCellValue('F'.$i, $row->email ?? '');
        }

        foreach (['A' => 30, 'B' => 60, 'C' => 30, 'D' => 30, 'E' => 30, 'F' => 30] as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_start();
        $writer->save('php://output');

        return (string) ob_get_clean();
    }

    /**
     * Упаковывает экспорт в ZIP и возвращает ответ для скачивания
     *
     * @param string $filename
     * @param string $contents
     * @return BinaryFileResponse
     */
    private function zipResponse(string $filename, string $contents): BinaryFileResponse
    {
        $zipPath = tempnam(sys_get_temp_dir(), 'links_export_').'.zip';
        $zip = new ZipArchive;
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFromString($filename, $contents);
        $zip->close();

        return response()->download($zipPath, 'emailexport_'.date('d_m_Y').'.zip')->deleteFileAfterSend(true);
    }

    /**
     * Создает временную директорию для безопасной обработки архива.
     *
     * @return string
     */
    private function temporaryDirectory(): string
    {
        $path = sys_get_temp_dir().DIRECTORY_SEPARATOR.'links_import_'.bin2hex(random_bytes(8));
        mkdir($path, 0700, true);

        return $path;
    }

    /**
     * Копирует один файл из ZIP-архива во временную директорию.
     *
     * @param ZipArchive $zip
     * @param string $name
     * @param string $extension
     * @param string $directory
     * @return string
     */
    private function copyArchiveEntry(ZipArchive $zip, string $name, string $extension, string $directory): string
    {
        $stream = $zip->getStream($name);

        if (! is_resource($stream)) {
            throw new InvalidArgumentException('Unable to read import archive entry: '.$name);
        }

        $temporaryPath = tempnam($directory, 'entry_');
        $path = $temporaryPath.'.'.$extension;
        rename($temporaryPath, $path);

        $target = fopen($path, 'wb');

        if (! is_resource($target)) {
            fclose($stream);

            throw new InvalidArgumentException('Unable to write temporary import file.');
        }

        stream_copy_to_stream($stream, $target);
        fclose($stream);
        fclose($target);

        return $path;
    }

    /**
     * Удаляет временную директорию с файлами импорта.
     *
     * @param string $directory
     * @return void
     */
    private function removeDirectory(string $directory): void
    {
        if (! is_dir($directory)) {
            return;
        }

        foreach (glob($directory.DIRECTORY_SEPARATOR.'*') ?: [] as $path) {
            is_dir($path) ? $this->removeDirectory($path) : @unlink($path);
        }

        @rmdir($directory);
    }

    /**
     * Возвращает тип reader для импорта таблицы по расширению файла.
     *
     * @param string $extension
     * @return string
     */
    private function spreadsheetReaderType(string $extension): string
    {
        if (! isset(self::SPREADSHEET_READER_TYPES[$extension])) {
            throw new InvalidArgumentException('Unsupported import file extension: '.$extension);
        }

        return self::SPREADSHEET_READER_TYPES[$extension];
    }
}
