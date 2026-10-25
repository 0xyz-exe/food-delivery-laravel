<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isRestaurantOwner(): bool { return in_array($this->role, ['admin', 'restaurant'], true); }
    public function isDeliveryStaff(): bool { return in_array($this->role, ['admin', 'delivery'], true); }
    public function restaurants(){return $this->hasMany(Restaurant::class,'owner_id');}
    public function addresses(){return $this->hasMany(DeliveryAddress::class);}
    public function foodOrders(){return $this->hasMany(FoodOrder::class,'customer_id');}

    protected function casts(): array { return ['email_verified_at'=>'datetime','password'=>'hashed']; }
}
