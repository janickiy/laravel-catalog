<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LinkCustomField extends Model
{
    public const TYPES = ['text', 'textarea', 'url', 'number', 'select', 'checkbox'];

    protected $fillable = [
        'label', 'type', 'help_text', 'options', 'is_required', 'is_active', 'is_public', 'sort_order',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
