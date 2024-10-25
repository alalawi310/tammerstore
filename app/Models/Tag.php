<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $fillable =[
        'name',
        'slug',
        'theme_id',
        'store_id',
        'created_by'
    ];


    public static function slugs($data)
    {
        $slug = '';
        // Remove special characters
        $slug = preg_replace('/[^a-zA-Z0-9\s-]/', '', $data);
        // Replace multiple spaces with a single hyphen
        $slug = preg_replace('/\s+/', '-', trim($slug));
        // Convert to lowercase
        $slug = strtolower($slug);
        $table = with(new Tag)->getTable();
        $allSlugs = self::getRelatedSlugs($table, $slug ,$id = 0);

        if (!$allSlugs->contains('slug', $slug)) {
            return $slug;
        }
        for ($i = 1; $i <= 100; $i++) {
            $newSlug = $slug . '-' . $i;
            if (!$allSlugs->contains('slug', $newSlug)) {
                return $newSlug;

            }
        }
    }

    protected static function getRelatedSlugs($table, $slug, $id = 0)
    {
        return \DB::table($table)->select()->where('slug', 'like', $slug . '%')->where('id', '<>', $id)->get();
    }

}
