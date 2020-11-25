<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Category extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable, CascadeSoftDeletes;

    protected $fillable = ['name', 'unit', 'parent_id', 'sort_order'];


    // Each category may have one parent
    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id', 'id');
    }

    // Each category may have multiple children
    public function children()
    {
        return $this->hasMany(static::class, 'parent_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class);
    }
}
