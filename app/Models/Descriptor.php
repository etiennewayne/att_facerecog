<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Descriptor extends Model
{
    use HasFactory;


    protected $table = 'descriptors';
    protected $primaryKey = 'descriptor_id';

    protected $fillable = ['employee_id', 'descriptor'];


    protected $casts = [
        'descriptor' => 'json',
    ];


    public function setOptionsAttribute($descriptor)
    {
        $this->attributes['descriptor'] = json_encode($descriptor);
    }


}
