<?php
namespace App\Services;
use App\Models\DeliveryAddress;
use App\Models\FoodOrder;
use App\Models\FoodOrderEvent;
use App\Models\MenuItem;
use App\Models\Promotion;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class OrderCreator {
 public function create(User $customer,Restaurant $restaurant,DeliveryAddress $address,array $lines,?string $code,?string $note): FoodOrder {
  if($address->user_id!==$customer->id)throw ValidationException::withMessages(['address_id'=>'Choose one of your saved addresses.']);
  return DB::transaction(function()use($customer,$restaurant,$address,$lines,$code,$note){
   $restaurant=Restaurant::whereKey($restaurant->id)->lockForUpdate()->firstOrFail();if(!$restaurant->is_open)throw ValidationException::withMessages(['restaurant'=>'Restaurant is currently closed.']);
   $ids=array_map('intval',array_keys($lines));$items=MenuItem::where('restaurant_id',$restaurant->id)->whereIn('id',$ids)->lockForUpdate()->get()->keyBy('id');if(count($ids)!==count($items))throw ValidationException::withMessages(['items'=>'Cart contains unavailable items.']);
   $subtotal=0;foreach($lines as $id=>$qty){$item=$items[(int)$id];$qty=(int)$qty;if(!$item->available||($item->stock!==null&&$item->stock<$qty))throw ValidationException::withMessages(['items'=>'An item is unavailable or does not have enough stock.']);$subtotal+=$item->price_cents*$qty;}
   $discount=0;$promotion=null;if($code){$promotion=Promotion::where('code',strtoupper(trim($code)))->lockForUpdate()->first();if(!$promotion||!$promotion->active||($promotion->starts_at&&$promotion->starts_at->isFuture())||($promotion->ends_at&&$promotion->ends_at->isPast())||($promotion->max_uses!==null&&$promotion->uses >= $promotion->max_uses)||$subtotal<$promotion->minimum_subtotal_cents)throw ValidationException::withMessages(['promotion_code'=>'Promotion code is invalid or unavailable.']);$discount=$promotion->discount_percent?intdiv($subtotal*$promotion->discount_percent,100):min($promotion->discount_cents??0,$subtotal);$discount=min($discount,$subtotal);}
   $order=FoodOrder::create(['reference'=>'FD-'.now()->format('ymd').'-'.strtoupper(bin2hex(random_bytes(8))),'customer_id'=>$customer->id,'restaurant_id'=>$restaurant->id,'delivery_address_id'=>$address->id,'address_snapshot'=>$address->only(['recipient_name','phone','address_line','city','postal_code','delivery_notes']),'subtotal_cents'=>$subtotal,'delivery_fee_cents'=>$restaurant->delivery_fee_cents,'discount_cents'=>$discount,'total_cents'=>$subtotal+$restaurant->delivery_fee_cents-$discount,'promotion_code'=>$promotion?->code,'payment_method'=>'cash_on_delivery','payment_status'=>'unpaid','status'=>'pending','customer_note'=>$note]);
   foreach($lines as $id=>$qty){$item=$items[(int)$id];$qty=(int)$qty;$order->items()->create(['menu_item_id'=>$item->id,'item_name'=>$item->name,'unit_price_cents'=>$item->price_cents,'quantity'=>$qty,'line_total_cents'=>$item->price_cents*$qty]);if($item->stock!==null)$item->decrement('stock',$qty);}
   if($promotion)$promotion->increment('uses');FoodOrderEvent::create(['food_order_id'=>$order->id,'user_id'=>$customer->id,'from_status'=>null,'to_status'=>'pending']);return $order;
  },5);
 }
}
