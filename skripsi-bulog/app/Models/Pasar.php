<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pasar extends Model
{
    use HasFactory;

    protected $table = 'pasars';

    protected $fillable = [
        'kantor_cabang',
        'kabupaten',
        'nama_pasar',
        'latitude',
        'longitude',
        'target',
    ];

    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
        'target'    => 'integer',
    ];

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }

    public static function kancabOptions(): array
    {
        return [
            'KANCAB BOGOR',
            'KANCAB CIANJUR',
            'KANCAB BANDUNG',
            'KANCAB CIAMIS',
            'KANCAB CIREBON',
            'KANCAB INDRAMAYU',
            'KANCAB SUBANG',
            'KANCAB KARAWANG',
        ];
    }
}