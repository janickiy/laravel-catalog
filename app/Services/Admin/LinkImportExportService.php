<?php

namespace App\Services\Admin;

use App\Helpers\StringHelper;
use App\Imports\LinksImport;
use App\Imports\LinksImportFromCsv;
use App\Repositories\LinksRepository;
use App\Services\DomainAvailabilityService;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use ZipArchive;

class LinkImportExportService
{
    public function __construct(
        private readonly LinksRepository $links,
        private readonly DomainAvailabilityService $domainAvailability,
    ) {
    }

    public function import(UploadedFile $file): int
    {
        set_time_limit(0);

        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension === 'csv' || $extension === 'txt') {
            return LinksImportFromCsv::import($file->getRealPath(), $this->domainAvailability);
        }

        Excel::import(new LinksImport($this->domainAvailability), $file);

        return 0;
    }

    public function export(?int $catalogId, string $type, string $compress)
    {
        $links = $this->links->publishedForExport($catalogId);
        $filename = 'emailexport' . date('d_m_Y') . ($type === 'excel' ? '.xlsx' : '.txt');
        $contents = $type === 'excel'
            ? $this->excelContents($links)
            : $this->textContents($links);

        if ($compress === 'zip') {
            return $this->zipResponse($filename, $contents);
        }

        return response($contents, 200, [
            'Content-Disposition' => 'attachment; filename=' . $filename,
            'Cache-Control' => 'max-age=0',
            'Content-Type' => StringHelper::getMimeType(pathinfo($filename, PATHINFO_EXTENSION)),
        ]);
    }

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
            ]) . "\r\n";
        }

        return $contents;
    }

    private function excelContents(iterable $links): string
    {
        $spreadsheet = new Spreadsheet();
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
            ->setCellValue('A1', 'Город')
            ->setCellValue('B1', 'Название')
            ->setCellValue('C1', 'Категория')
            ->setCellValue('D1', 'URL')
            ->setCellValue('E1', 'Телефон')
            ->setCellValue('F1', 'Email');

        $i = 1;

        foreach ($links as $row) {
            $i++;
            $sheet
                ->setCellValue('A' . $i, $row->city ?? '')
                ->setCellValue('B' . $i, $row->name)
                ->setCellValue('C' . $i, $row->catalog->name ?? '')
                ->setCellValue('D' . $i, $row->url)
                ->setCellValue('E' . $i, $row->phone)
                ->setCellValue('F' . $i, $row->email ?? '');
        }

        foreach (['A' => 30, 'B' => 60, 'C' => 30, 'D' => 30, 'E' => 30, 'F' => 30] as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_start();
        $writer->save('php://output');

        return (string) ob_get_clean();
    }

    private function zipResponse(string $filename, string $contents)
    {
        $zipPath = tempnam(sys_get_temp_dir(), 'links_export_') . '.zip';
        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFromString($filename, $contents);
        $zip->close();

        return response()->download($zipPath, 'emailexport_' . date('d_m_Y') . '.zip')->deleteFileAfterSend(true);
    }
}
