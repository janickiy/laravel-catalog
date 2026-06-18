<?php

namespace App\Models;

use App\Http\Traits\StaticTableName;
use App\Enums\LinkStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Links extends Model
{
    use StaticTableName;

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
     * @return BelongsTo
     */
    public function catalog(): BelongsTo
    {
        return $this->belongsTo(Catalog::class, 'catalog_id', 'id');
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function getStatusAttribute(mixed $value): string
    {
        return LinkStatus::codeFor($value);
    }
}
