<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BreadcrumbImage;

use App\Models\Country;
use App\Models\CountryState;
use App\Models\City;
use App\Models\Vendor;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderProductVariant;
use App\Models\OrderAddress;
use App\Models\Product;
use App\Models\Setting;
use App\Mail\OrderSuccessfully;
use App\Helpers\MailHelper;
use App\Models\EmailTemplate;

use App\Models\CcavenuePayment;

use App\Models\Coupon;
use App\Models\ShoppingCart;
use App\Models\ProductVariantItem;
use App\Models\FlashSaleProduct;
use App\Models\FlashSale;
use App\Models\Shipping;
use App\Models\Address;
use App\Models\SslcommerzPayment;
use App\Models\ShoppingCartVariant;
use Mail;
use Stripe;
use Cart;
use Session;
use Auth;
use Str;
use Exception;
use Redirect;
use App\Library\SslCommerz\SslCommerzNotification;
use Mollie\Laravel\Facades\Mollie;

use Twilio\Rest\Client;
use App\Models\SmsTemplate;
use App\Models\TwilioSms;


class CcavenueController extends Controller
{
    

    public function ccavenueOrder(Request $request)
    {
        //dd($request);
        $user = Auth::guard("api")->user();
        $total = $this->calculateCartTotal(
            $user,
            $request->coupon,
            $request->shipping_method_id,
            $request->buy_now_productid
        );

        $ccavenue = CcavenuePayment::first();

        //dd($total);
        $total_price = $total["total_price"];
        $gst_fee = $total["tax_fee"];
        $payable_amount = $total_price * $ccavenue->currency_rate;
        $payable_amount = round($payable_amount, 2);
        $order_id = "ccaven_".date("YmdHis").random_int(100000, 999999);
        $currency = $ccavenue->currency_code;

        $data = [
            "key" => $ccavenue->key,
            "amount" => $payable_amount,
            "order_id" => $order_id,
        ];

        return response()->json($data, 200);
    }

    public function ccavenueWebView(Request $request)
    {
        $rules = [
            "order_id" => "required",
            "request_from" => "required",
            "shipping_address_id" => "required",
            "billing_address_id" => "required",
            "shipping_method_id" => "required",
            "amount" => "required",
        ];

        $this->validate($request, $rules);
        $user = Auth::guard("api")->user();

        $cartProducts = ShoppingCart::with("product", "variants.variantItem")
            ->where("user_id", $user->id)
            ->select("id", "product_id", "qty")
            ->get();

        if ($cartProducts->count() == 0) {
            $notification = trans("Your shopping cart is empty");
            return response()->json(["message" => $notification], 403);
        }
       
        $ccavenue = CcavenuePayment::first();
        $ccavenue_key = $ccavenue->key;

        $orderId = $request->order_id;
        $payable_amount = $request->amount;

        $UniqueTid = abs(crc32( uniqid('') ) );  
        $currencyCode = $ccavenue->currency_code;
        $merchantId =  $ccavenue->merchand_id;
        $accessCode = $ccavenue->key;
        $workingKey = $ccavenue->secret_key;

        $payment_success_url =  route('user.checkout.ccavenue-pay-verify');
        $frontend_success_url = $request->frontend_success_url;
        $frontend_faild_url = $request->frontend_faild_url;
        $request_from = $request->request_from;
        Session::put('frontend_success_url', $frontend_success_url);
        Session::put('frontend_faild_url', $frontend_faild_url);
        Session::put('request_from', $request_from);

        $shipping_address_id = $request->query('shipping_address_id');
        $billing_address_id = $request->query('billing_address_id');
        $shipping_method_id = $request->query('shipping_method_id');

        Session::put('shipping_address_id', $shipping_address_id);
        Session::put('billing_address_id', $billing_address_id);
        Session::put('shipping_method_id', $shipping_method_id);
        
        Session::put('user', $user);
        $coupon = $request->coupon;
        $token = $request->token;
        $billing = Address::find($billing_address_id);
        $shipping = Address::find($shipping_address_id);
        $UserId = $user->id;
        $billing_name = $billing->name;
        $billing_email = $billing->email;
        $billing_phone = $billing->phone;
        $billing_address = $billing->address;
        $billing_pincode = $billing->pincode;
        $billing_country = $billing->country->name;
        $billing_state = $billing->countryState->name;
        $billing_city = $billing->city_name;

        return view(
            "ccavenue_webview",
            compact(
                "orderId",
                "UserId",
                "ccavenue",
                "workingKey",
                "accessCode",
                "merchantId",
                "currencyCode",
                "UniqueTid",
                "payable_amount",
                "payment_success_url",
                "frontend_success_url",
                "frontend_faild_url",
                "request_from",
                "shipping_address_id",
                "billing_address_id",
                "shipping_method_id",
                "coupon",
                "token",
                "billing_name",
                "billing_email",
                "billing_phone",
                "billing_address",
                "billing_pincode",
                "billing_country",
                "billing_state",
                "billing_city"
            )
        );
    }

    

    public function ccavenueVerify(Request $request)
    {
        function decrypts($encryptedText,$key)
        {
          $key = hextobin(md5($key));
          $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
          $encryptedText = hextobin($encryptedText);
          $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
          return $decryptedText;
        }

        function hextobin($hexString) 
         { 
          $length = strlen($hexString); 
          $binString="";   
          $count=0; 
          while($count<$length) 
          {       
            $subString =substr($hexString,$count,2);           
            $packedString = pack("H*",$subString); 
            if ($count==0)
            {
              $binString=$packedString;
            } 
            
            else 
            {
              $binString.=$packedString;
            } 
            
            $count+=2; 
          } 
            return $binString; 
        }


        $ccavenue = CcavenuePayment::first();
        $workingKey = $ccavenue->secret_key;  //Working Key should be provided here.
        $encResponse=$_POST["encResp"];
        

        $rcvdString=decrypts($encResponse,$workingKey);      //Crypto Decryption used as per the specified working key.
        $order_status="";
        $decryptValues=explode('&', $rcvdString);
        $dataSize=sizeof($decryptValues);

        // echo "<pre>";
        // echo "received String";
        // print_r($rcvdString);
        // echo "\n\r";
        // echo "decryptValues String";
        // print_r($decryptValues);
        // echo "\n\r";
        // echo "dataSize";
        // print_r($dataSize);
        // echo "\n\r";
        // dd(Session());
        // echo "</pre>";
        // exit;

        $invalidError = '';
        for($i = 0; $i < $dataSize; $i++) 
        {
            $information=explode('=',$decryptValues[$i]);
            if($i==3)   $order_status=$information[1];
            if($i==2)   $bank_ref_no=$information[1];
            if($i==0)   $payOrderid=$information[1];
            if($i==1)   $tranx_id=$information[1];
            if($i==8)   $invalidError=$information[1];
        }

        if($order_status==="Success") { $responseMessage = "Thank you for shopping with us. Your transaction is successful. We will be shipping your order soon.";}
        else if($order_status==="Aborted"){ $responseMessage = "Thank you for shopping with us.We will keep you posted regarding the status of your order through e-mail"; }
        else if($order_status==="Failure") { $responseMessage = "Thank you for shopping with us.However,the transaction has been declined."; }
        else if($order_status==="Failure") { $responseMessage = "Thank you for shopping with us.However,the transaction has been declined."; }
        else if($order_status==="Invalid") { $responseMessage = "Thank you for shopping with us.However,the transaction has failed due to some missing/invalid parameters (".$invalidError.")."; }
        else { $responseMessage = "Security Error. Illegal access detected"; }
        //end response

        $request_from = Session::get('request_from');
        $shipping_address_id = Session::get('shipping_address_id');
        $billing_address_id = Session::get('billing_address_id');
        $shipping_method_id = Session::get('shipping_method_id');
        $frontend_success_url = Session::get('frontend_success_url');
        $frontend_faild_url = Session::get('frontend_faild_url');
        if ($order_status==="Success") {

            $user = Session::get('user');
            $total = $this->calculateCartTotal(
                    $user, 
                    $request->coupon, 
                    $shipping_method_id, 
                    $request->buy_now_productid
                );
            
            if(is_array($total))
            {
                $total_price = $total["total_price"];
                $coupon_price = $total["coupon_price"];
                $shipping_fee = $total["shipping_fee"];
                $gst_fee = $total["tax_fee"];
                $productWeight = $total["productWeight"];
                $shipping = $total["shipping"];
            }
            else
            {
                $total_price = $total->total_price;
                $coupon_price = $total->coupon_price;
                $shipping_fee = $total->shipping_fee;
                $gst_fee = $total->tax_fee;
                $productWeight = $total->productWeight;
                $shipping = $total->shipping;
            }

            if(!is_null($request->buy_now_productid)){
            
                $totalProduct = ShoppingCart::with("variants")
                    ->where("user_id", $user->id)
                    ->where('id', $request->buy_now_productid)
                    ->sum("qty");
            }else{

                $totalProduct = ShoppingCart::with("variants")
                    ->where("user_id", $user->id)
                    ->sum("qty");
            }


            $setting = Setting::first();
            $amount_real_currency = $total_price;
            $amount_usd = round($total_price / $setting->currency_rate, 2);
            $currency_rate = $setting->currency_rate;
            $currency_icon = $setting->currency_icon;
            $currency_name = $setting->currency_name;
            $transaction_id = $tranx_id;
            $orderId = substr(rand(0, time()), 0, 10);
            //dd($request);
            $order_result = $this->orderStore(
                $user,
                $orderId,
                $total_price,
                $totalProduct,
                "CCAVENUE",
                $transaction_id,
                1,
                $shipping,
                $shipping_fee,
                $gst_fee,
                $coupon_price,
                0,
                $billing_address_id,
                $shipping_address_id,
                $request->buy_now_productid
            );

            $this->sendOrderSuccessMail(
                $user,
                $total_price,
                "CCAVENUE",
                1,
                $order_result["order"],
                $order_result["order_details"]
            );
            
            if ($request_from == "react_web") {
                $order = $order_result["order"];
                $success_url = $frontend_success_url;
                $notification = trans("Payment Successfull");
                $success_url = $success_url . "/" . $orderId;
                
                return redirect($success_url);
                
            } else {
                return redirect()->route(
                    "user.checkout.order-success-url-for-mobile-app"
                );
            }
        } else {

            if ($request_from == "react_web") {
                $notification = trans("Payment Failed");
                if(!empty($frontend_faild_url)){
                    return redirect($frontend_faild_url);    
                }
                else
                {
                    return redirect(env('FRONTEND_URL', 'https://jewelleryze.com/').'checkout');    
                }
                // $faild_url = $frontend_faild_url;
                // return redirect($faild_url);
                
                
                
            } else {
                return redirect()->route(
                    "user.checkout.order-fail-url-for-mobile-app"
                );
            }
            $notification = trans("Payment Failed");
            return response()->json(
                ["status" => "faild", "message" => $notification],
                403
            );
        }

    }

    public function calculateCartTotal(
        $user,
        $request_coupon,
        $request_shipping_method_id,
        $request_buy_now_productid
    ) {
        $total_price = 0;
        $coupon_price = 0;
        $shipping_fee = 0;
        $productWeight = 0;

        if(($request_buy_now_productid === "null")){
            $cartProducts = ShoppingCart::with("product", "variants.variantItem")
            ->where("user_id", $user->id)
            ->select("id", "product_id", "qty")
            ->get();
        }else{
            $cartProducts = ShoppingCart::with("product", "variants.variantItem")
            ->where("user_id", $user->id)
            ->where('id', $request_buy_now_productid)
            ->select("id", "product_id", "qty")
            ->get();
        }


        if ($cartProducts->count() == 0) {
            $notification = trans("Your shopping cart is empty");

            return response()->json(["message" => $notification], 403);
        }

        foreach ($cartProducts as $index => $cartProduct) {
            $variantPrice = 0;

            if ($cartProduct->variants) {
                foreach ($cartProduct->variants as $item_index => $var_item) {
                    $item = ProductVariantItem::find(
                        $var_item->variant_item_id
                    );

                    if ($item) {
                        $variantPrice += $item->price;
                    }
                }
            }

            $product = Product::select(
                "id",
                "price",
                "offer_price",
                "weight"
            )->find($cartProduct->product_id);

            $price = $product->offer_price
                ? $product->offer_price
                : $product->price;

            $price = $price + $variantPrice;
            $weight = $product->weight;
            $weight = $weight * $cartProduct->qty;
            $productWeight += $weight;
            $isFlashSale = FlashSaleProduct::where([
                "product_id" => $product->id,
                "status" => 1,
            ])->first();

            $today = date("Y-m-d H:i:s");

            if ($isFlashSale) {
                $flashSale = FlashSale::first();
                if ($flashSale->status == 1) {
                    if ($today <= $flashSale->end_time) {
                        $offerPrice = ($flashSale->offer / 100) * $price;

                        $price = $price - $offerPrice;
                    }
                }
            }

            $price = $price * $cartProduct->qty;
            $total_price += $price;
        }

        // calculate coupon coast

        if ($request_coupon) {
            $coupon = Coupon::where([
                "code" => $request_coupon,
                "status" => 1,
            ])->first();

            if ($coupon) {
                if ($coupon->expired_date >= date("Y-m-d")) {
                    if ($coupon->apply_qty < $coupon->max_quantity) {
                        if ($coupon->offer_type == 1) {
                            $couponAmount = $coupon->discount;

                            $couponAmount =
                                ($couponAmount / 100) * $total_price;
                        } elseif ($coupon->offer_type == 2) {
                            $couponAmount = $coupon->discount;
                        }

                        $coupon_price = $couponAmount;
                        $qty = $coupon->apply_qty;
                        $qty = $qty + 1;
                        $coupon->apply_qty = $qty;
                        $coupon->save();
                    }
                }
            }
        }

        $shipping = Shipping::find($request_shipping_method_id);

        if (!$shipping) {
            return response()->json(
                ["message" => trans("Shipping method not found")],
                403
            );
        }

        if ($shipping->shipping_fee == 0) {
            $shipping_fee = 0;
        } else {
            $shipping_fee = $shipping->shipping_fee;
        }
        

        $total_price = $total_price - $coupon_price + $shipping_fee;

        // code for tax calculation start
        $setting = Setting::first();
        $tax_rate = $setting->tax_rate;
        $taxAmount = (($total_price * $tax_rate) / 100 );
        $total_price += $taxAmount;
        //code for tax calculations end

        $total_price = str_replace(
            ['\'', '"', ",", ";", "<", ">"],
            "",
            $total_price
        );

        $total_price = number_format($total_price, 2, ".", "");
        $arr = [];
        $arr["total_price"] = $total_price;
        $arr["coupon_price"] = $coupon_price;
        $arr["shipping_fee"] = $shipping_fee;
        $arr["tax_fee"] = $taxAmount;
        $arr["productWeight"] = $productWeight;
        $arr["shipping"] = $shipping;
        return $arr;
    }

    public function orderStore(
        $user,
        $orderId,
        $total_price,
        $totalProduct,
        $payment_method,
        $transaction_id,
        $paymetn_status,
        $shipping,
        $shipping_fee,
        $coupon_price,
        $cash_on_delivery,
        $billing_address_id,
        $shipping_address_id,
        $request_buy_now_productid
    ) {
        if(!is_null($request_buy_now_productid)){
            $cartProducts = ShoppingCart::with("product", "variants.variantItem")
            ->where("user_id", $user->id)
            ->where('id', $request_buy_now_productid)
            ->select("id", "product_id", "qty")
            ->get();

        }else{
            $cartProducts = ShoppingCart::with("product", "variants.variantItem")
            ->where("user_id", $user->id)
            ->select("id", "product_id", "qty")
            ->get();
        }

        if ($cartProducts->count() == 0) {
            $notification = trans("Your shopping cart is empty");

            return response()->json(["message" => $notification], 403);
        }

        $order = new Order();
        $order->order_id = $orderId;
        $order->user_id = $user->id;
        $order->total_amount = $total_price;
        $order->product_qty = $totalProduct;
        $order->payment_method = $payment_method;
        $order->transection_id = $transaction_id;
        $order->payment_status = $paymetn_status;
        $order->shipping_method = $shipping->shipping_rule;
        $order->shipping_cost = $shipping_fee;
        $order->gst_cost = $gst_fee;
        $order->coupon_coast = $coupon_price;
        $order->order_status = 0;
        $order->cash_on_delivery = $cash_on_delivery;
        $order->save();
        $order_details = "";

        $setting = Setting::first();
        foreach ($cartProducts as $key => $cartProduct) {
            $variantPrice = 0;
            if ($cartProduct->variants) {
                foreach ($cartProduct->variants as $item_index => $var_item) {
                    $item = ProductVariantItem::find(
                        $var_item->variant_item_id
                    );
                    if ($item) {
                        $variantPrice += $item->price;
                    }
                }
            }

            // calculate product price

            $product = Product::select(
                "id",
                "price",
                "offer_price",
                "weight",
                "vendor_id",
                "qty",
                "name"
            )->find($cartProduct->product_id);

            $price = $product->offer_price
                ? $product->offer_price
                : $product->price;

            $price = $price + $variantPrice;
            $isFlashSale = FlashSaleProduct::where([
                "product_id" => $product->id,
                "status" => 1,
            ])->first();

            $today = date("Y-m-d H:i:s");
            if ($isFlashSale) {
                $flashSale = FlashSale::first();
                if ($flashSale->status == 1) {
                    if ($today <= $flashSale->end_time) {
                        $offerPrice = ($flashSale->offer / 100) * $price;

                        $price = $price - $offerPrice;
                    }
                }
            }

            // store ordre product

            $orderProduct = new OrderProduct();
            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $cartProduct->product_id;
            $orderProduct->seller_id = $product->vendor_id;
            $orderProduct->product_name = $product->name;
            $orderProduct->unit_price = $price;
            $orderProduct->qty = $cartProduct->qty;
            $orderProduct->save();

            // update product stock

            $qty = $product->qty - $cartProduct->qty;
            $product->qty = $qty;
            $product->save();

            // store prouct variant

            // return $cartProduct->variants;
            foreach ($cartProduct->variants as $index => $variant) {
                $item = ProductVariantItem::find($variant->variant_item_id);
                $productVariant = new OrderProductVariant();
                $productVariant->order_product_id = $orderProduct->id;
                $productVariant->product_id = $cartProduct->product_id;
                $productVariant->variant_name = $item->product_variant_name;
                $productVariant->variant_value = $item->name;
                $productVariant->save();
            }

            $order_details .= "Product: " . $product->name . "<br>";
            $order_details .= "Quantity: " . $cartProduct->qty . "<br>";
            $order_details .=
                "Price: " .
                $setting->currency_icon .
                $cartProduct->qty * $price .
                "<br>";
        }

        // store shipping and billing address

        $billing = Address::find($billing_address_id);
        $shipping = Address::find($shipping_address_id);
        $orderAddress = new OrderAddress();
        $orderAddress->order_id = $order->id;
        $orderAddress->billing_name = $billing->name;
        $orderAddress->billing_email = $billing->email;
        $orderAddress->billing_phone = $billing->phone;
        $orderAddress->billing_address = $billing->address;
        $orderAddress->billing_country = $billing->country->name;
        $orderAddress->billing_state = $billing->countryState->name;
        $orderAddress->billing_city = $billing->city->name;
        $orderAddress->billing_address_type = $billing->type;
        $orderAddress->shipping_name = $shipping->name;
        $orderAddress->shipping_email = $shipping->email;
        $orderAddress->shipping_phone = $shipping->phone;
        $orderAddress->shipping_address = $shipping->address;
        $orderAddress->shipping_country = $shipping->country->name;
        $orderAddress->shipping_state = $shipping->countryState->name;
        $orderAddress->shipping_city = $shipping->city->name;
        $orderAddress->shipping_address_type = $shipping->type;
        $orderAddress->save();

        foreach ($cartProducts as $cartProduct) {
            ShoppingCartVariant::where(
                "shopping_cart_id",
                $cartProduct->id
            )->delete();

            $cartProduct->delete();
        }

        $arr = [];
        $arr["order"] = $order;
        $arr["order_details"] = $order_details;
        return $arr;
    }

    public function sendOrderSuccessMail(
        $user,
        $total_price,
        $payment_method,
        $payment_status,
        $order,
        $order_details
    ) {
        $setting = Setting::first();
        MailHelper::setMailConfig();
        $template = EmailTemplate::where("id", 6)->first();
        $subject = $template->subject;
        $message = $template->description;
        $message = str_replace("{{user_name}}", $user->name, $message);

        $message = str_replace(
            "{{total_amount}}",
            $setting->currency_icon . $total_price,
            $message
        );

        $message = str_replace("{{payment_method}}", $payment_method, $message);
        $message = str_replace("{{payment_status}}", "Success", $message);
        $message = str_replace("{{order_status}}", "Pending", $message);
        $message = str_replace(
            "{{order_date}}",
            $order->created_at->format("d F, Y"),
            $message
        );

        $message = str_replace("{{order_detail}}", $order_details, $message);
        Mail::to($user->email)->send(new OrderSuccessfully($message, $subject));

        $this->sendOrderSuccessSms($user, $order);
    }

    public function sendOrderSuccessSms($user, $order){
        $setting = Setting::first();
        $template=SmsTemplate::where('id',3)->first();
        $message=$template->description;
        $message = str_replace('{{user_name}}',$user->name,$message);
        $message = str_replace('{{order_id}}',$order->order_id,$message);
        $message = str_replace('{{frontend_url}}',$setting->frontend_url,$message);

        $twilio = TwilioSms::first();
        if($twilio->enable_order_confirmation_sms == 1){
            if($user->phone){
                try{
                    $account_sid = $twilio->account_sid;
                    $auth_token = $twilio->auth_token;
                    $twilio_number = $twilio->twilio_phone_number;
                    $recipients = $user->phone;
                    $client = new Client($account_sid, $auth_token);
                    $messageTwilio = $client->messages
                            ->create($recipients,
                                array(
                                    'from' => $twilio_number, 
                                    'body' => $message
                                ));
                }catch(Exception $ex){

                }
            }
        }
    }


}
