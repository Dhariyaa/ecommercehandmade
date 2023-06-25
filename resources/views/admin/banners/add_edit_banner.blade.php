@extends('layouts.admin_layout.admin_layout')
@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Sections</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Banners</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        @if ($errors->any())
                <div class="alert alert-danger" style="margin-top: 10px;">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                 @endforeach
                </ul>
                </div>
            @endif
            @if (Session::has('success_message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top: 20px;">
                {{ Session::get('success_message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
        <form name="formProduct" id="FormProduct" @if(empty($productData['id']))
        action="{{ url('admin/add-edit-product') }}" @else action="{{ url('admin/add-edit-product/'.$productData['id']) }}" @endif
        method="post" enctype="multipart/form-data">@csrf
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">{{$title}}</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                    <label for="product_code">Code of Product</label>
                    <input type="text" class="form-control" name="product_code" id="product_code"
                     placeholder="Please Enter Product Code (Must Fill In)" @if(!empty($productData['product_code']))
                     value="{{ $productData['product_code'] }}" @else value="{{ old('product_code') }}" @endif>
                </div>
                <div class="form-group">
                    <label for="product_price">Price of Product</label>
                    <input type="text" class="form-control" name="product_price" id="product_price"
                     placeholder="Please Enter Product Price" @if(!empty($productData['product_price']))
                     value="{{ $productData['product_price'] }}" @else value="{{ old('product_price') }}" @endif>
                </div>
                    <div class="form-group">
                        <label for="meta_description"> Meta Description</label>
                        <textarea id="meta_description" name="meta_description" class="form-control" rows="3"
                        placeholder="Enter the description ...">@if(!empty($productData['meta_description']))
                        {{$productData['meta_description'] }} @else {{ old('meta_description') }} @endif</textarea>
                    </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                    <label for="product_discount">Discount of Product (%)</label>
                    <input type="number" min="10" step="10" max="90" class="form-control" name="product_discount" id="product_discount"
                     placeholder="Please Enter Product Discount" @if(!empty($productData['product_discount']))
                     value="{{ $productData['product_discount'] }}" @else value="{{ old('product_discount') }}" @endif>
                </div>
                <div class="form-group">
                    <label for="product_weight"> Product Weight</label>
                    <input type="number" class="form-control" name="product_weight" id="product_weight"
                     placeholder="Please Enter Product Discount" @if(!empty($productData['product_weight']))
                     value="{{ $productData['product_weight'] }}" @else value="{{ old('product_weight') }}" @endif>
                </div>
                <div class="form-group">
                    <label for="image">Banner Image</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="image"
                         name="image">
                        <label class="custom-file-label" for="image">Choose file</label>
                      </div>
                      <div class="input-group-append">
                        <span class="input-group-text">Upload</span>
                      </div>
                    </div>
                    <div> Recommended Image Size: (Width:1040px Height:1200px)</div>
                    @if(!empty($productData['image']))
                    <div>
                        <img style="width:100px; margin-top: 5px;"
                      src="{{ asset('images/product_images/small/'.$productData['image']) }}">
                      &nbsp;
                      <a class="ConfirmDelete" href="javascript:void(0)" record="product-image" recordId="{{ $productData['id'] }}"
                     >Delete Image</a>
                    </div>
                  @endif
                    <div>
                </div>
                  </div>
                  <div class="form-group">
                    <label for="meta_keywords">Featured Item</label>
                    <input type="checkbox" name="is_featured" id="is_featured" value="Yes"
                    @if(!empty($productData['is_featured']) && $productData['is_featured']=="Yes") checked=""
                    @endif>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                    <label for="meta_title">Meta Title</label>
                    <textarea id="meta_title" name="meta_title" class="form-control" rows="3"
                     placeholder="Enter the description ..."
                    >@if(!empty($productData['meta_title']))
                    {{ $productData['meta_title'] }} @else {{ old('meta_title') }} @endif</textarea>
                </div>
                    <div class="form-group">
                        <label for="meta_keywords">Meta Keywords</label>
                        <textarea  name="meta_keywords" id="meta_keywords" class="form-control" rows="3"
                        placeholder="Enter the description ..." >@if(!empty($productData['meta_keywords']))
                        {{$productData['meta_keywords'] }} @else {{ old('meta_keywords') }} @endif</textarea>
                    </div>
                    </div>
                </div>
              </div>
          <div class="card-footer">
           <button type="submit" class="d-inline p-2 bg-dark text-white">Submit</button>
          </div>
        </div>
        </form>
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

@endsection
