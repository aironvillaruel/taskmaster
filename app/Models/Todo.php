<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    //
    protected $table = 'tbl_todo';
    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'user_id',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
