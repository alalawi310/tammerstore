<?php

namespace App\Exports;

use App\Models\MainCategory;
use App\Models\Product;
use App\Models\SubCategory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function collection()
    {
        $product = Product::where('id', $this->id)->first();
        $detail = strip_tags($product->detail);
        $specification = strip_tags($product->specification);

        $productData = [
            'name' => $product->name,
            'slug' => $product->slug,
            'maincategory_name' => $product->category_name,
            'subcategory_name' => $product->sub_category_name,
            'price' => $product->price,
            'sale_price' => $product->sale_price ?? null,
            'final_price' => $product->final_price ?? null,
            'brand' => $product->brand->name ?? null,
            'label' => $product->label->name ?? null,
            'tax_id' => $product->Tax->name ?? null,
            'tax_status' => $product->tax_status ?? null,
            'product_weight' => $product->product_weight ?? null,
            'product_stock' => $product->product_stock ?? null,
            'stock_status' => $product->stock_status ?? null,
            'low_stock_threshold' => $product->low_stock_threshold ?? null,
            'description' => $product->description ?? null,
            'detail' => $detail ?? null,
            'specification' => $specification ?? null,
        ];
        return new Collection([$productData]);
    }

    public function headings(): array
    {
        return [
            "Procuct Name",
            "Procuct Slug Name",
            "Main Category Name",
            "Sub Category Name",
            "Product Price",
            "Sale Price",
            "Final Price",
            "Product Brand",
            "Product Lable",
            "Tax",
            "Tax Status",
            "Product Weight",
            "Product Stock",
            "Stock Status",
            "Low Stock Threshold",
            "Procuct Description",
            "Procuct Detail",
            "Procuct Specification",
        ];
    }
}
