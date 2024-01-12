<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;


class Revenue extends Model {
    public static $rules = [
    ];

    public $table = 'revenue';

    public $fillable = [
        'name',
        'total',
        'type',
        'note',
        'entry_date'
    ];
}
