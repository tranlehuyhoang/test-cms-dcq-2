<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;


class Roles extends Model {
    public static $rules = [
    ];

    public $table = 'roles';

    public $fillable = [
        'name',
        'code',
    ];
}
