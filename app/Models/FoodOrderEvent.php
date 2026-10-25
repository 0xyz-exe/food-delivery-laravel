<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class FoodOrderEvent extends Model { protected $fillable=['food_order_id','user_id','from_status','to_status']; public function order(){return $this->belongsTo(FoodOrder::class,'food_order_id');} public function user(){return $this->belongsTo(User::class);} }
