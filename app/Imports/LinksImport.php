<?php

namespace App\Imports;

use App\Models\{Links,Catalog};
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use App\Helpers\StringHelper;

class LinksImport implements ToModel, WithBatchInserts
{

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $n = 0;
        /*
        $name = trim($row['nazvanie_kompanii']);
        $url = trim($row['www']);
        $email = trim($row['e_mail']);
        $telefon = trim($row['telefon']);
        */

        $city = trim($row[0]);
        $name = trim($row[1]);
        $category = trim($row[3]);
        $url = trim($row[5]);
        $phone = trim($row[7]);

        if ($url && Links::isDomainAvailible($url, 5)) {

            $url_link = $url;

            if (substr($url_link, 0, 7) == "http://") $url_link = str_replace('http://', '', $url_link);
            if (substr($url_link, 0, 8) == "https://") $url_link = str_replace('https://', '', $url_link);
            if (strpos($url_link, '/') > 0) list($url_link) = explode('/', $url_link);

            if (Links::where('url', '=', $url_link)->count() == 0) {
                $tags_row = @get_meta_tags($url);

                $tags = [];

                if ($tags_row) {
                    foreach ($tags_row as $mkey => $mval) {
                        $tags[$mkey] = StringHelper::str_to_utf8($mval);
                    }
                }

                $keywords = $tags['keywords'] ?? '';
                $description = $tags['keywords'] ??  '';

                if ($description) {
                    $n++;
                    $arr = explode('/', $category);
                    $n_arr = [];

                    $parent_id = 0;

                    for ($i = 0; $i < count($arr); $i++) {
                        $parent_id = $this->importCategory(trim($arr[$i]), $parent_id);
                        $n_arr[$i] = ['name' => $arr[$i], 'id' => $parent_id];
                    }

                    $category = array_pop($n_arr);

                    Links::create([
                            'name' => $name,
                            'url' => $url_link,
                            'phone' => $phone,
                            'city' => $city,
                            'description' => $description,
                            'keywords' => $keywords,
                            'full_description' => $description,
                            'catalog_id' => $category['id'] ?? 3,
                            'status' => 1
                        ]
                    );

                    return $n;
                }
            }
        }
    }

    public function chunkSize(): int
    {
        return 1000;
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

}
