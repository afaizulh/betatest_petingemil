<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategorySize extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'list_size'
    ];

    public function productSize(): BelongsTo {
        return $this->belongsTo(Product::class, 'id_category_size', 'id');
    }
}
