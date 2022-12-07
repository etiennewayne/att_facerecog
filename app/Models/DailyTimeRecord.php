<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyTimeRecord extends Model
{
    use HasFactory;


    protected $table = 'daily_time_records';
    protected $primaryKey = 'id';

    protected $fillable = ['time_status',
        'user_id', 
        // 'time_record', 
        // 'date_record', 
        'dt_record'
    ];


 public function user(){
     return $this->belongsTo(User::class, 'user_id', 'user_id');
 }


}
