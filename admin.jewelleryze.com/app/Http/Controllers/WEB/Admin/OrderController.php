<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Setting;
use App\Models\OrderProduct;
use App\Models\OrderProductVariant;
use App\Models\OrderAddress;
use App\Helpers\MailHelper;
use App\Models\EmailTemplate;
use App\Models\SmsTemplate;
use App\Models\TwilioSms;
use Twilio\Rest\Client;
use App\Mail\OrderSuccessfully;
use Mail;
class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        
        // $orders = Order::with('user')->orderBy('id','desc')->get();
        $orders = Order::with('user')->where('order_deleted','!=',1);
        $searchTxt = '';
        $paymentStatus = '';
        $orderStatus = '';
        $paymentMethod = '';
        if($request->has('search')){
            $searchTxt = $request->get('search');
        }
        if($request->has('orderStatus')){
            $orderStatus = $request->get('orderStatus');
        }
        if($request->has('paymentStatus')){
            $paymentStatus = $request->get('paymentStatus');
        }
        if($request->has('paymentMethod')){
            $paymentMethod = $request->get('paymentMethod');
        }
        
        if ($searchTxt != "") 
        {
            $orders = $orders->leftJoin('users', 'users.id', '=', 'orders.user_id');
            $orders = $orders->where(function($query)  use ($searchTxt) {
                $query->where('order_id','=',$searchTxt)
                    ->orWhere('users.name','LIKE',"%" . $searchTxt . "%")
                    ->orWhere('users.email','LIKE',"%" . $searchTxt . "%")
                    ->orWhere('users.phone','LIKE',"%" . $searchTxt . "%");
            });
        }
        if ($orderStatus != "") 
        {
            $orders = $orders->where('order_status','=',$orderStatus);
        }
        if ($paymentMethod != "") 
        {
            $orders = $orders->where('payment_method','=',$paymentMethod);
        }
        if ($paymentStatus != "") 
        {
            $orders = $orders->where('payment_status','=',$paymentStatus);
        }
        $orders = $orders->orderBy('orders.id','desc')->paginate(15)->withQueryString();
        $page = 0;
        if($request->page){
            $page = ($request->page-1);
        }
        $perPage = 15;
        if($request->perPage){
            $perPage = $request->perPage;
        }
        $title = trans('admin_validation.All Orders');
        $setting = Setting::first();
        return view('admin.order', compact('orders','title','setting','paymentStatus','orderStatus','searchTxt','paymentMethod','page','perPage'));

    }

    public function pendingOrder(Request $request){
        $orders = Order::with('user')->orderBy('id','desc')->where('order_status',0);
        $title = trans('admin_validation.Pending Orders');
        $setting = Setting::first();
        $searchTxt = '';
        $paymentStatus = '';
        $orderStatus = '0';
        $paymentMethod = '';
        if($request->has('search')){
            $searchTxt = $request->get('search');
        }
        if($request->has('paymentStatus')){
            $paymentStatus = $request->get('paymentStatus');
        }
        if($request->has('paymentMethod')){
            $paymentMethod = $request->get('paymentMethod');
        }
        
        if ($searchTxt != "") 
        {
            $orders = $orders->leftJoin('users', 'users.id', '=', 'orders.user_id');
            $orders = $orders->where(function($query)  use ($searchTxt) {
                $query->where('order_id','=',$searchTxt)
                    ->orWhere('users.name','LIKE',"%" . $searchTxt . "%")
                    ->orWhere('users.email','LIKE',"%" . $searchTxt . "%")
                    ->orWhere('users.phone','LIKE',"%" . $searchTxt . "%");
            });
        }
        if ($orderStatus != "") 
        {
            $orders = $orders->where('order_status','=',$orderStatus);
        }
        if ($paymentMethod != "") 
        {
            $orders = $orders->where('payment_method','=',$paymentMethod);
        }
        if ($paymentStatus != "") 
        {
            $orders = $orders->where('payment_status','=',$paymentStatus);
        }
        $orders = $orders->orderBy('orders.id','desc')->paginate(15)->withQueryString();
        $page = 0;
        if($request->page){
            $page = ($request->page-1);
        }
        $perPage = 15;
        if($request->perPage){
            $perPage = $request->perPage;
        }
        return view('admin.order', compact('orders','title','setting','paymentStatus','orderStatus','searchTxt','paymentMethod','page','perPage'));
    }

    public function pregressOrder(Request $request){
        $orders = Order::with('user')->orderBy('id','desc')->where('order_status',1);
        $title = trans('admin_validation.Progress Orders');
        $setting = Setting::first();
        $searchTxt = '';
        $paymentStatus = '';
        $orderStatus = '1';
        $paymentMethod = '';
        if($request->has('search')){
            $searchTxt = $request->get('search');
        }
        if($request->has('paymentStatus')){
            $paymentStatus = $request->get('paymentStatus');
        }
        if($request->has('paymentMethod')){
            $paymentMethod = $request->get('paymentMethod');
        }
        
        if ($searchTxt != "") 
        {
            $orders = $orders->leftJoin('users', 'users.id', '=', 'orders.user_id');
            $orders = $orders->where(function($query)  use ($searchTxt) {
                $query->where('order_id','=',$searchTxt)
                    ->orWhere('users.name','LIKE',"%" . $searchTxt . "%")
                    ->orWhere('users.email','LIKE',"%" . $searchTxt . "%")
                    ->orWhere('users.phone','LIKE',"%" . $searchTxt . "%");
            });
        }
        if ($paymentMethod != "") 
        {
            $orders = $orders->where('payment_method','=',$paymentMethod);
        }
        if ($paymentStatus != "") 
        {
            $orders = $orders->where('payment_status','=',$paymentStatus);
        }
        $orders = $orders->orderBy('orders.id','desc')->paginate(15)->withQueryString();
        $page = 0;
        if($request->page){
            $page = ($request->page-1);
        }
        $perPage = 15;
        if($request->perPage){
            $perPage = $request->perPage;
        }
        return view('admin.order', compact('orders','title','setting','paymentStatus','orderStatus','searchTxt','paymentMethod','page','perPage'));
    }

    public function deliveredOrder(Request $request){
        $orders = Order::with('user')->orderBy('id','desc')->where('order_status',2);
        $title = trans('admin_validation.Delivered Orders');
        $setting = Setting::first();
        $searchTxt = '';
        $paymentStatus = '';
        $orderStatus = '2';
        $paymentMethod = '';
        if($request->has('search')){
            $searchTxt = $request->get('search');
        }
        if($request->has('paymentStatus')){
            $paymentStatus = $request->get('paymentStatus');
        }
        if($request->has('paymentMethod')){
            $paymentMethod = $request->get('paymentMethod');
        }
        
        if ($searchTxt != "") 
        {
            $orders = $orders->leftJoin('users', 'users.id', '=', 'orders.user_id');
            $orders = $orders->where(function($query)  use ($searchTxt) {
                $query->where('order_id','=',$searchTxt)
                    ->orWhere('users.name','LIKE',"%" . $searchTxt . "%")
                    ->orWhere('users.email','LIKE',"%" . $searchTxt . "%")
                    ->orWhere('users.phone','LIKE',"%" . $searchTxt . "%");
            });
        }
        if ($paymentMethod != "") 
        {
            $orders = $orders->where('payment_method','=',$paymentMethod);
        }
        if ($paymentStatus != "") 
        {
            $orders = $orders->where('payment_status','=',$paymentStatus);
        }
        $orders = $orders->orderBy('orders.id','desc')->paginate(15)->withQueryString();
        $page = 0;
        if($request->page){
            $page = ($request->page-1);
        }
        $perPage = 15;
        if($request->perPage){
            $perPage = $request->perPage;
        }
        return view('admin.order', compact('orders','title','setting','paymentStatus','orderStatus','searchTxt','paymentMethod','page','perPage'));
    }

    public function completedOrder(Request $request){
        $orders = Order::with('user')->orderBy('id','desc')->where('order_status',3);
        $title = trans('admin_validation.Completed Orders');
        $setting = Setting::first();
        $searchTxt = '';
        $paymentStatus = '';
        $orderStatus = '3';
        $paymentMethod = '';
        if($request->has('search')){
            $searchTxt = $request->get('search');
        }
        if($request->has('paymentStatus')){
            $paymentStatus = $request->get('paymentStatus');
        }
        if($request->has('paymentMethod')){
            $paymentMethod = $request->get('paymentMethod');
        }
        
        if ($searchTxt != "") 
        {
            $orders = $orders->leftJoin('users', 'users.id', '=', 'orders.user_id');
            $orders = $orders->where(function($query)  use ($searchTxt) {
                $query->where('order_id','=',$searchTxt)
                    ->orWhere('users.name','LIKE',"%" . $searchTxt . "%")
                    ->orWhere('users.email','LIKE',"%" . $searchTxt . "%")
                    ->orWhere('users.phone','LIKE',"%" . $searchTxt . "%");
            });
        }
        if ($paymentMethod != "") 
        {
            $orders = $orders->where('payment_method','=',$paymentMethod);
        }
        if ($paymentStatus != "") 
        {
            $orders = $orders->where('payment_status','=',$paymentStatus);
        }
        $orders = $orders->orderBy('orders.id','desc')->paginate(15)->withQueryString();
        $page = 0;
        if($request->page){
            $page = ($request->page-1);
        }
        $perPage = 15;
        if($request->perPage){
            $perPage = $request->perPage;
        }
        return view('admin.order', compact('orders','title','setting','paymentStatus','orderStatus','searchTxt','paymentMethod','page','perPage'));
    }

    public function declinedOrder(Request $request){
        $orders = Order::with('user')->orderBy('id','desc')->where('order_status',4);
        $title = trans('admin_validation.Declined Orders');
        $setting = Setting::first();
        $searchTxt = '';
        $paymentStatus = '';
        $orderStatus = '4';
        $paymentMethod = '';
        if($request->has('search')){
            $searchTxt = $request->get('search');
        }
        if($request->has('paymentStatus')){
            $paymentStatus = $request->get('paymentStatus');
        }
        if($request->has('paymentMethod')){
            $paymentMethod = $request->get('paymentMethod');
        }
        
        if ($searchTxt != "") 
        {
            $orders = $orders->leftJoin('users', 'users.id', '=', 'orders.user_id');
            $orders = $orders->where(function($query)  use ($searchTxt) {
                $query->where('order_id','=',$searchTxt)
                    ->orWhere('users.name','LIKE',"%" . $searchTxt . "%")
                    ->orWhere('users.email','LIKE',"%" . $searchTxt . "%")
                    ->orWhere('users.phone','LIKE',"%" . $searchTxt . "%");
            });
        }
        if ($paymentMethod != "") 
        {
            $orders = $orders->where('payment_method','=',$paymentMethod);
        }
        if ($paymentStatus != "") 
        {
            $orders = $orders->where('payment_status','=',$paymentStatus);
        }
        $orders = $orders->orderBy('orders.id','desc')->paginate(15)->withQueryString();
        $page = 0;
        if($request->page){
            $page = ($request->page-1);
        }
        $perPage = 15;
        if($request->perPage){
            $perPage = $request->perPage;
        }
        return view('admin.order', compact('orders','title','setting','paymentStatus','orderStatus','searchTxt','paymentMethod','page','perPage'));
    }

    public function cashOnDelivery(Request $request){
        $orders = Order::with('user')->orderBy('id','desc')->where('cash_on_delivery',1);
        $title = trans('admin_validation.Cash On Delivery');
        $setting = Setting::first();
        $searchTxt = '';
        $paymentStatus = '';
        $orderStatus = '';
        $paymentMethod = 'Cash on Delivery';
        if($request->has('search')){
            $searchTxt = $request->get('search');
        }
        if($request->has('paymentStatus')){
            $paymentStatus = $request->get('paymentStatus');
        }
        if($request->has('paymentMethod')){
            $paymentMethod = $request->get('paymentMethod');
        }
        
        if ($searchTxt != "") 
        {
            $orders = $orders->leftJoin('users', 'users.id', '=', 'orders.user_id');
            $orders = $orders->where(function($query)  use ($searchTxt) {
                $query->where('order_id','=',$searchTxt)
                    ->orWhere('users.name','LIKE',"%" . $searchTxt . "%")
                    ->orWhere('users.email','LIKE',"%" . $searchTxt . "%")
                    ->orWhere('users.phone','LIKE',"%" . $searchTxt . "%");
            });
        }
        if ($orderStatus != "") 
        {
            $orders = $orders->where('order_status','=',$orderStatus);
        }
        if ($paymentStatus != "") 
        {
            $orders = $orders->where('payment_status','=',$paymentStatus);
        }
        $orders = $orders->orderBy('orders.id','desc')->paginate(15)->withQueryString();
        $page = 0;
        if($request->page){
            $page = ($request->page-1);
        }
        $perPage = 15;
        if($request->perPage){
            $perPage = $request->perPage;
        }
        return view('admin.order', compact('orders','title','setting','paymentStatus','orderStatus','searchTxt','paymentMethod','page','perPage'));
    }

    public function show($id){
        $setting = Setting::first();
        $frontend_url = $setting->frontend_url;
        $frontend_view = $frontend_url.'products/';
        $order = Order::with('user','orderProducts.orderProductVariants','orderAddress','orderProducts.product')->find($id);
        // dd($order);
		$user = User::where('id',$order->user_id)->first();
        $setting = Setting::first();
        return view('admin.show_order',compact('order','setting','user','frontend_view'));
    }

    public function updateOrderStatus(Request $request , $id){
        $setting = Setting::first();
        $enable_phone_required = $setting->phone_number_required;

        $rules = [
            'order_status' => 'required',
            'payment_status' => 'required',
        ];
        $this->validate($request, $rules);


        $order = Order::find($id);
        $user = User::where('id', $order->user_id)->first();
        if($request->order_status == 0){
            $order->order_status = 0;
            $order->save();
            $orderStatusName = "Pending";
        }else if($request->order_status == 1){
            $order->order_status = 1;
            $order->order_approval_date = date('Y-m-d');
            $order->save();
            $orderStatusName = "In-Progress";
        }else if($request->order_status == 2){
            $order->order_status = 2;
            $order->order_delivered_date = date('Y-m-d');
            $order->save();
            $orderStatusName = "Delivered";
        }else if($request->order_status == 3){
            $order->order_status = 3;
            $order->order_completed_date = date('Y-m-d');
            $order->save();
            $orderStatusName = "Completed";
        }else if($request->order_status == 4){
            $order->order_status = 4;
            $order->order_declined_date = date('Y-m-d');
            $order->save();
            $orderStatusName = "Declined";
			// revert stock of each product start
            $orderProducts = OrderProduct::where('order_id',$id)->get();
            foreach($orderProducts as $orderProduct){
                if ($orderProduct->is_stock_reverted == "No"){
                    $orderProduct->is_stock_reverted = "Yes";
                    $orderProduct->save();
                    $item = Product::where('id',$orderProduct->product_id)->first();
                    $item->qty += $orderProduct->qty;
                    $item->save();
                }
            }
            // revert stock of each product end
        }

        if($request->payment_status == 0){
            $order->payment_status = 0;
            $order->save();
        }elseif($request->payment_status == 1){
            $order->payment_status = 1;
            $order->payment_approval_date = date('Y-m-d');
            $order->save();
        }

        $setting = Setting::first();
        MailHelper::setMailConfig();
        $template = EmailTemplate::where("id", 8)->first();
        $subject = $template->subject;
        $message = $template->description;
        $message = str_replace("{{user_name}}", $user->name, $message);
        $message = str_replace('{{order_id}}',$order->order_id,$message);
        $message = str_replace('{{order_status}}',$orderStatusName,$message);
        $message = str_replace('{{frontend_url}}',$setting->frontend_url,$message);

        Mail::to($user->email)->send(new OrderSuccessfully($message, $subject));


        if($enable_phone_required == 1){
            $template=SmsTemplate::where('id',4)->first();
            $message=$template->description;
            $message = str_replace('{{user_name}}',$user->name,$message);
            $message = str_replace('{{order_id}}',$order->order_id,$message);
            $message = str_replace('{{order_status}}',$orderStatusName,$message);
            $message = str_replace('{{frontend_url}}',$setting->frontend_url,$message);

            $twilio = TwilioSms::first();
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

        $notification = trans('admin_validation.Order Status Updated successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }


    public function destroy($id){
        $order = Order::find($id);
        //$order->delete();
        $order->order_deleted = "1";
        $order->order_deleted_date = date('Y-m-d');
        $order->save();
        $orderProducts = OrderProduct::where('order_id',$id)->get();
        //$orderAddress = OrderAddress::where('order_id',$id)->first();
        foreach($orderProducts as $orderProduct){
            // $OrderProductVariant = OrderProductVariant::where('order_product_id',$orderProduct->id)->delete();
            // $OrderProductVariant->order_deleted = "1";
            // $OrderProductVariant->order_deleted_date = date('Y-m-d');

            // $orderProduct->order_deleted = "1";
            // $orderProduct->order_deleted_date = date('Y-m-d');

			// revert stock of each product end
            if ($orderProduct->is_stock_reverted == "No"){
                $orderProduct->is_stock_reverted = "Yes";
                $orderProduct->save();
                $item = Product::where('id',$orderProduct->product_id)->first();
                $item->qty += $orderProduct->qty;
                $item->save();
            }
            // revert stock of each product end
        }

        // $orderAddress->order_deleted = "1";
        // $orderAddress->order_deleted_date = date('Y-m-d');
        

        $notification = trans('admin_validation.Delete successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.all-order')->with($notification);
    }
}
