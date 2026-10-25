<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Promotion extends Model { protected $fillable=['code','discount_percent','discount_cents','minimum_subtotal_cents','max_uses','uses','starts_at','ends_at','active']; protected function casts():array{return ['starts_at'=>'datetime','ends_at'=>'datetime','active'=>'boolean'];} }
