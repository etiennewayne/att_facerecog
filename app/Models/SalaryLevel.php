<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryLevel extends Model
{
    use HasFactory;

    protected $table = 'salary_levels';
    protected $primaryKey = 'salary_level_id';

    protected $fillable = ['salary_level', 'salary'];

}
