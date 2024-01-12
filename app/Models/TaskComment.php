<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    // số comments hiện lần đầu
    const DEFAULT_COMMENTS = 0;
    const SORT_COMMENTS = 'desc';
    const START_COMMENTS = 3;
    const LIMIT_COMMENTS = 3;

    protected $fillable = ['task_id', 'create_by', 'content', 'reply_id'];

    public function task()
    {
        return $this->belongsTo(Tasks::class, 'task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'create_by');
    }

    public function reply()
    {
        return $this->belongsTo(TaskComment::class, 'reply_id');
    }
}
