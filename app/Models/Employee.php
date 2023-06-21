<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Employee extends Model
{
    use HasFactory;
    public $table = "employee";
    protected $fillable = ['id','name','department_id'];

    public function departments()
    {
        return $this->belongsTo(Department::class);
    }
}
