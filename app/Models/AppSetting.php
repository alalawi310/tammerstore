<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use HasFactory;

    protected $fillable = [
    	'theme_id',
    	'page_name',
    	'store_id',
    	'theme_json',
    	'theme_json_api'
    ];
}
