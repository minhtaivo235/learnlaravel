<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use DB;// su dung database
use Session;
use Illuminate\Support\Facades\Redirect;
session_start();

class ProductController extends Controller
{
    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }
            return Redirect::to('admin')->send();
        
    }
    public function add_product()
    {
        $this->AuthLogin();
        $cate_product = DB::table('tbl_category_product')->orderby('category_id','desc')->get();
        $brand_product = DB::table('tbl_brand')->orderby('brand_id','desc')->get();
        return view('admin.add_product')->with('cate_product',$cate_product)->with('brand_product',$brand_product);
    }
   
    public function all_product()
    {
        $this->AuthLogin();
    	$all_product = DB::table('tbl_product')
        ->join('tbl_category_product','tbl_category_product.category_id','=','tbl_product.category_id')
        ->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
        ->orderby('tbl_product.product_id','desc')->get();
    	// view('tro toi file view')->(biến lưu, dữ liệu đổ vào)
    	$manager_product = view('admin.all_product')->with('all_product',$all_product);
    	return view('admin_layout')->with('admin.all_product',$manager_product);	
    }

     public function edit_product($id)
    {
        $this->AuthLogin();
        $cate_product = DB::table('tbl_category_product')->orderby('category_id','desc')->get();
        $brand_product = DB::table('tbl_brand')->orderby('brand_id','desc')->get();
    	$edit_product = DB::table('tbl_product')->where('product_id',$id)->get();
    	// view('tro toi file view')->(biến lưu, dữ liệu đổ vào)
    	$manager_product = view('admin.edit_product')->with('edit_product',$edit_product)->with('cate_product',$cate_product)->with('brand_product',$brand_product);
    	return view('admin_layout')->with('admin.edit_product',$manager_product);	
    }

    public function update_product(Request $request,$id)
    {
        $this->AuthLogin();
    	$data = array();
    	$data['product_name'] = $request->product_name;
    	$data['product_price'] = $request->product_price;
        $data['product_desc'] = $request->product_desc;
        $data['product_content'] = $request->product_content;
        $data['category_id'] = $request->product_cate;
        $data['brand_id'] = $request->product_brand;
        $data['product_status'] = $request->product_status;
        $get_image = $request->file('product_image');

        if($get_image){
            // lấy name của ảnh
            $get_name_image = $get_image->getClientOriginalName();
            // tách chuỗi dựa trên dấu '.'  current lấy đầu chuỗi   end lấy cuối chuỗi  
            $name_image = current(explode('.', $get_name_image));
            // getClientOriginaExtension lấy đuôi mở rông như jpg png
            $new_image = $name_image.rand(0,99).'.'.$get_image->getClientOriginalExtension(); 
            $get_image->move('public/uploads/product',$new_image);
            $data['product_image'] = $new_image;
            DB::table('tbl_product')->where('product_id',$id)->update($data);
            Session::put('message','Cập nhật sản phẩm thành công');
            return Redirect::to('all-product');
        }
        DB::table('tbl_product')->where('product_id',$id)->update($data);
        Session::put('message','Cập nhật sản phẩm thành công');
        return Redirect::to('all-product');


    	DB::table('tbl_product')->where('product_id',$id)->update($data);
    	Session::put('message','Cập nhật sản phẩm thành công');
    	return Redirect::to('all-product');
    }

    public function save_product(Request $request)
    {
        $this->AuthLogin();
    	$data = array();
    	// $data['tên cột trong csdk'] = name trong view
    	$data['product_name'] = $request->product_name;
        $data['product_price'] = $request->product_price;
    	$data['product_desc'] = $request->product_desc;
        $data['product_content'] = $request->product_content;
        $data['category_id'] = $request->product_cate;
        $data['brand_id'] = $request->product_brand;
        $data['product_status'] = $request->product_status;

        $get_image = $request->file('product_image');
        if($get_image){
            // lấy name của ảnh
            $get_name_image = $get_image->getClientOriginalName();
            // tách chuỗi dựa trên dấu '.'  current lấy đầu chuỗi   end lấy cuối chuỗi  
            $name_image = current(explode('.', $get_name_image));
            // getClientOriginaExtension lấy đuôi mở rông như jpg png
            $new_image = $name_image.rand(0,99).'.'.$get_image->getClientOriginalExtension(); 
            $get_image->move('public/uploads/product',$new_image);
            $data['product_image'] = $new_image;
            DB::table('tbl_product')->insert($data);
            Session::put('message','Thêm sản phẩm thành công');
            return Redirect::to('all-product');
        }
        $data['product_image'] = '';
    	DB::table('tbl_product')->insert($data);
    	Session::put('message','Thêm sản phẩm thành công');
    	return Redirect::to('all-product');
    }

     public function delete_product($id)
    {
        $this->AuthLogin();
    	DB::table('tbl_product')->where('product_id',$id)->delete();
    	Session::put('message','Xóa sản phẩm thành công');
    	return Redirect::to('all-product');
    }

    public function unactive_product($id)
    {
        $this->AuthLogin();
    	DB::table('tbl_product')->where('product_id',$id)->update(['product_status'=>0]);
    	Session::put('message','Không kích hoạt sản phẩm thành công');
    	return Redirect::to('all-product');
    }
    public function active_product($id)
    {
        $this->AuthLogin();
    	DB::table('tbl_product')->where('product_id',$id)->update(['product_status'=>1]);
    	Session::put('message','Kích hoạt sản phẩm thành công');
    	return Redirect::to('all-product');	
    }
}
