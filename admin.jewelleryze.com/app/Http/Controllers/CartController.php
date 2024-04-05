<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BreadcrumbImage;
use App\Models\BannerImage;
use App\Models\ProductVariantItem;
use App\Models\Product;
use App\Models\FlashSaleProduct;
use App\Models\FlashSale;
use App\Models\Coupon;
use App\Models\Setting;
use App\Models\ShoppingCart;
use App\Models\ShoppingCartVariant;
use Cart;
use Session;
use Auth;
class CartController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function cart(){
        $user = Auth::guard('api')->user();
        $cartProducts = ShoppingCart::with('product','variants.variantItem')->where('user_id', $user->id)->select('id','product_id','qty')->get();

        return response()->json(['cartProducts' => $cartProducts],200);
    }

    public function addToCart(Request $request){

        $user = Auth::guard('api')->user();
        //$cartProductQty = ShoppingCart::where(['user_id' => $user->id, 'product_id' => $request->product_id])->get();
        $productStock = Product::find($request->product_id);
        $stock = $productStock->qty - $productStock->sold_qty;

        if($stock == 0){
            $notification = trans('Product stock out');
            return response()->json(['message' => $notification],403);
        }

        if($stock < $request->quantity){
            $notification = trans('Quantity not available in our stocks');
            return response()->json(['message' => $notification],403);
        }
		
		$variantIds = "";
        if($request->items){
            foreach($request->items as $index => $varr){
               $variantIds = $variantIds.$varr.",";
            }
        }

        $productInCartExist = ShoppingCart::where(['user_id' => $user->id, 'product_id' => $request->product_id, 'variant_item_ids' => $variantIds])->first();
        if($productInCartExist){

            $item = ShoppingCart::where(['user_id' => $user->id, 'product_id' => $request->product_id, 'variant_item_ids' => $variantIds])->first();
            $current_qty = $item->qty;
			$newOrderQty = $current_qty + $request->quantity;

            $productStock = Product::find($item->product_id);
            $stock = $productStock->qty - $productStock->sold_qty;

            if($stock < $newOrderQty){
                $notification = trans('No more quantity available in the stock at present.');
                return response()->json(['message' => $notification],403);
            }

            $item->qty = $item->qty + $request->quantity;
            $item->save();
			$item->id;

            $notification = trans('Quantity Increased successfully');
            return response()->json(['message' => $notification, 'cartID' => $item->id]);

        } else {

            $item = new ShoppingCart();
            $item->user_id = $user->id;
            $item->product_id = $request->product_id;
            $item->qty = $request->quantity;
            $item->coupon_name = '';
            $item->offer_type = 0;
			$item->variant_item_ids = $variantIds;
            $item->save();
			$item->id;


            if($request->variants && $request->items){
                foreach($request->variants as $index => $varr){
                    if($request->items[$index] != '-1' && $request->variants[$index] != '-1'){
                        $variant = new ShoppingCartVariant();
                        $variant->shopping_cart_id = $item->id;
                        $variant->variant_id = $varr;
                        $variant->variant_item_id = $request->items[$index];
                        $variant->save();
                    }
                }
            }

            $notification = trans('Item added successfully');
            return response()->json(['message' => $notification, 'cartID' => $item->id]);

        }

        
    }

    public function cartItemIncrement($id){
        $item = ShoppingCart::find($id);
        $current_qty = $item->qty;

        $productStock = Product::find($item->product_id);
        $stock = $productStock->qty - $productStock->sold_qty;

        if($stock <= $current_qty){
            $notification = trans('The required quantity is not available in the stock at present.');
            return response()->json(['message' => $notification],403);
        } else {
            $item->qty = $item->qty + 1;
            $item->save();

            $notification = trans('Update successfully');
            return response()->json(['message' => $notification]);
        }

       
    }

    public function cartItemDecrement($id){
        $item = ShoppingCart::find($id);
        if($item->qty > 1){
            $item->qty = $item->qty - 1;
            $item->save();

            $notification = trans('Update successfully');
            return response()->json(['message' => $notification]);
        }else{
            $notification = trans('Minimum Order Quantity Cannot be less than 1');
            return response()->json(['message' => $notification],403);
        }

    }

    public function cartItemRemove($rowId){
        $user = Auth::guard('api')->user();
        $cartProduct = ShoppingCart::where(['user_id' => $user->id, 'id' => $rowId])->first();
        ShoppingCartVariant::where('shopping_cart_id', $rowId)->delete();
        $cartProduct->delete();

        $notification = trans('Remove successfully');
        return response()->json(['message' => $notification]);
    }

    public function cartClear(){
        $user = Auth::guard('api')->user();
        $cartProducts = ShoppingCart::where(['user_id' => $user->id])->get();
        foreach($cartProducts as $cartProduct){
            ShoppingCartVariant::where('shopping_cart_id', $cartProduct->id)->delete();
            $cartProduct->delete();
        }

        $notification = trans('Cart clear successfully');
        return response()->json(['message' => $notification]);
    }


    public function applyCoupon(Request $request){
        if($request->coupon == null){
            $notification = trans('Coupon Field is required');
            return response()->json(['message' => $notification],403);
        }

        $user = Auth::guard('api')->user();
        $count = ShoppingCart::where('user_id', $user->id)->count();
        if($count == 0){
            $notification = trans('Your shopping cart is empty');
            return response()->json(['message' => $notification],403);
        }

        $coupon = Coupon::where(['code' => $request->coupon, 'status' => 1])->first();

        if(!$coupon){
            $notification = trans('Invalid Coupon');
            return response()->json(['message' => $notification],403);
        }

        if($coupon->expired_date < date('Y-m-d')){
            $notification = trans('Coupon already expired');
            return response()->json(['message' => $notification],403);
        }

        if($coupon->apply_qty >=  $coupon->max_quantity ){
            $notification = trans('Sorry! You can not apply this coupon');
            return response()->json(['message' => $notification],403);
        }

        return response()->json(['coupon' => $coupon]);
    }


    public function calculateProductPrice(Request $request) {
        $prices = [];
        $variantPrice = 0;
        if($request->variants){
            foreach($request->variants as $index => $varr){
                $item = ProductVariantItem::where(['id' => $request->items[$index]])->first();
                $prices[] = $item->price;
            }
            $variantPrice = $variantPrice + array_sum($prices);
        }

        $product = Product::with('tax')->find($request->product_id);


        $isCampaign = false;
        $today = date('Y-m-d');
        $campaign = CampaignProduct::where(['status' => 1, 'product_id' => $product->id])->first();
        if($campaign){
            $campaign = $campaign->campaign;
            if($campaign->start_date <= $today &&  $today <= $campaign->end_date){
                $isCampaign = true;
            }
            $campaignOffer = $campaign->offer;
            $productPrice = $product->price;
            $campaignOfferPrice = ($campaignOffer / 100) * $productPrice;
            $totalPrice = $product->price;
            $campaignOfferPrice = $totalPrice - $campaignOfferPrice;
        }else{
            $totalPrice = $product->price;
            if($product->offer_price != null){
                $offerPrice = $product->offer_price;
            }
        }

        $productPrice = 0;
        if($isCampaign){
            $productPrice = $campaignOfferPrice + $variantPrice;
        }else{
            if ($product->offer_price == null) {
                $productPrice = $totalPrice + $variantPrice;
            }else {
                $productPrice = $product->offer_price + $variantPrice;
            }
        }

        $productPrice = round($productPrice, 2);
        return response()->json(['productPrice' => $productPrice]);
    }

}
