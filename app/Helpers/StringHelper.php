<?php

namespace App\Helpers;


class StringHelper
{

    /**
     * @param $status
     * @return string
     */
    public static function linkStatus($status)
    {
        switch ($status) {
            case 'new':
                return 'ожидает проверку';
                break;

            case 'publish':
                return 'опубликован';
                break;

            case 'hide':
                return 'скрыта';
                break;

            case 'block':
                return 'в черном списке';
                break;

            default:
                return $status;
        }
    }

    /**
     * @param $str
     * @return string
     */
    public static function ucfirst_utf8($str)
    {
        return mb_substr(mb_strtoupper($str, 'utf-8'), 0, 1, 'utf-8') . mb_substr($str, 1, mb_strlen($str) - 1, 'utf-8');
    }


    /**
     * @param $originalDate
     * @return false|string
     */
    public static function date_format_ru($originalDate)
    {
        return date("d.m.Y", strtotime($originalDate));
    }

    /**
     * @param string $datestr
     * @param bool $short
     * @return string
     */
    public static function mysql_russian_date($datestr = '', $short = false)
    {
        if ($datestr == '') return '';

        list($day) = explode(' ', $datestr);

        switch ($day) {
            case date('Y-m-d'):
                $result = 'Сегодня';
                break;

            case date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"))):
                $result = 'Вчера';
                break;

            default:
            {
                list($y, $m, $d) = explode('-', $day);
                $month_str = $short == true ? ['янв', 'фев', 'март', 'апр', 'мая', 'июн', 'июл', 'авг', 'сен', 'окт', 'ноябр', 'дек'] : ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];
                $month_int = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
                $m = str_replace($month_int, $month_str, $m);
                $result = $d . ' ' . $m . ' ' . $y;
            }
        }

        return $result;
    }

    /**
     * @param string $datestr
     * @param bool $short
     * @return string
     */
    public static function mysql_english_date($datestr = '', $short = false)
    {
        if ($datestr == '') return '';

        list($day) = explode(' ', $datestr);

        switch ($day) {
            case date('Y-m-d'):
                $result = 'today';
                break;

            case date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"))):
                $result = 'yesterday';
                break;

            default:
            {
                list($y, $m, $d) = explode('-', $day);
                $month_str = $short == true ? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Augt', 'Sept', 'Oct', 'Nov', 'Dec'] : ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                $month_int = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
                $m = str_replace($month_int, $month_str, $m);
                $result = $d . ' ' . $m . ' ' . $y;
            }
        }

        return $result;
    }

    /**
     * @param string $datestr
     * @param bool $short
     * @return string
     */
    public static function mysql_russian_datetime($datestr = '', $short = false)
    {
        if ($datestr == '') return '';

        $dt_elements = explode(' ', $datestr);

        $date_elements = explode('-', $dt_elements[0]);
        $time_elements = explode(':', $dt_elements[1]);

        $result1 = mktime($time_elements[0], $time_elements[1], $time_elements[2], $date_elements[1], $date_elements[2], $date_elements[0]);
        $monthes = $short == true ? [' ', 'янв', 'фев', 'март', 'апр', 'мая', 'июн', 'июл', 'авг', 'сент', 'окт', 'ноябр', 'дек'] : [' ', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];
        $days = $short == true ? [' ', 'пон', 'вт', 'ср', 'чет', 'пят', 'суб', 'воск'] : [' ', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота', 'воскресенье'];
        $day = date("j", $result1);
        $month = $monthes[date("n", $result1)];
        $year = date("Y", $result1);
        $hour = date("G", $result1);
        $minute = date("i", $result1);
        $dayofweek = $days[date("N", $result1)];
        $result = $day . ' ' . $month . ' ' . $year;

        return $result;
    }

    /**
     * @param string $datestr
     * @param bool $short
     * @return string
     */
    public static function mysql_english_datetime($datestr = '', $short = false)
    {
        if ($datestr == '') return '';

        $dt_elements = explode(' ', $datestr);

        $date_elements = explode('-', $dt_elements[0]);
        $time_elements = explode(':', $dt_elements[1]);

        $result1 = mktime($time_elements[0], $time_elements[1], $time_elements[2], $date_elements[1], $date_elements[2], $date_elements[0]);
        $monthes = $short == true ? [' ', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'] : [' ', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $days = $short == true ? [' ', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'] : [' ', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $day = date("j", $result1);
        $month = $monthes[date("n", $result1)];
        $year = date("Y", $result1);
        $hour = date("G", $result1);
        $minute = date("i", $result1);
        $dayofweek = $days[date("N", $result1)];
        $result = $day . ' ' . $month . ' ' . $year;

        return $result;
    }

    /**
     * @param $str
     * @param int $chars
     * @return string
     */
    public static function shortText($str, $chars = 500)
    {
        $pos = strpos(substr($str, $chars), " ");
        $srttmpend = strlen($str) > $chars ? '...' : '';

        return substr($str, 0, $chars + $pos) . (isset($srttmpend) ? $srttmpend : '');
    }

    /**
     * @param $string
     * @return false|mixed|string
     */
    public static function str_to_utf8($string)
    {
        if (mb_detect_encoding($string, 'UTF-8', true) === false) {
            $string = @iconv("windows-1251", "utf-8", $string);
        }
        return $string;
    }

    /**
     * @param $size
     * @param int $maxDecimals
     * @param string $mbSuffix
     * @return string
     */
    public static function formatSizeInMb($size, $maxDecimals = 3, $mbSuffix = "MB")
    {
        $mbSize = round($size / 1024 / 1024, $maxDecimals);

        return preg_replace("/\\.?0+$/", "", $mbSize) . $mbSuffix;
    }

    /**
     * @return mixed
     */
    public static function detectMaxUploadFileSize()
    {
        /**
         * Converts shorthands like "2M" or "512K" to bytes
         *
         * @param int $size
         * @return int|float
         * @throws Exception
         */
        $normalize = function ($size) {
            if (preg_match('/^(-?[\d\.]+)(|[KMG])$/i', $size, $match)) {
                $pos = array_search($match[2], ["", "K", "M", "G"]);
                $size = $match[1] * pow(1024, $pos);
            } else {
                return false;
            }
            return $size;
        };
        $limits = [];
        $limits[] = $normalize(ini_get('upload_max_filesize'));
        if (($max_post = $normalize(ini_get('post_max_size'))) != 0) {
            $limits[] = $max_post;
        }
        if (($memory_limit = $normalize(ini_get('memory_limit'))) != -1) {
            $limits[] = $memory_limit;
        }
        $maxFileSize = min($limits);

        return $maxFileSize;
    }

    /**
     * @return string
     */
    public static function maxUploadFileSize()
    {
        $maxUploadFileSize = self::detectMaxUploadFileSize();

        if (!$maxUploadFileSize or $maxUploadFileSize == 0) {
            $maxUploadFileSize = 2097152;
        }

        return self::formatSizeInMb($maxUploadFileSize);
    }

    /**
     * @param $str
     * @return string
     */
    public static function removeHtmlTags($str)
    {
        $str = strip_tags($str);
        $str = html_entity_decode($str);

        return $str;
    }

}













