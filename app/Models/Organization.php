<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'avatar', 'name', 'address', 'phone', 'description'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    // Relationships
    public function members()
    {
        return $this->hasMany(Member::class, 'org_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}

