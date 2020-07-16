<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;// su dung database
use Session;
use Illuminate\Support\Facades\Redirect;
session_start();

class BrandProduct extends Controller
{
    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }
            return Redirect::to('admin')->send();
        
    }
    public function add_brand_product()
    {
        $this->AuthLogin();
    	return view('admin.add_brand_product');	
    }
   
    public function all_brand_product()
    {
        $this->AuthLogin();
    	$all_brand_product = DB::table('tbl_brand')->get();
    	// view('tro toi file view')->(biến lưu, dữ liệu đổ vào)
    	$manager_brand_product = view('admin.all_brand_product')->with('all_brand_product',$all_brand_product);
    	return view('admin_layout')->with('admin.all_brand_product',$manager_brand_product);	
    }

     public function edit_brand_product($id)
    {
        $this->AuthLogin();
    	$edit_brand_product = DB::table('tbl_brand')->where('brand_id',$id)->get();
    	// view('tro toi file view')->(biến lưu, dữ liệu đổ vào)
    	$manager_brand_product = view('admin.edit_brand_product')->with('edit_brand_product',$edit_brand_product);
    	return view('admin_layout')->with('admin.edit_brand_product',$manager_brand_product);	
    }

    public function update_brand_product(Request $request,$id)
    {
        $this->AuthLogin();
    	$data = array();
    	$data['brand_name'] = $request->brand_product_name;
    	$data['brand_desc'] = $request->brand_product_desc;
    	DB::table('tbl_brand')->where('brand_id',$id)->update($data);
    	Session::put('message','Cập nhật thương hiệu sản phẩm thành công');
    	return Redirect::to('all-brand-product');
    }

    public function save_brand_product(Request $request)
    {
        $this->AuthLogin();
    	$data = array();
    	// $data['tên cột trong csdk'] = name trong view
    	$data['brand_name'] = $request->brand_product_name;
    	$data['brand_desc'] = $request->brand_product_desc;
    	$data['brand_status'] = $request->brand_product_status;
    	DB::table('tbl_brand')->insert($data);
    	Session::put('message','Thêm thương hiệu sản phẩm thành công');
    	return Redirect::to('all-brand-product');
    }

     public function delete_brand_product($id)
    {
        $this->AuthLogin();
    	DB::table('tbl_brand')->where('brand_id',$id)->delete();
    	Session::put('message','Xóa thương hiệu sản phẩm thành công');
    	return Redirect::to('all-brand-product');
    }

    public function unactive_brand_product($id)
    {
        $this->AuthLogin();
    	DB::table('tbl_brand')->where('brand_id',$id)->update(['brand_status'=>0]);
    	Session::put('message','Không kích hoạt thương hiệu sản phẩm thành công');
    	return Redirect::to('all-brand-product');
    }
    public function active_brand_product($id)
    {
        $this->AuthLogin();
    	DB::table('tbl_brand')->where('brand_id',$id)->update(['brand_status'=>1]);
    	Session::put('message','Kích hoạt thương hiệu sản phẩm thành công');
    	return Redirect::to('all-brand-product');	
    }
    // End Function Admin Page

    // show index
    public function show_brand_home($brand_id){
        $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id','desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status','1')->orderby('brand_id','desc')->get();
        $brand_by_id = DB::table('tbl_product')->join('tbl_brand','tbl_product.brand_id','=','tbl_brand.brand_id')->where('tbl_product.brand_id',$brand_id)->where('tbl_product.product_status','1')->get();

        $brand_name = DB::table('tbl_brand')->where('tbl_brand.brand_id',$brand_id)->limit(1)->get();
        return view('pages.brand.show_brand')->with('category',$cate_product)->with('brand',$brand_product)->with('brand_by_id',$brand_by_id)->with('brand_name',$brand_name);
    }
}
