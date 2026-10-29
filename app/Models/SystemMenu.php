<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class SystemMenu extends Model
{
    protected $table = 'system_menus';

    protected $fillable = [
        'parent_id',
        'name',
        'icon',
        'route_name',
        'url',
        'sort_order',
        'is_visible',
        'permission_code',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function parent()
    {
        return $this->belongsTo(SystemMenu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(SystemMenu::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Scope or helper to transform flat collection into hierarchical tree
     */
    public static function toTree(): Collection
    {
        $items = static::where('is_visible', 1)->orderBy('sort_order')->get();
        return $items->whereNull('parent_id')->map(function ($parent) use ($items) {
            $parent->children_list = $items->where('parent_id', $parent->id)->values();
            return $parent;
        })->values();
    }
}
