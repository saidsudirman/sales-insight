<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warna extends Model
{
    protected $table = 'warnas';

    protected $fillable = [
        'nama_warna',
        'kode_warna',
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
        return $this->hasMany(VarianProduk::class, 'warna_id');
    }
}
