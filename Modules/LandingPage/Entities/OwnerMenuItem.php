<?php

namespace Modules\LandingPage\Entities;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Utility;
use Illuminate\Support\Facades\Storage;

class OwnerMenuItem extends Model
{
    use HasFactory;


    protected $fillable = [
        'menu_id',
        'title',
        'name',
        'slug',
        'type',
        'icon',
        'target'
    ];

}
