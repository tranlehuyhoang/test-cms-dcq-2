<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;


class Customer extends Model {
    public static $rules = [
    ];

    public $table = 'customer';

    public $fillable = [
        'name',
        'code',
        'address',
        'phone',
    ];
}
