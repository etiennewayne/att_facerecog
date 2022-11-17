<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $primaryKey = 'employee_id';

    protected $fillable = ['lname', 'fname', 'mname', 'suffix', 'sex', 'contact_no'];

    public function descriptors(){
        return $this->hasMany(Descriptor::class, 'employee_id', 'employee_id');
    }
}
