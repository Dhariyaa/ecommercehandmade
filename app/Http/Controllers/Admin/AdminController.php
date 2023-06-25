<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Auth;
use Session;
use App\Admin;
use Image;

class AdminController extends Controller
{
    public function dashboard(){
        Session::put('page','dashboard');
        return view('admin.admin_dashboard');
    }

    public function settings(){
        Session::put('page','settings');
        /*echo "<pre>"; print_r(Auth::guard('admin')->user()); die;*/
        $adminDetails = Admin::where('email',Auth::guard('admin')->user()->email)->first();
        return view('admin.admin_settings')->with(compact('adminDetails'));
    }

    public function loginpage(Request $request){
        if ($request->isMethod('post')){
            $data = $request->all();
            /*echo "<pre>"; print_r ($data); die;*/


           $rules = [
            'email'=> 'required|email|max:255',
            'password'=> 'required',
           ];

           $customMessages = [
            'email.required'=> 'Please enter your email!',
            'email.email'=> 'A valid email is required!',
            'password.required'=> 'A valid password is required!',
           ];

           $this->validate($request,$rules,$customMessages);



            if(Auth::guard('admin')->attempt(['email'=>$data['email'],'password'=>$data['password']])){
                return redirect('admin/dashboard');
            }else{
                Session::flash('error_message','You have an Invalid Email or Password');
                return redirect()->back();

            }
        }
        /*echo $password = Hash::make('123456'); die;*/
        return view("admin.admin_loginpage");
    }
    public function logout(){
        Auth::guard('admin')->logout();
        return redirect ('/admin');
    }

    public function checkPresentPassword(Request $request){
        $data = $request->all();
        //echo "<pre>"; print_r($data);
        //echo "<pre>"; print_r(Auth::guard('admin')->user()->password); die;
        if(Hash::check($data['present_password'],Auth::guard('admin')->user()->password)){
            echo "true";
        }else{
            echo "false";
        }

    }

    public function updatePresentPassword(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            /*echo "<pre>"; print_r($data); die;*/
            //To check if the present password is valid or not
            if(Hash::check($data['present_password'],Auth::guard('admin')->user()->password)){
                //To check if new and set password is same or not
                if($data['new_password']==$data['set_password']){
                    Admin::where('id', Auth::guard('admin')->user()->id)->update(['password'=>bcrypt($data['new_password'])]);
                    Session::flash('success_message', 'Your new password has been finalized successfully!!');
                }else{
                    Session::flash('error_message','The New and Set Password that you have entered is not the same!!');
                }

            }else{
                Session::flash('error_message','The Present Password that you have entered is incorrect!!');
            }
            return redirect()->back();

        }
    }

    public function updateAdminDetails(Request $request){
        Session::put('page','update-admin-details');
        if ($request->isMethod('post')){
            $data =$request->all();
           // echo "<pre>"; print_r($data); die;
            $rules = [
                //To make the name only be in alphabet and no numeric formula regex:/^[\pL\s\-]+$/u
                'admin_name'=> 'required|regex:/^[\pL\s\-]+$/u',
                'admin_mobile'=> 'required|numeric',
                'admin_image'=> 'image'
            ];
            $customMessages = [
                'admin_name.required'=> 'Name is required!',
                'admin_name.regex'=> 'Please enter a valid name!',
                'admin_mobile.required'=> 'A Handphone number is required!',
                'admin_mobile.numeric'=> 'Please enter a valid handphone number!',
                'admin_image.image'=> 'Please enter a valid image!'
            ];
            $this->validate($request, $rules,$customMessages);

        //To upload images
        if($request->hasFile('admin_image')){
            $image_temp =$request->file('admin_image');
            if($image_temp->isValid()){
                //Obtain image extension
                $extention = $image_temp->getClientOriginalExtension();
                //Generate new image name
                $imageName = rand(1111,99999).'.'.$extention;
                $imagePath = 'images/admin_images/admin_photos/'.$imageName;
                //image upload
                Image::make($image_temp)->save($imagePath);
            }
            }else if (!empty($data['current_admin_image'])){
                $imageName = $data['current_admin_image'];
            }else{
                $imageName="";
            }



            //Upgrade admin details
            Admin::where('email',Auth::guard('admin')->user()->email)
            ->update(['name'=>$data['admin_name'],'mobile'=>$data['admin_mobile'],'image'=>$imageName]);
            session::flash('success_message','The Admin details that you have entered have been upgraded successfully!');
            return redirect()->back();
        }
        return view('admin.update_admin_details');
    }

}

