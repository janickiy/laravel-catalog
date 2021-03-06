<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function children(){
        return $this->hasMany(Catalog::class, 'parent_id', 'id');
    }
}
