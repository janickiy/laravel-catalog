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

        $extension = strtolower($file->getClientOriginalExtension());
        $this->importProcessor->reset();

        if (in_array($extension, self::CSV_EXTENSIONS, true)) {
            return (new LinksImportFromCsv($this->importProcessor))->import($file->getRealPath());
        }

        $import = new LinksImport($this->importProcessor);
        Excel::import($import, $file, null, $this->spreadsheetReaderType($extension));

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
