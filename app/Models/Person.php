<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        'stack' => 'array',
        'date_of_birth' => 'date'
    ];

    protected $fillable = [
        'name',
        'nickname',
        'date_of_birth',
        'stack',
        'uuid',
    ];

    protected static function booted(): void
    {
        static::creating(function (Person $person) {
            if (! empty($person['uuid'])) {
                return;
            }

            $person['uuid'] = uuid_create();
        });
    }
}
