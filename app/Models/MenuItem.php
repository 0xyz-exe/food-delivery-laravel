<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class MenuItem extends Model { protected $fillable=['restaurant_id','menu_category_id','name','description','price_cents','stock','available','image_path']; protected function casts():array{return ['available'=>'boolean'];} public function restaurant(){return $this->belongsTo(Restaurant::class);} public function category(){return $this->belongsTo(MenuCategory::class,'menu_category_id');} }
