<?php

namespace App\Models;

use App\Http\Traits\StaticTableName;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use StaticTableName;

    protected $table = 'settings';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'value',
    ];

    /**
     * Normalizes the setting name before saving.
     */
    public function setNameAttribute(mixed $value): void
    {
        $this->attributes['name'] = str_replace(' ', '_', (string) $value);
    }
}
