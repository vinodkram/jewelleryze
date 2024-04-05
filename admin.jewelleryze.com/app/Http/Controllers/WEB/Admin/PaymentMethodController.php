<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaypalPayment;
use App\Models\StripePayment;
use App\Models\RazorpayPayment;
use App\Models\CcavenuePayment;
use App\Models\Flutterwave;
use App\Models\BankPayment;
use App\Models\PaystackAndMollie;
use App\Models\InstamojoPayment;
use App\Models\CurrencyCountry;
use App\Models\Currency;
use App\Models\Setting;
use App\Models\PaymongoPayment;
use App\Models\SslcommerzPayment;
use Image;
use File;
class PaymentMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $paypal = PaypalPayment::first();
        $stripe = StripePayment::first();
        $razorpay = RazorpayPayment::first();
        $ccavenue = CcavenuePayment::first();
        $flutterwave = Flutterwave::first();
        $bank = BankPayment::first();
        $paystackAndMollie = PaystackAndMollie::first();
        $instamojo = InstamojoPayment::first();
        $paymongo = PaymongoPayment::first();
        $sslcommerz = SslcommerzPayment::first();

        $countires = CurrencyCountry::orderBy('name','asc')->get();
        $currencies = Currency::orderBy('name','asc')->get();
        $setting = Setting::first();

        return view('admin.payment_method', compact('paypal','stripe','razorpay','ccavenue','bank','paystackAndMollie','flutterwave','instamojo','countires','currencies','setting','paymongo','sslcommerz'));

    }

    public function updatePaypal(Request $request){

        $rules = [
            'paypal_client_id' => 'required',
            'paypal_secret_key' => 'required',
            'account_mode' => 'required',
            'country_name' => 'required',
            'currency_name' => 'required',
            'currency_rate' => 'required',
        ];
        $customMessages = [
            'paypal_client_id.required' => trans('admin_validation.Paypal client id is required'),
            'paypal_secret_key.required' => trans('admin_validation.Paypal secret key is required'),
            'account_mode.required' => trans('admin_validation.Account mode is required'),
            'country_name.required' => trans('admin_validation.Country name is required'),
            'currency_name.required' => trans('admin_validation.Currency name is required'),
            'currency_rate.required' => trans('admin_validation.Currency rate is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $paypal = PaypalPayment::first();
        $paypal->client_id = $request->paypal_client_id;
        $paypal->secret_id = $request->paypal_secret_key;
        $paypal->account_mode = $request->account_mode;
        $paypal->country_code = $request->country_name;
        $paypal->currency_code = $request->currency_name;
        $paypal->currency_rate = $request->currency_rate;
        $paypal->status = $request->status ? 1 : 0;
        $paypal->save();

        $notification=trans('admin_validation.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function updateStripe(Request $request){

        $rules = [
            'stripe_key' => 'required',
            'stripe_secret' => 'required',
            'country_name' => 'required',
            'currency_name' => 'required',
            'currency_rate' => 'required',
        ];
        $customMessages = [
            'stripe_key.required' => trans('admin_validation.Stripe key is required'),
            'stripe_secret.required' => trans('admin_validation.Stripe secret is required'),
            'country_name.required' => trans('admin_validation.Country name is required'),
            'currency_name.required' => trans('admin_validation.Currency name is required'),
            'currency_rate.required' => trans('admin_validation.Currency rate is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $stripe = StripePayment::first();
        $stripe->stripe_key = $request->stripe_key;
        $stripe->stripe_secret = $request->stripe_secret;
        $stripe->country_code = $request->country_name;
        $stripe->currency_code = $request->currency_name;
        $stripe->currency_rate = $request->currency_rate;
        $stripe->status = $request->status ? 1 : 0;
        $stripe->save();

        $notification=trans('admin_validation.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function updateRazorpay(Request $request){
        $rules = [
            'razorpay_key' => 'required',
            'razorpay_secret' => 'required',
            'name' => 'required',
            'description' => 'required',
            'currency_rate' => 'required',
            'theme_color' => 'required',
            'currency_name' => 'required',
            'country_name' => 'required',
        ];
        $customMessages = [
            'razorpay_key.required' => trans('admin_validation.Razorpay key is required'),
            'razorpay_secret.required' => trans('admin_validation.Razorpay secret is required'),
            'name.required' => trans('admin_validation.Name is required'),
            'description.required' => trans('admin_validation.Description is required'),
            'country_name.required' => trans('admin_validation.Country name is required'),
            'currency_name.required' => trans('admin_validation.Currency name is required'),
            'currency_rate.required' => trans('admin_validation.Currency rate is required'),
            'theme_color.required' => trans('admin_validation.Theme Color is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $razorpay = RazorpayPayment::first();
        $razorpay->key = $request->razorpay_key;
        $razorpay->secret_key = $request->razorpay_secret;
        $razorpay->name = $request->name;
        $razorpay->currency_rate = $request->currency_rate;
        $razorpay->description = $request->description;
        $razorpay->color = $request->theme_color;
        $razorpay->country_code = $request->country_name;
        $razorpay->currency_code = $request->currency_name;
        $razorpay->status = $request->status ? 1 : 0;
        $razorpay->save();

        if($request->image){
            $old_image=$razorpay->image;
            $image=$request->image;
            $extention=$image->getClientOriginalExtension();
            $image_name= 'razorpay-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name='uploads/website-images/'.$image_name;
            Image::make($image)
                ->save(public_path().'/'.$image_name);
            $razorpay->image=$image_name;
            $razorpay->save();
            if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
        }

        $notification=trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function updateCcavenue(Request $request){
        $rules = [
            'account_mode' => 'required',
            'ccavenue_merchand_id' => 'required',
            'ccavenue_key' => 'required',
            'ccavenue_secret' => 'required',
            'name' => 'required',
            'description' => 'required',
            'currency_rate' => 'required',
            'currency_name' => 'required',
            'country_name' => 'required',
        ];
        $customMessages = [
            'account_mode.required' => trans('admin_validation.Account mode is required'),
            'ccavenue_merchand_id.required' => trans('admin_validation.CCAvenue_merchant_is_required'),
            'ccavenue_key.required' => trans('admin_validation.CCAvenue_key_is_required'),
            'ccavenue_secret.required' => trans('admin_validation.CCAvenue_secret_is_required'),
            'name.required' => trans('admin_validation.Name is required'),
            'description.required' => trans('admin_validation.Description is required'),
            'country_name.required' => trans('admin_validation.Country name is required'),
            'currency_name.required' => trans('admin_validation.Currency name is required'),
            'currency_rate.required' => trans('admin_validation.Currency rate is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $ccavenue = CcavenuePayment::first();
        $ccavenue->account_mode = $request->account_mode;
        $ccavenue->merchand_id = $request->ccavenue_merchand_id;
        $ccavenue->key = $request->ccavenue_key;
        $ccavenue->secret_key = $request->ccavenue_secret;
        $ccavenue->sandbox_key = $request->ccavenue_sandbox_key;
        $ccavenue->sandbox_secret_key = $request->ccavenue_sandbox_secret;
        $ccavenue->name = $request->name;
        $ccavenue->currency_rate = $request->currency_rate;
        $ccavenue->description = $request->description;
        $ccavenue->color = $request->theme_color;
        $ccavenue->country_code = $request->country_name;
        $ccavenue->currency_code = $request->currency_name;
        $ccavenue->status = $request->status ? 1 : 0;
        $ccavenue->save();

        if($request->image){
            $old_image=$ccavenue->image;
            $image=$request->image;
            $extention=$image->getClientOriginalExtension();
            $image_name= 'ccavenue-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name='uploads/website-images/'.$image_name;
            Image::make($image)
                ->save(public_path().'/'.$image_name);
            $ccavenue->image=$image_name;
            $ccavenue->save();
            if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
        }

        $notification=trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }    

    public function updateBank(Request $request){
        $rules = [
            'account_info' => 'required'
        ];
        $customMessages = [
            'account_info.required' => trans('admin_validation.Account information is required'),
        ];
        $this->validate($request, $rules,$customMessages);
        $bank = BankPayment::first();
        $bank->account_info = $request->account_info;
        $bank->status = $request->status ? 1 : 0;
        $bank->save();

        $notification=trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function updateMollie(Request $request){
        $rules = [
            'mollie_key' => 'required',
            'mollie_currency_rate' => 'required',
            'mollie_country_name' => 'required',
            'mollie_currency_name' => 'required'
        ];

        $customMessages = [
            'mollie_key.required' => trans('admin_validation.Mollie key is required'),
            'mollie_country_name.required' => trans('admin_validation.Country name is required'),
            'mollie_currency_name.required' => trans('admin_validation.Currency name is required'),
            'mollie_currency_rate.required' => trans('admin_validation.Currency rate is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $mollie = PaystackAndMollie::first();
        $mollie->mollie_key = $request->mollie_key;
        $mollie->mollie_currency_rate = $request->mollie_currency_rate;
        $mollie->mollie_currency_code = $request->mollie_currency_name;
        $mollie->mollie_country_code = $request->mollie_country_name;
        $mollie->mollie_status = $request->status ? 1 : 0;
        $mollie->save();

        $notification=trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function updatePayStack(Request $request){
        $rules = [
            'paystack_public_key' => 'required',
            'paystack_secret_key' => 'required',
            'paystack_currency_rate' => 'required',
            'paystack_currency_name' => 'required',
            'paystack_country_name' => 'required'
        ];

        $customMessages = [
            'paystack_public_key.required' => trans('admin_validation.Paystack public key is required'),
            'paystack_secret_key.required' => trans('admin_validation.Paystack secret key is required'),
            'paystack_currency_rate.required' => trans('admin_validation.Currency rate is required'),
            'paystack_currency_name.required' => trans('admin_validation.Currency name is required'),
            'paystack_country_name.required' => trans('admin_validation.Country rate is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $paystact = PaystackAndMollie::first();
        $paystact->paystack_public_key = $request->paystack_public_key;
        $paystact->paystack_secret_key = $request->paystack_secret_key;
        $paystact->paystack_currency_code = $request->paystack_currency_name;
        $paystact->paystack_country_code = $request->paystack_country_name;
        $paystact->paystack_currency_rate = $request->paystack_currency_rate;
        $paystact->paystack_status = $request->status ? 1 : 0;
        $paystact->save();

        $notification=trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function updateflutterwave(Request $request){
        $rules = [
            'public_key' => 'required',
            'secret_key' => 'required',
            'title' => 'required',
            'currency_rate' => 'required',
            'currency_name' => 'required',
            'country_name' => 'required',
        ];
        $customMessages = [
            'title.required' => trans('admin_validation.Title is required'),
            'public_key.required' => trans('admin_validation.Public key is required'),
            'secret_key.required' => trans('admin_validation.Secret key is required'),
            'currency_rate.required' => trans('admin_validation.Currency rate is required'),
            'currency_name.required' => trans('admin_validation.Currency name is required'),
            'country_name.required' => trans('admin_validation.Country name is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $flutterwave = Flutterwave::first();
        $flutterwave->public_key = $request->public_key;
        $flutterwave->secret_key = $request->secret_key;
        $flutterwave->title = $request->title;
        $flutterwave->currency_rate = $request->currency_rate;
        $flutterwave->country_code = $request->country_name;
        $flutterwave->currency_code = $request->currency_name;
        $flutterwave->status = $request->status ? 1 : 0;
        $flutterwave->save();

        if($request->image){
            $old_image=$flutterwave->logo;
            $image=$request->image;
            $extention=$image->getClientOriginalExtension();
            $image_name= 'flutterwave-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name='uploads/website-images/'.$image_name;
            Image::make($image)
                ->save(public_path().'/'.$image_name);
            $flutterwave->logo=$image_name;
            $flutterwave->save();
            if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
        }

        $notification=trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }


    public function updateInstamojo(Request $request){
        $rules = [
            'account_mode' => 'required',
            'api_key' => 'required',
            'auth_token' => 'required',
            'currency_rate' => 'required',
        ];
        $customMessages = [
            'account_mode.required' => trans('admin_validation.Account mode is required'),
            'api_key.required' => trans('admin_validation.Api key is required'),
            'currency_rate.required' => trans('admin_validation.Currency rate is required'),
            'auth_token.required' => trans('admin_validation.Auth token is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $instamojo = InstamojoPayment::first();
        $instamojo->account_mode = $request->account_mode;
        $instamojo->api_key = $request->api_key;
        $instamojo->auth_token = $request->auth_token;
        $instamojo->currency_rate = $request->currency_rate;
        $instamojo->status = $request->status ? 1 : 0;
        $instamojo->save();

        $notification=trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function updateCashOnDelivery(Request $request){
        $bank = BankPayment::first();
        if($bank->cash_on_delivery_status==1){
            $bank->cash_on_delivery_status=0;
            $bank->save();
            $message= trans('admin_validation.Inactive Successfully');
        }else{
            $bank->cash_on_delivery_status=1;
            $bank->save();
            $message= trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }


    public function updateSslcommerz(Request $request){
        $rules = [
            'store_id' => $request->status ? 'required' : '',
            'store_password' => $request->status ? 'required' : '',
            'currency_rate' => $request->status ? 'required|numeric' : '',
            'currency_name' => $request->status ? 'required' : '',
            'country_name' => $request->status ? 'required' : '',
        ];
        $customMessages = [
            'store_id.required' => trans('admin_validation.Store id is required'),
            'store_password.required' => trans('admin_validation.Store password is required'),
            'currency_rate.required' => trans('admin_validation.Currency rate is required'),
            'currency_name.required' => trans('admin_validation.Currency name is required'),
            'country_name.required' => trans('admin_validation.Country name is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $sslcommerz = SslcommerzPayment::first();
        $sslcommerz->mode = $request->account_mode;
        $sslcommerz->store_id = $request->store_id;
        $sslcommerz->store_password = $request->store_password;
        $sslcommerz->currency_rate = $request->currency_rate;
        $sslcommerz->country_code = $request->country_name;
        $sslcommerz->currency_code = $request->currency_name;
        $sslcommerz->status = $request->status ? 1 : 0;
        $sslcommerz->save();

        $notification=trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }



}
