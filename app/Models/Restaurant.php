<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Restaurant extends Model { protected $fillable=['owner_id','name','slug','description','is_open','delivery_fee_cents']; protected function casts():array{return ['is_open'=>'boolean'];} public function owner(){return $this->belongsTo(User::class,'owner_id');} public function categories(){return $this->hasMany(MenuCategory::class);} public function items(){return $this->hasMany(MenuItem::class);} public function orders(){return $this->hasMany(FoodOrder::class);} }
