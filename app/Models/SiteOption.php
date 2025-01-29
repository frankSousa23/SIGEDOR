<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SiteOption extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function sites()
    {
        return $this->hasMany(Site::class);
    }
}
