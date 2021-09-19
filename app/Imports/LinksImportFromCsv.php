<?php

namespace App\Imports;

use App\Models\{Links,Catalog};
use League\Csv\Reader;

class LinksImportFromCsv
{

   public static function import($path)
   {
       $n = 0;
       $csv = Reader::createFromPath($path, 'r');
       $csv->setDelimiter(';');

       foreach ($csv as $index => $row) {

           $city = isset($row[0]) ? trim(str_to_utf8($row[0])) : '';
           $name = isset($row[1]) ?  trim(str_to_utf8($row[1])) : '';
           $category = isset($row[3]) ? trim(str_to_utf8($row[3])) : '';
           $url = isset($row[5]) ? trim($row[5]) : '';
           $phone = isset($row[7]) ? trim($row[7]) : '';

           if ($url && isDomainAvailible($url, 5)) {

               $url_link =  $url;

               if (substr( $url_link, 0, 7) == "http://") $url_link = str_replace('http://', '', $url_link);
               if (substr( $url_link, 0, 8) == "https://") $url_link = str_replace('https://', '', $url_link);
               if (strpos( $url_link, '/') > 0) list($url_link) = explode('/', $url_link);

               if (Links::where('url', '=',  $url_link)->count() == 0) {
                   $tags_row = @get_meta_tags($url);

                   $tags = [];

                   if ($tags_row) {
                       foreach($tags_row as $mkey => $mval) {
                           $tags[$mkey] = str_to_utf8($mval);
                       }
                   }

                   $keywords = $tags['keywords'] ?? '';
                   $description = $tags['description'] ?? '';

                   if ($description) {
                       $n++;
                       $arr = explode('/', $category);
                       $n_arr = [];
                       $parent_id = 0;

                       for ($i = 0; $i < count($arr); $i++) {
                           $parent_id = self::importCategory(trim($arr[$i]), $parent_id);
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
                   }
               }
           }
       }

       return $n;
   }

    /**
     * @param $name
     * @param int $parent_id
     * @return mixed
     */
    private static function importCategory($name, $parent_id = 0)
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
