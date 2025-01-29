<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AreaOption extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function sites()
    {
        return $this->hasMany(Site::class, 'area_id');
    }
}
