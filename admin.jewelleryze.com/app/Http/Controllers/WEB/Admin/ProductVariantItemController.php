<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariantItem;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\Product;
use App\Models\Setting;
use App\Models\ShoppingCartVariant;
class ProductVariantItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        if($request->product_id){
            $product = Product::find($request->product_id);
            if($product){
                if($request->variant_id){
                    $variant = ProductVariant::find($request->variant_id);
                    if($variant){
                        if($variant->product_id == $product->id){
                            $variantItems = ProductVariantItem::with('product', 'variant')->where(['product_id' => $product->id , 'product_variant_id' => $variant->id])->get();
                            $setting = Setting::first();

                            return view('admin.variant_item',compact('variantItems','product','variant','setting'));

                        }else return $this->existingDataError();
                    }else return $this->existingDataError();
                }else return $this->existingDataError();
            }else return $this->existingDataError();
        }else return $this->existingDataError();
    }


    public function store(Request $request)
    {
        $variantItems = ProductVariantItem::where(['product_id' => $request->product_id , 'product_variant_id' => $request->variant_id])->count();

        $rules = [
            'name' => 'required',
            'product_id' => 'required',
            'variant_id' => 'required',
            'price' => 'required|numeric',
            'status' => 'required',
        ];

        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'product_id.required' => trans('admin_validation.Product is required'),
            'variant_id.required' => trans('admin_validation.Variant is required'),
            'price.required' => trans('admin_validation.Price is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        if($request->product_id){
            $product = Product::find($request->product_id);
            if($product){
                if($request->variant_id){
                    $variant = ProductVariant::find($request->variant_id);
                    if($variant){
                        if($variant->product_id == $product->id){
                            $variantItem = new ProductVariantItem();

                            $variantItem->product_id = $request->product_id;
                            $variantItem->product_variant_id = $request->variant_id;
                            $variantItem->name = $request->name;
                            $variantItem->price = $request->price;
                            $variantItem->product_variant_name = $variant->name;
                            $variantItem->status = $request->status;
                            $variantItem->save();

                            $notification = trans('admin_validation.Created Successfully');
                            $notification=array('messege'=>$notification,'alert-type'=>'success');
                            return redirect()->back()->with($notification);

                        }else return $this->existingDataError();
                    }else return $this->existingDataError();
                }else return $this->existingDataError();
            }else return $this->existingDataError();
        }else return $this->existingDataError();
    }

    public function update(Request $request,$variantItemId){
        $variantItems = ProductVariantItem::where(['product_id' => $request->product_id , 'product_variant_id' => $request->variant_id])->count();
        $rules = [
            'name' => 'required',
            'product_id' => 'required',
            'variant_id' => 'required',
            'price' => 'required|numeric',
            'status' => 'required',
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'product_id.required' => trans('admin_validation.Product is required'),
            'variant_id.required' => trans('admin_validation.Variant is required'),
            'price.required' => trans('admin_validation.Price is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        if($request->product_id){
            $product = Product::find($request->product_id);
            if($product){
                if($request->variant_id){
                    $variant = ProductVariant::find($request->variant_id);
                    if($variant){
                        if($variant->product_id == $product->id){
                            $variantItem = ProductVariantItem::find($variantItemId);
                            $variantItem->product_id = $request->product_id;
                            $variantItem->product_variant_id = $request->variant_id;
                            $variantItem->name = $request->name;
                            $variantItem->price = $request->price;
                            $variantItem->status = $request->status;
                            $variantItem->save();

                            $notification = trans('admin_validation.Update Successfully');
                            $notification=array('messege'=>$notification,'alert-type'=>'success');
                            return redirect()->back()->with($notification);

                        }else return $this->existingDataError();
                    }else return $this->existingDataError();
                }else return $this->existingDataError();
            }else return $this->existingDataError();
        }else return $this->existingDataError();
    }


    public function destroy($id)
    {
        $variantItem = ProductVariantItem::find($id);
        $product_variant_id = $variantItem->product_variant_id;
        if($variantItem){
            $product = Product::find($variantItem->product_id);
            $variant = ProductVariant::find($variantItem->product_variant_id);
            if($product->id == $variant->product_id){
                $variantItem->delete();
                ShoppingCartVariant::where('variant_item_id', $id)->delete();

                $notification = trans('admin_validation.Delete Successfully');
                $notification=array('messege'=>$notification,'alert-type'=>'success');
                return redirect()->back()->with($notification);
                return response()->json(['message' => $notification],200);
            }else return $this->existingDataError();
        }else return $this->existingDataError();
    }

    public function changeStatus($id){
        $variantItem = ProductVariantItem::find($id);
        if($variantItem->status == 1){
            $variantItem->status = 0;
            $variantItem->save();
            $message = trans('admin_validation.Inactive Successfully');
        }else{
            $variantItem->status = 1;
            $variantItem->save();
            $message = trans('admin_validation.Active Successfully');
        }

        return response()->json($message);
    }


    public function existingDataError(){
        $notification = trans('admin_validation.Something went wrong');
        $notification=array('messege'=>$notification,'alert-type'=>'error');
        return redirect()->route('admin.product.index')->with($notification);
    }


    public function show($id){
        $variantItem = ProductVariantItem::find($id);
        $product = Product::find($variantItem->product_id);
        $variant = ProductVariant::find($variantItem->product_variant_id);
        return response()->json(['variantItem' => $variantItem, 'product' => $product, 'variant' => $variant],200);
    }
}
