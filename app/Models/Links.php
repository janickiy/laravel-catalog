<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Links extends Model
{
    const PER_PAGE = 1000;

    protected $table = 'links';

    protected $fillable = [
        'name',
        'url',
        'city',
        'email',
        'phone',
        'description',
        'keywords',
        'full_description',
        'image',
        'catalog_id',
        'status',
        'views',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function catalog()
    {
        return $this->hasOne(Catalog::class,'id','catalog_id');
    }

    /**
     * @return string|void
     */
    public function getStatusAttribute()
    {
        switch ($this->attributes['status']) {
            case 0:
                return 'new';
            case 1:
                return 'publish';
            case 2:
                return 'black';
        }
    }

    /**
     * @param $status
     * @return string
     */
    public static function linkStatus($status)
    {
        switch ($status) {
            case 'new':
                return 'ожидает проверку';
            case 'publish':
                return 'опубликован';
            case 'block':
                return 'в черном списке';
            default:
                return $status;
        }
    }

    /**
     * @param $domain
     * @param int $timeout
     * @return bool
     */
    public static function isDomainAvailible($domain, $timeout=5){

        if (!filter_var($domain, FILTER_VALIDATE_URL)) {
            return false;
        }

        $curlInit = curl_init($domain);
        curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,$timeout);
        curl_setopt($curlInit,CURLOPT_TIMEOUT       ,$timeout);
        curl_setopt($curlInit,CURLOPT_HEADER,false);
        curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);

        $response = curl_exec($curlInit);

        curl_close($curlInit);

        return (bool)($response);
    }
}
