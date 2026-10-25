<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class FoodOrderItem extends Model { protected $fillable=['food_order_id','menu_item_id','item_name','unit_price_cents','quantity','line_total_cents']; public function order(){return $this->belongsTo(FoodOrder::class,'food_order_id');} }
