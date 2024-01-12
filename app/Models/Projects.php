<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class Projects extends Model implements HasMedia {
    use InteractsWithMedia;

    public static $rules = [
    ];

    public $table = 'dcq_projects';

    public $fillable = [
        'name',
        'code',
        'labo_agency',
        'count',
        'budget',
        'customer_id',
        'description',
        'status',
        'due_date',
    ];

    public function projectCustomer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
