<?php

namespace Modules\LandingPage\Entities;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Utility;
use Illuminate\Support\Facades\Storage;

class OwnerMenu extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'content',
        'created_by'
    ];

}
