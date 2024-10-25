<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image_url',
        'image_path',
        'icon_path',
        'trending',
        'status',
        'theme_id',
        'store_id'
    ];

    protected $hidden = [
        'image_url'
    ];

    protected $appends = ["demo_field", "category_item","total_product", "maincategory_id","image_path_full_url","icon_path_full_url"];

    /* ********************************
            Field Append Start
    ******************************** */

    public function getDemoFieldAttribute()
    {
        return 'demo_field';
    }

    public function product_details() {
        return $this->hasMany(Product::class, 'maincategory_id', 'id');
    }

    public function getMaincategoryIdAttribute() {
        return $this->id;
    }

    public function getTotalProductAttribute() {
        $count = $this->product_details()->count();
        return $count ?? 0;
    }

    public function getIconImgPathAttribute($value)
    {
        $icon_path = 'themes/'.APP_THEME().'/upload/require/dot.png';
        if(!empty($this->icon_path)) {
            $icon_path = $this->icon_path;
        }
        return $icon_path;
    }

    public function getImagePathFullUrlAttribute() {
        $image = [];
        if(!empty($this->image_path)) {
            return get_file($this->image_path, $this->theme_id);
        }
        return $image;
    }

    public function getIconPathFullUrlAttribute() {
		$image = [];
        if(!empty($this->icon_path)) {
        	return get_file($this->icon_path, $this->theme_id);
		}
        return $image;
    }

    public static function homePageCategory($themeId, $slug, $section, $no = 2)
    {
        $currentTheme = $themeId;
        $storeId = getCurrenctStoreId($slug);
        $best_seller_category = MainCategory::where('status', 1)->where('theme_id', $themeId)->where('store_id',$storeId)->limit($no)->get();

        return view('front_end.sections.homepage.category_slider', compact('slug','best_seller_category', 'themeId', 'section', 'currentTheme'))->render();
    }

    public static function homePageBestCategory($themeId, $slug, $section, $no = 2)
    {
        $currentTheme = $themeId;
        $storeId = getCurrenctStoreId($slug);
        $cat = Product::select('maincategory_id')->where('theme_id', $themeId)->where('store_id', $storeId)->groupBy('maincategory_id')->pluck('maincategory_id');
        $best_seller_category = MainCategory::whereIn('id', $cat->toArray())->limit($no)->get();

        return view('front_end.sections.homepage.category_slider', compact('slug', 'best_seller_category', 'themeId', 'section','currentTheme'))->render();
    }

    public function subCategory()
    {
        return $this->hasMany(SubCategory::class, 'id', 'maincategory_id');
    }

    public function getCategoryItemAttribute() {
        $count = $this->subCategory()->count();
        return $count ?? 0;
    }
}
