<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DeliveryAddress extends Model { protected $fillable=['user_id','label','recipient_name','phone','address_line','city','postal_code','delivery_notes']; public function user(){return $this->belongsTo(User::class);} }
