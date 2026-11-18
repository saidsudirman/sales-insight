<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ukuran extends Model
{
    protected $table = 'ukurans';

    protected $fillable = [
        'nama_ukuran',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    public function varianProduks(): HasMany
    {
        return $this->hasMany(VarianProduk::class, 'ukuran_id');
    }
}
