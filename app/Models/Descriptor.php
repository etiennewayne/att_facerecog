<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Descriptor extends Model
{
    use HasFactory;


    protected $table = 'descriptors';
    protected $primaryKey = 'id';

    protected $fillable = ['name', 'descriptor'];


    protected function descriptor(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }

}
