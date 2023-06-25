<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\User;
use Session;
use Auth;
use App\Cart;
use App\Country;



class UsersController extends Controller
{
    public function loginRegister(){
        return view('front.users.login_register');

    }

    public function registerUser(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>";print_r($data); die;

            //check if the user exist
            $userCount =  User::where('email',$data['email'])->count();
            if($userCount>0){
                $message = "The Email Entered Already Exist!";
                session::flash('error_message',$message);
                return redirect()->back();

            }else{
                //register the user
                $user = new User;
                $user->name = $data['name'];
                $user->mobile = $data['mobile'];
                $user->email = $data['email'];
                $user->password = bcrypt($data['password']);
                $user->status = 1;
                $user->save();

                if(Auth::attempt(['email'=>$data['email'],'password'=>$data['password']])){


                    //update user cart with user id
                if(!empty(Session::get('session_id'))){
                    $user_id=Auth::user()->id;
                    $session_id = Session::get('session_id');
                    Cart::where('session_id',$session_id)->update(['user_id'=>$user_id]);
                }

                    // echo "<pre>"; print_r(Auth::user()); die;
                    return redirect('kashmiri');
                }

            }
        }
    }


    public function checkEmail(Request $request){
        //check if the email is already existed
        $data = $request->all();
        $emailCount= User::where('email',$data['email'])->count();
        if($emailCount>0){
            return "false";
        }else{
            return "true";
        }
    }


    public function loginUser(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if(Auth::attempt(['email'=>$data['email'],'password'=>$data['password']])){

                //update user cart with user id
                if(!empty(Session::get('session_id'))){
                    $user_id=Auth::user()->id;
                    $session_id = Session::get('session_id');
                    Cart::where('session_id',$session_id)->update(['user_id'=>$user_id]);
                }
                return redirect('/cart');
            }else{
                $message = "Invalid Username or Password has been set";
                Session::flash('error_message',$message);
                return redirect()->back();
            }
        }
    }

    public function logoutUser(){
        Auth::logout();
        return redirect('/');
    }

    public function account(Request $request){
         $user_id =  Auth::user()->id;
         $userDetails = User::find($user_id)->toArray();

         $countries= Country::where('status',1)->get()->toArray();
        //  dd($countries); die;

        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            Session::forget('error_message');
            Session::forget('success_message');


            $rules = [
                //To make the name only be in alphabet and no numeric formula regex:/^[\pL\s\-]+$/u
                'name'=> 'required|regex:/^[\pL\s\-]+$/u',
                'mobile'=> 'required|numeric',

            ];
            $customMessages = [
                'name.required'=> 'Name is required!',
                'name.regex'=> 'Please enter a valid name! (no numbers)',
                'mobile.required'=> 'A Handphone number is required!',

            ];
            $this->validate($request, $rules,$customMessages);

            $user = User::find($user_id);
            $user->name =  $data['name'];
            $user->address =  $data['address'];
            $user->city =  $data['city'];
            $user->state =  $data['state'];
            $user->country =  $data['country'];
            $user->pincode =  $data['pincode'];
             $user->mobile =  $data['mobile'];
             $user->save();
             $message = "The details that you have entered has been updated succesfully!";
             Session::put('success_message', $message);
             return redirect()->back();

        }

        return view('front.users.account')->with(compact('userDetails','countries'));
    }


    public function chkUserPassword(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $user_id = Auth::User()->id;
            $chkPassword = User::select('password')->where('id',$user_id)->first();
            if(Hash::check($data['current_pwd'],$chkPassword->password)){
                return "true";
            }else{
                return "false";
            }
        }
    }

    public function updateUserPassword(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $user_id = Auth::User()->id;
            $chkPassword = User::select('password')->where('id',$user_id)->first();
            if(Hash::check($data['current_pwd'],$chkPassword->password)){
                //update to the current password
                $new_pwd = bcrypt($data['new_pwd']);
                User::where('id',$user_id)->update(['password'=>$new_pwd]);
                $message = "Password has been updated successfully!";
                Session::flash('success_message',$message);
                Session::forget('error_message');
                return redirect()->back();
            }else{
                $message = "Password inserted is incorrect!";
                Session::flash('error_message',$message);
                Session::forget('success_message');
                return redirect()->back();
            }
        }
    }

}
