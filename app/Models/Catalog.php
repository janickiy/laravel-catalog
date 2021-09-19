<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use URL;

class Catalog extends Model
{
    const PER_PAGE = 1000;

	protected $table = 'catalog';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
        'keywords',
        'image',
        'image_mime',
        'parent_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(){
        return $this->hasMany($this, 'parent_id', 'id');
    }

    /**
     * @param $option
     * @param $parent_id
     * @param int $lvl
     * @return mixed
     */
    public static function showTree(&$option, $parent_id, &$lvl = 0)
    {
        $lvl++;
        $rows = self::where('parent_id', $parent_id)->orderBy('name')->get();

        foreach ($rows as $row) {
            $indent = '';
            for ($i = 1; $i < $lvl; $i++) $indent .= '-';

            $option[$row->id] = $indent . " " . $row->name;
            self::showTree($option, $row->id, $lvl);
            $lvl--;
        }

        return $option;
    }

    /**
     * @param $id
     * @return string
     */
    public static function ShowSubCat($id)
    {
        $catalogs = self::selectRaw('catalog.id, catalog.name, COUNT(links.status) AS number_links')
            ->leftJoin('links', 'links.catalog_id', '=', 'catalog.id')
            ->groupBy('catalog.id')
            ->groupBy('catalog.name')
            ->orderBy('catalog.name')
            ->where('catalog.parent_id', $id)
            ->get();

        $sub_category_list = [];

        if ($catalogs) {
            foreach ($catalogs as $catalog) {
                $sub_category_list[] = '<a href="' . url('/' . $catalog->id) . '">' . $catalog->name . '</a> <span>(' . $catalog->number_links . ')</span>';
            }
        }

        return implode(', ', $sub_category_list);
    }

    /**
     * @param $topbar
     * @param $parent_id
     * @return array
     */
    public static function topbarMenu(&$topbar, $parent_id)
    {
        if (is_numeric($parent_id)) {
            $result = self::where('id', $parent_id);

            if ($result->count() > 0) {
                $catalog = $result->first();
                $topbar[] = [$catalog->id, $catalog->name];

                self::topbarMenu($topbar, $catalog->parent_id);
            }
        }

        sort($topbar);

        return $topbar;
    }

    /**
     * @param $cats
     * @param $parent_id
     * @param bool $only_parent
     * @return null|string
     */
    public static function build_tree($cats, $parent_id, $only_parent = false)
    {
        if (is_array($cats) && isset($cats[$parent_id])) {
            $tree = '<ul>';
            if ($only_parent == false) {
                foreach ($cats[$parent_id] as $cat) {
                    $tree .= '<li>' . $cat['name'] . ' <a title="Добавить подкатегорию" href="' . URL::route('cp.catalog.create', ['parent_id' => $cat['id']]) . '"> <span class="fa fa-plus"></span> </a> <a title="Редактировать" href="' . URL::route('cp.catalog.edit', ['id' => $cat['id']]) . '"> <span class="fa fa-pencil"></span> </a> <a title="Удалить" href="' . URL::route('cp.catalog.delete', $cat['id']) . '"> <span class="fa fa-trash-o"></span> </a>';
                    $tree .= self::build_tree($cats, $cat['id']);
                    $tree .= '</li>';
                }
            } elseif (is_numeric($only_parent)) {
                $cat = $cats[$parent_id][$only_parent];
                $tree .= '<li>' . $cat['name'] . ' #' . $cat['id'];
                $tree .= self::build_tree($cats, $cat['id']);
                $tree .= '</li>';
            }
            $tree .= '</ul>';
        } else return null;
        return $tree;
    }
}
