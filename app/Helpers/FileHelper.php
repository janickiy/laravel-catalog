<?php

namespace App\Helpers;

use Gumlet\ImageResize;

class FileHelper
{

    /**
     * @param $url
     * @return array
     */
    public static function getScreenShot($url): array
    {
        if (substr($url, 0, 7) == "http://" or substr($url, 0, 8) == "https://")
            $url_with_prefix = $url;
        else
            $url_with_prefix = 'http://' . $url;

        $api = env('GOOGLE_API_KEY');
        $adress = "https://pagespeedonline.googleapis.com/pagespeedonline/v5/runPagespeed?url=$url_with_prefix&category=CATEGORY_UNSPECIFIED&strategy=DESKTOP&key=$api";

        $curl_init = curl_init($adress);
        curl_setopt($curl_init, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl_init);
        curl_close($curl_init);

        if (!$response) return ['result' => false];

        $googledata = json_decode($response, true);

        if (!isset($googledata["lighthouseResult"]["audits"]["full-page-screenshot"]["details"]["screenshot"]["data"])) return ['result' => false];

        $pic = $googledata["lighthouseResult"]["audits"]["full-page-screenshot"]["details"]["screenshot"]["data"];
        $pic = str_replace('data:image/jpeg;base64,', '', $pic);

        $filename = md5(uniqid()) . '.jpg';

        $image = ImageResize::createFromString(base64_decode($pic));
        $image->crop(1200, 800, true, ImageResize::CROPTOP);

        if ($image->save(public_path('/uploads/url') . '/' . $filename) === false) return ['result' => false];

        return ['name' => $filename];

    }


    /**
     * @param $url
     * @return bool
     */
    public static function url_exists($url)
    {
        if (substr($url, 0, 7) == "http://" or substr($url, 0, 8) == "https://")
            $url_with_prefix = $url;
        else
            $url_with_prefix = 'http://' . $url;

        return curl_init($url_with_prefix) !== false;
    }

}













