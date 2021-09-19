<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\{Catalog};
use App\Http\Controllers\Controller;
use App\Helpers\StringHelper;
use Validator;
use Image;
use URL;

class CatalogController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $catalogs = Catalog::get();
        $cats = [];

        if ($catalogs) {

            $catalog_arr = $catalogs->toArray();

            foreach ($catalog_arr as $catalog) {
                $cats_ID[$catalog['id']][] = $catalog;
                $cats[$catalog['parent_id']][$catalog['id']] = $catalog;
            }
        }

        return view('cp.catalog.index', compact('cats'))->with('title', 'Категории');
    }

    /**
     * @param int $parent_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($parent_id = 0)
    {
        $options[0] = 'Выберите';
        $options = Catalog::ShowTree($options, 0);

        $maxUploadFileSize = StringHelper::maxUploadFileSize();

        return view('cp.catalog.create_edit', compact('parent_id', 'options', 'maxUploadFileSize'))->with('title', 'Добавление категории');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'image' => 'image|mimes:jpeg,jpg,gif,png|max:2048|nullable',
            'parent_id' => 'integer'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())  return back()->withErrors($validator)->withInput();

        $pic = $request->file('image');

        if (isset($pic)) {
            $destinationPath = public_path('/uploads/catalog/');
            $filename = time() . '.' . $pic->getClientOriginalExtension();
            $img = Image::make($request->file('image')->getRealPath());

            $img->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $filename);
        }

        Catalog::create(array_merge(array_merge($request->all()), ['image' => $filename ?? null]));

        return redirect(URL::route('cp.catalog.index'))->with('success', 'Информация успешно добавлена');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $row = Catalog::where('id', $id)->first();

        if (!$row) abort(404);

        $options[0] = 'Выберите';
        Catalog::ShowTree($options, 0);
        $parent_id = $row->parent_id;
        $maxUploadFileSize = StringHelper::maxUploadFileSize();

        unset($options[$id]);

        return view('cp.catalog.create_edit', compact('row', 'parent_id', 'options', 'maxUploadFileSize'))->with('title', 'Редактирование категории');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
            'image' => 'image|mimes:jpeg,jpg,gif,png|max:2048|nullable',
            'parent_id' => 'integer'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        $catalog = Catalog::find($request->id);

        if (!$catalog) abort(404);

        $catalog->name = $request->name;
        $catalog->description = $request->description;
        $catalog->keywords = $request->keywords;
        $catalog->parent_id = $request->parent_id;

        $pic = $request->file('image');

        if (isset($pic)) {

            $pic1 = $request->pic;

            if ($pic1 != NULL) {
                $dir = public_path("/uploads/catalog/$pic1");
                if (file_exists($dir)) {
                    @unlink($dir);
                }
            }

            $destinationPath = public_path('/uploads/catalog/');
            $filename = time() . '.' . $pic->getClientOriginalExtension();

            $img = Image::make($request->file('image')->getRealPath());

            $img->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $filename);

            $catalog->image = $filename;
        }

        $catalog->save();

        return redirect(URL::route('cp.catalog.index'))->with('success', 'Данные обновлены');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $parent = Catalog::findOrFail($request->id);
        $array_of_ids = $this->getChildren($parent);
        array_push($array_of_ids, $request->id);

        $catalog = Catalog::whereIn('id', $array_of_ids)->get();

        if ($catalog) {
            $pics = $catalog->pluck('image');
            foreach ($pics as $pic) {
                $image = public_path('/uploads/catalog/' . $pic);

                if (file_exists($image)) @unlink($image);
            }
        }

        Catalog::destroy($array_of_ids);

        return redirect(URL::route('cp.catalog.index'))->with('success', 'Данные удалены');
    }

    /**
     * @param $category
     * @return array
     */
    private function getChildren($category)
    {
        $ids = [];
        foreach ($category->children as $row) {
            $ids[] = $row->id;
            $ids = array_merge($ids, $this->getChildren($row));
        }
        return $ids;
    }

}
