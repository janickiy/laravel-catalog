<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\{Links,Catalog};
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\{LinksImport,LinksImportFromCsv};
use App\Helpers\StringHelper;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use URL;
use Validator;

class LinksController extends Controller
{

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $status_list = [];

        foreach (['new' => 0, 'publish' => 1, 'block' => 2] as $key => $value) {
            $status_list[$value] = Links::linkStatus($key);
        }

        return view('cp.links.index', compact('status_list'))->with('title', 'Ссылки');
    }

    public function create()
    {
        $options = [];
        $options = Catalog::ShowTree($options, 0);

        return view('cp.links.create_edit', compact('options'))->with('title', 'Добавление ссылки');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'url' => 'required|url|unique:links',
            'description' => 'required',
            'full_description' => 'required',
            'catalog_id' => 'required|integer'
        ];

        $messages = [
            'required' => 'Это поле обязательно для заполнения!',
            'url' => 'URL адрес введен неверно',
            'url.unique' => 'Сайт с таким URL уже есть в каталоге!'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        Links::create(array_merge($request->all(), ['status' => 1]));

        return redirect(URL::route('cp.links.index'))->with('success', 'Информация успешно добавлена');

    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $row = Links::where('id', $id)->first();

        if (!$row) abort(404);

        $options = [];
        $options = Catalog::ShowTree($options, 0);

        return view('cp.links.create_edit', compact('row', 'options'))->with('title', 'Редактирование ссылки');

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
            'url' => 'required|unique:links,url,' . $request->id,
            'description' => 'required',
            'full_description' => 'required',
            'catalog_id' => 'required|integer'
        ];

        $messages = [
            'required' => 'Это поле обязательно для заполнения!',
            'url' => 'URL адрес введен неверно',
            'url.unique' => 'Сайт с таким URL уже есть в каталоге!'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        $link = Links::find($request->id);

        if (!$link) abort(404);

        $link->name = $request->name;
        $link->url = $request->url;
        $link->description = $request->description;
        $link->keywords = $request->keywords;
        $link->full_description = $request->full_description;
        $link->htmlcode_banner = $request->htmlcode_banner;
        $link->catalog_id = $request->catalog_id;
        $link->save();

        return redirect(URL::route('cp.links.index'))->with('success', 'Данные обновлены');

    }

    /**
     * @param Request $request
     */
    public function destroy(Request $request)
    {
        $link = Links::find($request->id);

        if (!$link) abort(404);

        $link->delete();
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function importForm()
    {
        $maxUploadFileSize = StringHelper::maxUploadFileSize();

        return view('cp.links.import', compact('maxUploadFileSize'))->with('title', 'Импорт');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        set_time_limit(0);

        $rules = [
            'file' => 'required|mimes:csv,xlsx,xls,txt',
        ];

        $messages = [
            'required' => 'Это поле обязательно для заполнения!',
            'mimes' => 'Разрешено загружать файлы: csv,xlsx,xls,txt!'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        $extension = strtolower($request->file('file')->getClientOriginalExtension());

        if ($extension == 'csv' or $extension == 'txt') {
            $path = $request->file('file')->getRealPath();
            $n = LinksImportFromCsv::import($path);
        } else {
            $n = Excel::import(new LinksImport, $request->file('file'));
        }

        return redirect(URL::route('cp.links.import'))->with('success', 'Импорт заврешен. Импортировано ' . $n . ' ссылок');

    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function export()
    {
        $options = [];
        $options = Catalog::ShowTree($options, 0);

        return view('cp.links.export', compact('options'))->with('title', 'Экспорт');
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|void
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportLink(Request $request)
    {
        $linksList = $this->getLinksList($request->catalog_id);

        if ($request->export_type == 'text') {
            $ext = 'txt';
            $filename = 'emailexport' . date("d_m_Y") . '.txt';

            if ($linksList) {
                $contents = '';
                foreach ($linksList as $link) {
                    $city = $link->city ?? '';
                    $name = $link->name;
                    $category = $link->category;
                    $url = $link->url;
                    $phone = $link->phone ?? '';
                    $contents .= "" . $city . ";" . $name . ";" .  $category . ";" . $url . ";" . $phone . "\r\n";
                }
            }
        } elseif ($request->export_type == 'excel') {

            $ext = 'xlsx';
            $filename = 'emailexport' . date("d_m_Y") . '.xlsx';
            $oSpreadsheet_Out = new Spreadsheet();

            $oSpreadsheet_Out->getProperties()->setCreator('Alexander Yanitsky')
                ->setLastModifiedBy('PHP Newsletter')
                ->setTitle('Office 2007 XLSX Document')
                ->setSubject('Office 2007 XLSX Document')
                ->setDescription('Document for Office 2007 XLSX, generated using PHP classes.')
                ->setKeywords('office 2007 openxml php')
                ->setCategory('Email export file');


            // Add some data
            $oSpreadsheet_Out->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Город')
                ->setCellValue('B1', 'Название')
                ->setCellValue('C1', 'Категория')
                ->setCellValue('D1', 'URL')
                ->setCellValue('E1', 'Телефон');

            $i = 1;

            foreach ($linksList as $subscriber) {
                $i++;

                $oSpreadsheet_Out->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $subscriber->city)
                    ->setCellValue('B' . $i, $subscriber->name)
                    ->setCellValue('C' . $i, $subscriber->name)
                    ->setCellValue('D' . $i, $subscriber->name)
                    ->setCellValue('E' . $i, $subscriber->name);
            }

            $oSpreadsheet_Out->getActiveSheet()->getColumnDimension('A')->setWidth(30);
            $oSpreadsheet_Out->getActiveSheet()->getColumnDimension('B')->setWidth(30);
            $oSpreadsheet_Out->getActiveSheet()->getColumnDimension('C')->setWidth(30);
            $oSpreadsheet_Out->getActiveSheet()->getColumnDimension('D')->setWidth(30);
            $oSpreadsheet_Out->getActiveSheet()->getColumnDimension('E')->setWidth(30);

            $oWriter = IOFactory::createWriter($oSpreadsheet_Out, 'Xlsx');
            ob_start();
            $oWriter->save('php://output');
            $contents = ob_get_contents();
            ob_end_clean();
        }

        if ($request->compress == 'zip') {
            header('Content-type: application/zip');
            header('Content-Disposition: attachment; filename=emailexport_' . date("d_m_Y") . '.zip');

            $fout = fopen("php://output", "wb");

            if ($fout !== false) {
                fwrite($fout, "\x1F\x8B\x08\x08" . pack("V", '') . "\0\xFF", 10);

                $oname = str_replace("\0", "", $filename);
                fwrite($fout, $oname . "\0", 1 + strlen($oname));

                $fltr = stream_filter_append($fout, "zlib.deflate", STREAM_FILTER_WRITE, -1);
                $hctx = hash_init("crc32b");

                if (!ini_get("safe_mode")) set_time_limit(0);

                hash_update($hctx, $contents);
                $fsize = strlen($contents);

                fwrite($fout, $contents, $fsize);

                stream_filter_remove($fltr);

                $crc = hash_final($hctx, TRUE);

                fwrite($fout, $crc[3] . $crc[2] . $crc[1] . $crc[0], 4);
                fwrite($fout, pack("V", $fsize), 4);

                fclose($fout);

                exit();
            }

        } else {
            return response($contents, 200, [
                'Content-Disposition' => 'attachment; filename=' . $filename,
                'Cache-Control' => 'max-age=0',
                'Content-Type' => StringHelper::getMimeType($ext),
            ]);
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function statusLinks(Request $request)
    {
        if ($request->has('activate')) {
            $temp = [];

            foreach ($request->activate as $id) {
                if (is_numeric($id)) {
                    $temp[] = $id;
                }
            }

            Links::whereIN('id', $temp)->update(['status' => $request->action]);

        }

        return redirect(URL::route('cp.links.index'))->with('success', 'Данные обновлены');

    }

    /**
     * @param $catalog_id
     * @return mixed
     */
    private function getLinksList($catalog_id)
    {
        if ($catalog_id) {
            $links = Links::selectRaw('links.name, links.id, links.name, links.url, links.phone, links.city, catalog.name AS catalog')
                ->leftJoin('catalog', function ($join) {
                    $join->on('links.catalog_id', '=', 'catalog.id');
                })
                ->where('links.status', 1)
                ->where('links.catalog_id', $catalog_id)
                ->groupBy('catalog.name')
                ->groupBy('links.id')
                ->groupBy('links.name')
                ->groupBy('links.url')
                ->groupBy('links.phone')
                ->groupBy('links.city')
                ->get();
        } else {
            $links = Links::selectRaw('links.name, links.id, links.name, links.url, links.phone, links.city, catalog.name AS catalog')
            ->where('active', '=', 1)
                ->leftJoin('catalog', function ($join) {
                    $join->on('links.catalog_id', '=', 'catalog.id');
                })
                ->groupBy('catalog.name')
                ->groupBy('links.id')
                ->groupBy('links.name')
                ->groupBy('links.url')
                ->groupBy('links.phone')
                ->groupBy('links.city')
                ->get();
        }

        return $links;
    }

}
