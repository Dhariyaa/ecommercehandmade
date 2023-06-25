<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use App\Section;
use Session;
use Image;

class CategoryController extends Controller
{
    public function categories(){
        Session::put('page', 'categories');
        $categories = Category::with(['section','parentCategory'])->get();
        /*$categories = json_decode(json_encode($categories));
        echo "<pre>"; print_r($categories); die;*/
        return view('admin.categories.categories')->with(compact('categories'));
    }

    public function updateCategoryStatus(Request $request){
        if($request->ajax()){
         $data =$request->all();
            //echo "<pre>"; print_r($data); die;
            if ($data['status']=="Active"){
             $status =0;
            }else{
             $status = 1;
            }

            Category ::where('id',$data['category_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status,'category_id'=>$data['category_id']]);
            }
         }

         public function addEditCategory(Request $request, $id=null){
            if($id==""){

                  //Insert Category function
                $title = "Insert Category";
                $category = new Category;
                $categoryData=array();
                $getCategories=array();
                $message ="Category has been added succesfully!";
            }else{

                //Edit category function
                $title = "Edit Category";
                $categoryData = Category::where('id',$id)->first();
                $categoryData = json_decode(json_encode($categoryData),true);
               /* echo "<pre>"; print_r($categoryData ); die;*/
                $getCategories = Category::with('subcategories')->where (['parent_id'=>0, 'section_id'=>$categoryData['section_id']])->get();
                $getCategories = json_decode(json_encode($getCategories), true);
                 $category = Category::find($id);
                $message ="Category has been updated succesfully!";

            }

            if($request->isMethod('post')){
                $data = $request->all();
                // echo"<pre>"; print_r($data); die;

                //validations for category
                $rules = [
                    //To make the name only be in alphabet and no numeric formula regex:/^[\pL\s\-]+$/u
                    'category_name'=> 'required|regex:/^[\pL\s\-]+$/u',
                    'section_id'=> 'required',
                    'url'=> 'required',
                    'category_image'=> 'image',
                ];
                $customMessages = [
                    'category_name.required'=> 'Category Name is required!',
                    'category_name.regex'=> 'Please enter a valid category name!',
                    'section_id.required'=> 'A Main Category is required!',
                    'url.required'=> 'Please enter a valid url!',
                    'category_image.image'=> 'Please enter a valid category image!'
                ];
                $this->validate($request, $rules,$customMessages);

                //To upload category images
                if($request->hasFile('category_image')){
                    $image_temp = $request->file('category_image');
                    if($image_temp->isValid()){
                //Obtain image extension
                $extention = $image_temp->getClientOriginalExtension();
                //Generate new image name
                $imageName = rand(1111,99999).'.'.$extention;
                $imagePath = 'images/category_images/'.$imageName;
                //image upload
                Image::make($image_temp)->save($imagePath);

                //save image category
                $category->category_image = $imageName;
                 }
            }

            if (empty($data['category_discount'])){
                $data['category_discount']= null;
            }

            if (empty($data['description'])){
                $data['description']= null;
            }
            if (empty($data['meta_title'])){
                $data['meta_title']= null;
            }

            if (empty($data['meta_description'])){
                $data['meta_description']= null;
            }

            if (empty($data['meta_keywords'])){
                $data['meta_keywords']= null;
            }

                $category->parent_id = $data['parent_id'];
                $category->section_id = $data['section_id'];
                $category->category_name = $data['category_name'];
                $category->category_discount = $data['category_discount'];
                $category->description = $data['description'];
                $category->url = $data['url'];
                $category->meta_title = $data['meta_title'];
                $category->meta_description = $data['meta_description'];
                $category->meta_keywords = $data['meta_keywords'];
                $category->status= 1;
                $category->save();

                session::flash('success_message',$message);
                return redirect('admin/categories');
            }

            //Get All sections
            $getSections = Section:: get();

            return view('admin.categories.add_edit_category')->with(compact('title','getSections','categoryData','getCategories'));
        }

            public function includeCategoriesLevel(Request $request){
                if($request->ajax()){
                    $data = $request->all();
                   /*echo "<pre>"; print_r($data); die;*/
                    $getCategories = Category::with ('subcategories')->where(['section_id'=>$data['section_id'],'parent_id'=>0,
                    'status'=>1])->get();
                    $getCategories = json_decode(json_encode($getCategories),true);
                    /*echo "<pre>"; print_r($getCategories); die;*/
                    return view('admin.categories.include_categories_level')->with(compact('getCategories'));
                }
            }

            public function deleteCategoryImage($id){

                //Obtain category image
                $categoryImage = Category::select ('category_image')->where('id',$id)->first();

                //get category image path
                $category_Image_Path = 'images/category_images/';

                //delete the category image from the category_images folder when exist
                if(file_exists($category_Image_Path.$categoryImage->category_image)){
                    unlink($category_Image_Path.$categoryImage->category_image);
                }

                //delete the category image from the categories table
                Category::where ('id',$id)->update(['category_image'=>'']);

                $message = 'Image deleted successfully!';
                session::flash('success_message',$message);
                return redirect()->back();

            }

            public function deleteCategory($id){

                // to delete the category
                Category::where('id', $id)->delete();

                $message = 'Category is deleted successfully!';
                session::flash('success_message',$message);
                return redirect()->back();
            }
         }

