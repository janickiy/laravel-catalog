<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\{Links, Catalog};
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\LinksImport;
use App\Imports\LinksImportFromCsv;
use App\Helpers\StringHelper;
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
        $options = [];
        $options = Catalog::ShowTree($options, 0);

        $maxUploadFileSize = StringHelper::maxUploadFileSize();

        return view('cp.links.import', compact('options', 'maxUploadFileSize'))->with('title', 'Импорт');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        set_time_limit(0);

        $rules = [
            'file' => 'required',
            'catalog_id' => 'required|integer'
        ];

        $messages = [
            'required' => 'Это поле обязательно для заполнения!',
            'mimes' => 'Разрешено загружать файлы: csv,xlsx,xls!'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        $extension = strtolower($request->file('file')->getClientOriginalExtension());
        $n = 0;

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
        return view('cp.links.export')->with('title', 'Экспорт');
    }

    public function exportLink()
    {

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
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

}
