<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use DB;// su dung database
use Session;
use Illuminate\Support\Facades\Redirect;
session_start();

class CartController extends Controller
{
    public function save_cart(Request $request){

    	$cate_product = DB::table('tbl_category_product')->orderby('category_id','desc')->get();
        $brand_product = DB::table('tbl_brand')->orderby('brand_id','desc')->get();

    	$productId = $request->productidhidden;
    	$quantity = $request->qty;

    	$data = DB::table('tbl_product')->where('product_id',$productId)->get();
    	return view('pages.cart.show_cart')->with('category',$cate_product)->with('brand',$brand_product);
    }
}
