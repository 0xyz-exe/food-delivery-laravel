<?php

namespace Database\Seeders;

use App\Models\DeliveryAddress;
use App\Models\FoodOrder;
use App\Models\FoodOrderEvent;
use App\Models\FoodOrderItem;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Promotion;
use App\Models\Restaurant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users with distinct roles
        $admin = User::updateOrCreate(
            ['email' => 'admin@demo.test'],
            [
                'name' => 'Alexander Wright',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->role = 'admin';
        $admin->save();

        $chefKenji = User::firstOrCreate(
            ['email' => 'chef.kenji@kyotoramen.test'],
            ['name' => 'Kenji Takahashi', 'password' => Hash::make('password')]
        );
        $chefKenji->role = 'restaurant';
        $chefKenji->save();

        $maria = User::firstOrCreate(
            ['email' => 'maria.s@truffleburger.test'],
            ['name' => 'Maria Santos', 'password' => Hash::make('password')]
        );
        $maria->role = 'restaurant';
        $maria->save();

        $driverBudi = User::firstOrCreate(
            ['email' => 'budi.santoso@courier.test'],
            ['name' => 'Budi Santoso', 'password' => Hash::make('password')]
        );
        $driverBudi->role = 'delivery';
        $driverBudi->save();

        $driverAgus = User::firstOrCreate(
            ['email' => 'agus.w@courier.test'],
            ['name' => 'Agus Wicaksono', 'password' => Hash::make('password')]
        );
        $driverAgus->role = 'delivery';
        $driverAgus->save();

        $clara = User::firstOrCreate(
            ['email' => 'clara.d@consumer.test'],
            ['name' => 'Clara Danuwijaya', 'password' => Hash::make('password')]
        );
        $clara->role = 'customer';
        $clara->save();

        // 2. Delivery Addresses
        $addrAdmin = DeliveryAddress::firstOrCreate(
            ['user_id' => $admin->id, 'label' => 'Main Office'],
            [
                'recipient_name' => 'Alexander Wright',
                'phone' => '+62 811-2345-6789',
                'address_line' => 'SCBD Equity Tower, Suite 22A, Jl. Jend. Sudirman Kav. 52',
                'city' => 'Jakarta Selatan',
                'postal_code' => '12190',
                'delivery_notes' => 'Leave at 22nd floor lobby reception desk with security badge.',
            ]
        );

        $addrClara = DeliveryAddress::firstOrCreate(
            ['user_id' => $clara->id, 'label' => 'Apartment'],
            [
                'recipient_name' => 'Clara Danuwijaya',
                'phone' => '+62 812-9876-5432',
                'address_line' => 'Senopati Suites Tower 2 Unit 15B, Jl. Senopati Raya',
                'city' => 'Jakarta Selatan',
                'postal_code' => '12180',
                'delivery_notes' => 'Call recipient upon arrival at tower gate.',
            ]
        );

        // 3. Restaurants
        $restRamen = Restaurant::firstOrCreate(
            ['slug' => 'kyoto-artisan-ramen'],
            [
                'owner_id' => $chefKenji->id,
                'name' => 'Kyoto Artisan Ramen & Donburi',
                'description' => 'Authentic slow-simmered rich tonkotsu & tori paitan broth, handmade springy ramen noodles, artisanal chashu, and crispy chicken karaage.',
                'is_open' => true,
                'delivery_fee_cents' => 1200,
            ]
        );

        $restBurger = Restaurant::firstOrCreate(
            ['slug' => 'truffle-burger-co'],
            [
                'owner_id' => $maria->id,
                'name' => 'Truffle Burger & Craft Fries',
                'description' => 'Gourmet smash burgers featuring 100% Australian Wagyu beef, melted aged cheddar, house-crafted black truffle aioli, and brioche buns.',
                'is_open' => true,
                'delivery_fee_cents' => 1500,
            ]
        );

        $restNusantara = Restaurant::firstOrCreate(
            ['slug' => 'sate-khas-nusantara'],
            [
                'owner_id' => $admin->id,
                'name' => 'Sate Khas & Nusantara Grill',
                'description' => 'Charcoal-grilled chicken and wagyu satay skewers with velvety sweet soy peanut glaze, traditional lontong, and authentic gado-gado.',
                'is_open' => true,
                'delivery_fee_cents' => 1000,
            ]
        );

        $restPizza = Restaurant::firstOrCreate(
            ['slug' => 'napoli-woodfired-pizza'],
            [
                'owner_id' => $maria->id,
                'name' => 'Napoli Woodfired Pizzeria',
                'description' => 'Traditional 48-hour fermented sourdough Neapolitan pizzas baked in a volcanic stone oven with San Marzano tomatoes and fior di latte.',
                'is_open' => true,
                'delivery_fee_cents' => 1800,
            ]
        );

        // 4. Categories & Menu Items
        $catRamenMain = MenuCategory::firstOrCreate(['restaurant_id' => $restRamen->id, 'name' => 'Signature Ramen'], ['sort_order' => 1]);
        $catRamenSides = MenuCategory::firstOrCreate(['restaurant_id' => $restRamen->id, 'name' => 'Appetizers & Sides'], ['sort_order' => 2]);
        $catRamenDrinks = MenuCategory::firstOrCreate(['restaurant_id' => $restRamen->id, 'name' => 'Craft Beverages'], ['sort_order' => 3]);

        $itemRamen1 = MenuItem::firstOrCreate(['restaurant_id' => $restRamen->id, 'name' => 'Tori Paitan Rich Collagen Ramen'], [
            'menu_category_id' => $catRamenMain->id,
            'description' => '10-hour simmered chicken collagen broth, ajitama egg, menma bamboo shoots, scallions, nori.',
            'price_cents' => 1450,
            'stock' => 30,
            'available' => true,
        ]);

        $itemRamen2 = MenuItem::firstOrCreate(['restaurant_id' => $restRamen->id, 'name' => 'Spicy Garlic Tonkotsu Ramen'], [
            'menu_category_id' => $catRamenMain->id,
            'description' => 'Silky pork bone broth with roasted garlic oil, chili paste, torched pork chashu, and wood ear mushrooms.',
            'price_cents' => 1580,
            'stock' => 25,
            'available' => true,
        ]);

        $itemKaraage = MenuItem::firstOrCreate(['restaurant_id' => $restRamen->id, 'name' => 'Crispy Chicken Karaage (6 pcs)'], [
            'menu_category_id' => $catRamenSides->id,
            'description' => 'Double-fried ginger-soy marinated chicken thigh served with Japanese Kewpie mayonnaise and lemon wedge.',
            'price_cents' => 650,
            'stock' => 40,
            'available' => true,
        ]);

        $itemGyoza = MenuItem::firstOrCreate(['restaurant_id' => $restRamen->id, 'name' => 'Handmade Pan-Fried Pork Gyoza'], [
            'menu_category_id' => $catRamenSides->id,
            'description' => 'Crispy lace-bottom dumplings stuffed with minced pork, scallions, and cabbage with rayu dipping sauce.',
            'price_cents' => 580,
            'stock' => 35,
            'available' => true,
        ]);

        $itemTea = MenuItem::firstOrCreate(['restaurant_id' => $restRamen->id, 'name' => 'Iced Genmaicha Roasted Green Tea'], [
            'menu_category_id' => $catRamenDrinks->id,
            'description' => 'Cold-brewed Japanese green tea blended with roasted brown rice grains, unsweetened and refreshing.',
            'price_cents' => 350,
            'stock' => 50,
            'available' => true,
        ]);

        // Burger items
        $catBurgerMain = MenuCategory::firstOrCreate(['restaurant_id' => $restBurger->id, 'name' => 'Artisan Smash Burgers'], ['sort_order' => 1]);
        $catBurgerSides = MenuCategory::firstOrCreate(['restaurant_id' => $restBurger->id, 'name' => 'Gourmet Sides'], ['sort_order' => 2]);

        $itemBurger1 = MenuItem::firstOrCreate(['restaurant_id' => $restBurger->id, 'name' => 'Double Wagyu Truffle Smash Burger'], [
            'menu_category_id' => $catBurgerMain->id,
            'description' => 'Two 100g Australian wagyu patties, melted Wisconsin cheddar, caramelized onions, black truffle aioli, toasted brioche.',
            'price_cents' => 1600,
            'stock' => 25,
            'available' => true,
        ]);

        $itemBurgerFries = MenuItem::firstOrCreate(['restaurant_id' => $restBurger->id, 'name' => 'Parmesan Truffle Rustic Fries'], [
            'menu_category_id' => $catBurgerSides->id,
            'description' => 'Hand-cut skin-on Idaho potatoes tossed in white truffle oil, grated 24-month Parmigiano-Reggiano, fresh parsley.',
            'price_cents' => 600,
            'stock' => 45,
            'available' => true,
        ]);

        // Sate items
        $catSateMain = MenuCategory::firstOrCreate(['restaurant_id' => $restNusantara->id, 'name' => 'Signature Satay'], ['sort_order' => 1]);
        MenuItem::firstOrCreate(['restaurant_id' => $restNusantara->id, 'name' => 'Sate Ayam Madura Saus Kacang (10 Tusuk)'], [
            'menu_category_id' => $catSateMain->id,
            'description' => 'Charcoal-grilled tender chicken skewers smothered in velvety peanut sauce, sweet soy, and fried shallots.',
            'price_cents' => 850,
            'stock' => 30,
            'available' => true,
        ]);
        MenuItem::firstOrCreate(['restaurant_id' => $restNusantara->id, 'name' => 'Sate Maranggi Sapi Wagyu (5 Tusuk)'], [
            'menu_category_id' => $catSateMain->id,
            'description' => 'Marinated wagyu beef cubes grilled over coconut charcoal, served with fresh tomato sambal and lime.',
            'price_cents' => 1100,
            'stock' => 25,
            'available' => true,
        ]);

        // Pizza items
        $catPizzaMain = MenuCategory::firstOrCreate(['restaurant_id' => $restPizza->id, 'name' => 'Neapolitan Pizzas'], ['sort_order' => 1]);
        MenuItem::firstOrCreate(['restaurant_id' => $restPizza->id, 'name' => 'Margherita D.O.P. Woodfired Pizza'], [
            'menu_category_id' => $catPizzaMain->id,
            'description' => 'San Marzano tomato base, fresh buffalo mozzarella fior di latte, sweet basil leaves, and extra virgin olive oil.',
            'price_cents' => 1500,
            'stock' => 20,
            'available' => true,
        ]);
        MenuItem::firstOrCreate(['restaurant_id' => $restPizza->id, 'name' => 'Quattro Formaggi Truffle Honey Pizza'], [
            'menu_category_id' => $catPizzaMain->id,
            'description' => 'Four cheeses blend of gorgonzola, aged fontina, parmesan, and mozzarella finished with organic truffle honey.',
            'price_cents' => 1750,
            'stock' => 18,
            'available' => true,
        ]);

        // 5. Promotions
        Promotion::firstOrCreate(['code' => 'SAVE20'], [
            'discount_percent' => 20,
            'minimum_subtotal_cents' => 2500,
            'max_uses' => 500,
            'uses' => 24,
            'active' => true,
        ]);

        Promotion::firstOrCreate(['code' => 'FLAT5OFF'], [
            'discount_cents' => 500,
            'minimum_subtotal_cents' => 3000,
            'max_uses' => 200,
            'uses' => 12,
            'active' => true,
        ]);

        // 6. Food Orders
        $order1 = FoodOrder::firstOrCreate(['reference' => 'ORD-20261030-9142'], [
            'customer_id' => $admin->id,
            'restaurant_id' => $restRamen->id,
            'delivery_staff_id' => $driverBudi->id,
            'delivery_address_id' => $addrAdmin->id,
            'address_snapshot' => [
                'recipient_name' => $addrAdmin->recipient_name,
                'phone' => $addrAdmin->phone,
                'address_line' => $addrAdmin->address_line,
                'city' => $addrAdmin->city,
                'postal_code' => $addrAdmin->postal_code,
            ],
            'subtotal_cents' => 4250,
            'delivery_fee_cents' => 1200,
            'discount_cents' => 850,
            'total_cents' => 4600,
            'promotion_code' => 'SAVE20',
            'payment_method' => 'cash_on_delivery',
            'payment_status' => 'unpaid',
            'status' => 'preparing',
            'customer_note' => 'Please pack chili oil and fried shallots on the side. Extra nori if possible.',
            'created_at' => Carbon::now()->subMinutes(25),
        ]);

        if ($order1->wasRecentlyCreated) {
            $order1->items()->create(['menu_item_id' => $itemRamen1->id, 'item_name' => $itemRamen1->name, 'unit_price_cents' => 1450, 'quantity' => 2, 'line_total_cents' => 2900]);
            $order1->items()->create(['menu_item_id' => $itemKaraage->id, 'item_name' => $itemKaraage->name, 'unit_price_cents' => 650, 'quantity' => 1, 'line_total_cents' => 650]);
            $order1->items()->create(['menu_item_id' => $itemTea->id, 'item_name' => $itemTea->name, 'unit_price_cents' => 350, 'quantity' => 2, 'line_total_cents' => 700]);

            $order1->events()->create(['user_id' => $admin->id, 'from_status' => null, 'to_status' => 'pending', 'created_at' => Carbon::now()->subMinutes(25)]);
            $order1->events()->create(['user_id' => $chefKenji->id, 'from_status' => 'pending', 'to_status' => 'accepted', 'created_at' => Carbon::now()->subMinutes(20)]);
            $order1->events()->create(['user_id' => $chefKenji->id, 'from_status' => 'accepted', 'to_status' => 'preparing', 'created_at' => Carbon::now()->subMinutes(12)]);
        }

        $order2 = FoodOrder::firstOrCreate(['reference' => 'ORD-20261030-8815'], [
            'customer_id' => $clara->id,
            'restaurant_id' => $restBurger->id,
            'delivery_staff_id' => $driverAgus->id,
            'delivery_address_id' => $addrClara->id,
            'address_snapshot' => [
                'recipient_name' => $addrClara->recipient_name,
                'phone' => $addrClara->phone,
                'address_line' => $addrClara->address_line,
                'city' => $addrClara->city,
                'postal_code' => $addrClara->postal_code,
            ],
            'subtotal_cents' => 2800,
            'delivery_fee_cents' => 1500,
            'discount_cents' => 500,
            'total_cents' => 3800,
            'promotion_code' => 'FLAT5OFF',
            'payment_method' => 'cash_on_delivery',
            'payment_status' => 'unpaid',
            'status' => 'ready',
            'customer_note' => 'Ring doorbell twice upon arrival.',
            'created_at' => Carbon::now()->subMinutes(40),
        ]);

        if ($order2->wasRecentlyCreated) {
            $order2->items()->create(['menu_item_id' => $itemBurger1->id, 'item_name' => $itemBurger1->name, 'unit_price_cents' => 1600, 'quantity' => 1, 'line_total_cents' => 1600]);
            $order2->items()->create(['menu_item_id' => $itemBurgerFries->id, 'item_name' => $itemBurgerFries->name, 'unit_price_cents' => 600, 'quantity' => 2, 'line_total_cents' => 1200]);

            $order2->events()->create(['user_id' => $clara->id, 'from_status' => null, 'to_status' => 'pending', 'created_at' => Carbon::now()->subMinutes(40)]);
            $order2->events()->create(['user_id' => $maria->id, 'from_status' => 'pending', 'to_status' => 'accepted', 'created_at' => Carbon::now()->subMinutes(35)]);
            $order2->events()->create(['user_id' => $maria->id, 'from_status' => 'accepted', 'to_status' => 'preparing', 'created_at' => Carbon::now()->subMinutes(28)]);
            $order2->events()->create(['user_id' => $maria->id, 'from_status' => 'preparing', 'to_status' => 'ready', 'created_at' => Carbon::now()->subMinutes(5)]);
        }

        $order3 = FoodOrder::firstOrCreate(['reference' => 'ORD-20261029-7104'], [
            'customer_id' => $admin->id,
            'restaurant_id' => $restNusantara->id,
            'delivery_staff_id' => $driverBudi->id,
            'delivery_address_id' => $addrAdmin->id,
            'address_snapshot' => [
                'recipient_name' => $addrAdmin->recipient_name,
                'phone' => $addrAdmin->phone,
                'address_line' => $addrAdmin->address_line,
                'city' => $addrAdmin->city,
                'postal_code' => $addrAdmin->postal_code,
            ],
            'subtotal_cents' => 3800,
            'delivery_fee_cents' => 1000,
            'discount_cents' => 0,
            'total_cents' => 4800,
            'payment_method' => 'cash_on_delivery',
            'payment_status' => 'paid',
            'status' => 'delivered',
            'customer_note' => 'Delivered on time.',
            'created_at' => Carbon::now()->subDay(),
        ]);
    }
}
