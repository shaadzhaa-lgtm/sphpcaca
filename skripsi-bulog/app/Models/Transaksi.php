<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    protected $fillable = ['pasar_id', 'tanggal', 'jumlah_kg', 'harga_jual', 'keterangan'];

    protected $casts = [
        'tanggal'    => 'date',
        'jumlah_kg'  => 'float',
        'harga_jual' => 'integer',
    ];

    public function pasar(): BelongsTo
    {
        return $this->belongsTo(Pasar::class);
    }

    public function getOmzetAttribute(): float
    {
        return $this->jumlah_kg * $this->harga_jual;
    }
}