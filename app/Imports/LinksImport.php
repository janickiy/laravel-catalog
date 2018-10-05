<?php

namespace App\Imports;

use App\Models\Links;
use App\Models\Catalog;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Carbon\Carbon;

class LinksImport implements ToModel, WithHeadingRow, WithBatchInserts
{

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $name = trim($row['nazvanie_kompanii']);
        $url = trim($row['www']);
        $email = trim($row['e_mail']);
        $telefon = trim($row['telefon']);

        if ($url && isDomainAvailible($url, 5)) {

            $url_link =  $url;

            if (substr( $url_link, 0, 7) == "http://") $url_link = str_replace('http://', '', $url_link);
            if (substr( $url_link, 0, 8) == "https://") $url_link = str_replace('https://', '', $url_link);
            if (strpos( $url_link, '/') > 0) list($url_link) = explode('/', $url_link);

            if (Links::where('url', 'like',  $url_link)->count() == 0) {
            $tags_row = @get_meta_tags($url);

            $tags = [];

            if ($tags_row) {
                foreach($tags_row as $mkey => $mval) {
                    $tags[$mkey] = str_to_utf8($mval);
                }
            }

            $keywords = isset($tags['keywords']) ? $tags['keywords'] : '';
            $description = isset($tags['description']) ? $tags['description'] : '';
            $title = isset($tags['title']) ? $tags['title'] : '';;

            if ($description) {
                $arr = explode('/', trim($row['tematika']));
                $n_arr = [];

                $parent_id = 0;

                for ($i = 0; $i < count($arr); $i++) {
                    $parent_id = $this->importCategory(trim($arr[$i]), $parent_id);
                    $n_arr[$i] = ['name' => $arr[$i], 'id' => $parent_id];
                }

                $category = array_pop($n_arr);

                    return Links::create([
                            'name' => $name,
                            'url' => $url_link,
                            'email' => $email,
                            'description' => $title,
                            'keywords' => $keywords,
                            'full_description' => $description,
                            'catalog_id' => isset($category['id']) ? $category['id'] : 3,
                            'time_check' => Carbon::now(),
                            'status' => 1,
                            'token' => md5($url . time())]
                    );
                }
            }
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function headingRow(): int
    {
        return 6;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    /**
     * @param $name
     * @param int $parent_id
     * @return mixed
     */
    private function importCategory($name, $parent_id = 0)
    {
        if (!empty($name) && is_numeric($parent_id)) {
            $catalog = Catalog::where('name', 'like', $name)->where('parent_id', $parent_id);

            if ($catalog->count() > 0) {
                return $catalog->first()->id;
            } else {
                if ($name) {
                    Catalog::create(['name' => $name, 'parent_id' => $parent_id]);
                }
            }
        }
    }

    /**
     * @param $domain
     * @return bool
     */


}
