<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;


class Tasks extends Model {
    public static $rules = [
    ];

    public $table = 'tasks';

    public $fillable = [
        'name',
        'code',
        'description',
        'assign_to',
        'created_by',
        'approved_by',
        'due_date',
        'task_value',
        'parent_id',
        'project_id',
        'priority',
    ];

    const STATUS = array(
        'to do' => 'To do',
        "in_progress" => 'In progress',
        "pending" => 'Pending',
        "complete" => 'Complete',
        "awaiting confirmation" => 'Awaiting confirmation'
    );

    const PRIORITY = array(
        'hight' => 'Hight',
        "medium" => 'Medium',
        "low" => 'Low'
    );

    public function tasksAssignTo() {
        return $this->belongsTo(User::class, 'assign_to');
    }

    public function tasksCreatedBy() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tasksApprovedBy() {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
