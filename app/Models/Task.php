<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'description', 'assigned_to', 'completed_by', 'notes', 'created_by', 'project_id', 'status', 'due_at', 'completed_at'
    ];

    protected $dates = ['created_at', 'updated_at', 'completed_at', 'due_at', 'deleted_at'];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function projects()
    {
        return $this->belongsTo(Project::class);
    }
}
