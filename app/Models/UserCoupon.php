<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCoupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'coupon_id',
        'amount',
        'order_id',
        'date_used',
        'theme_id'
    ];

    protected $appends = ["coupon_name","product_order_id"];

    public function CouponName()
    {
        return $this->hasOne(Coupon::class, 'id', 'coupon_id')->first();
    }

    public function Orderid()
    {
        return $this->hasOne(Order::class, 'id', 'order_id')->first();
    }

    public function getCouponNameAttribute()
    {
        return !empty($this->CouponName()) ? $this->CouponName()->coupon_name : '';

    }

    public function getProductOrderIdAttribute()
    {
        return !empty($this->Orderid()) ? $this->Orderid()->product_order_id : '';

    }

    public function CouponData()
    {
        return $this->hasOne(Coupon::class, 'id', 'coupon_id');
    }

    public function OrderData()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }
}
