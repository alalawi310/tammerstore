<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Product;
use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\ProductBrand;
use App\Models\ProductLabel;
use App\Models\Shipping;
use App\Models\Tax;

class ProductsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Product|null
     */
    public function model(array $row)
    {

        $maincategory = MainCategory::where('name', $row['main_category_name'])->first();

        if (!$maincategory) {
            $maincategory = new MainCategory();
            $maincategory->name = $row['main_category_name'];
            $maincategory->slug =  'collections/' . strtolower(preg_replace("/[^\w]+/", "-", $row['main_category_name']));
            $maincategory->image_url ='Modules/ImportExport/resources/assets/img/download.jpeg';
            $maincategory->image_path = 'Modules/ImportExport/resources/assets/img/download.jpeg';
            $maincategory->icon_path = 'Modules/ImportExport/resources/assets/img/download.jpeg';
            $maincategory->trending = 1;
            $maincategory->status = 1;
            $maincategory->theme_id = APP_THEME();
            $maincategory->store_id = getCurrentStore();
            $maincategory->save();
        }

        $subcategory = SubCategory::where('name',$row['sub_category_name'])->first();

        if (!$subcategory) {
            $subcategory = new SubCategory();
            $subcategory->name = $row['sub_category_name'];
            $subcategory->maincategory_id = isset($maincategory->id) ? $maincategory->id : $maincategory->name;
            $subcategory->image_url ='Modules/ImportExport/resources/assets/img/download.jpeg';
            $subcategory->image_path = 'Modules/ImportExport/resources/assets/img/download.jpeg';
            $subcategory->icon_path = 'Modules/ImportExport/resources/assets/img/download.jpeg';
            $subcategory->status = 1;
            $subcategory->theme_id = APP_THEME();
            $subcategory->store_id = getCurrentStore();
            $subcategory->save();
        }

        $brand = ProductBrand::where('name',$row['product_brand'])->first();

        if (!$brand) {
            $brand = new ProductBrand();
            $brand->name = $row['product_brand'];
            $brand->slug = ProductBrand::slugs($row['product_brand']);
            $brand->logo ='Modules/ImportExport/resources/assets/img/download.jpeg';
            $brand->status = 1;
            $brand->is_popular = 1;
            $brand->store_id = getCurrentStore();
            $brand->theme_id = APP_THEME();
            $brand->created_by = \Auth::user()->id;
            $brand->save();
        }

        $label = ProductLabel::where('name',$row['product_label'])->first();

        if (!$label) {
            $label = new ProductLabel();
            $label->name = $row['product_label'];
            $label->slug = ProductLabel::slugs($row['product_label']);
            $label->status = 1;
            $label->theme_id = APP_THEME();
            $label->store_id = getCurrentStore();
            $label->created_by = \Auth::user()->id;
            $label->save();
        }

        $shipping = Shipping::where('name',$row['shipping'])->first();

        if (!$shipping) {
            $shipping = new Shipping();
            $shipping->name = $row['product_label'];
            $shipping->slug = str_replace(' ', '-', strtolower($row['product_label']));
            $shipping->description = 'Shipping description';
            $shipping->theme_id = APP_THEME();
            $shipping->store_id = getCurrentStore();
            $shipping->save();
        }

        $tax = Tax::where('name',$row['tax'])->first();

        if (!$tax) {
            $tax = new Tax();
            $tax->name = $row['tax'];
            $tax->theme_id = APP_THEME();
            $tax->store_id = getCurrentStore();
            $tax->save();
        }

        return new Product([
            'name'                  => $row['product_name'],
            'maincategory_id'       => isset($maincategory->id) ? $maincategory->id : $maincategory->name,
            'subcategory_id'        => isset($subcategory->id) ? $subcategory->id : $subcategory->name,
            'price'                 => $row['sale_price'],
            'sale_price'            => $row['final_price'],
            'brand_id'              => isset($brand->id) ? $brand->id : $brand->name,
            'label_id'              => isset($label->id) ? $label->id : $label->name,
            'tax_id'                => isset($tax->id) ? $tax->id : $tax->name,
            'product_weight'        => $row['product_weight'],
            'track_stock'           => $row['track_stock'],
            'product_stock'         => $row['product_stock'],
            'low_stock_threshold'   => $row['row_stock_threshold'],
            'description'           => $row['product_description'],
            'variant_product'       =>  0,
            'shipping_id'           => isset($shipping->id) ? $shipping->id : $shipping->name,
            'specification'         => $row['specification'],
            'detail'                => $row['details'],
            'detail'                => $row['details'],
            'theme_id'              => APP_THEME(),
            'store_id'              => getCurrentStore(),
        ]);
    }

}
