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
              <li class="breadcrumb-item active">Categories</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        @if ($errors->any())
                <div class="alert alert-danger" style="margin_top: 10px;">
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
        <form name="formCategory" id="FormCategory" @if(empty($categoryData['id']))
        action="{{ url('admin/add-edit-category') }}" @else action="{{ url('admin/add-edit-category/'.$categoryData['id']) }}" @endif
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
                    <label for="category_name">Name of Category/Subcategory</label>
                    <input type="text" class="form-control" name="category_name" id="category_name"
                     placeholder="Please Enter Category/Subcategory Name (Must Fill In)" @if(!empty($categoryData['category_name']))
                     value="{{ $categoryData['category_name'] }}" @else value="{{ old('category_name') }}" @endif>
                </div>
                <div id="includeCategoriesLevel">
                    @include( 'admin.categories.include_categories_level')
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                    <label>Select Main Category</label>
                    <select name ="section_id" id="section_id" class="form-control select2" style="width: 100%;">
                      <option value="">Please Select (Must Fill In)</option>
                      @foreach ($getSections as $section)
                      <option value="{{ $section->id }}" @if(!empty($categoryData['section_id'])
                         && $categoryData['section_id']==$section->id) selected @endif>{{ $section->name }}</option>
                      @endforeach
                    </select>
                  </div>


                <div class="form-group">
                    <label for="exampleInputFile">Category Image</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="category_image"
                         name="category_image">
                        <label class="custom-file-label" for="category_image">Choose file</label>
                      </div>
                      <div class="input-group-append">
                        <span class="input-group-text">Upload</span>
                      </div>
                      </div>
                      @if(!empty($categoryData['category_image']))
                      <div><img style="width:100px; margin-top: 5px;"
                        src="{{ asset('images/category_images/'.$categoryData['category_image']) }}">
                        &nbsp;
                        <a class="ConfirmDelete" href="javascript:void(0)" record="category-image" recordId="{{ $categoryData['id'] }}"
                        <?php /*href="{{ url('admin/delete-category-image/'.$categoryData['id']) }}"*/ ?>>Delete Image</a>
                    </div>
                    @endif
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12 col-sm-6">
                <div class="form-group">
                    <label for="category_discount">Discounts for Category</label>
                    <input type="text" class="form-control" id="category_discount"
                    name="category_discount" placeholder=" Please Enter Category Name"
                    @if(!empty($categoryData['category_discount']))
                     value="{{ $categoryData['category_discount'] }}" @else value="{{ old('category_discount') }}" @endif>
                </div>
                <div class="form-group">
                    <label for="description">Category Description</label>
                    <textarea name="description" id="description" class="form-control" rows="3"
                    placeholder="Enter the description ...">@if(!empty($categoryData['description']))
                     {{ $categoryData['description'] }} @else {{ old('description') }} @endif</textarea>
                </div>
              </div>
              <div class="col-12 col-sm-6">
                <div class="form-group">
                    <label for="url">URL for Category </label>
                    <input type="text" class="form-control" id="url" name="url"
                     placeholder=" Please Enter Category Name (Must Fill In)"
                     @if(!empty($categoryData['url']))
                     value="{{ $categoryData['url'] }}" @else value="{{ old('url') }}" @endif>
                </div>
                <div class="form-group">
                    <label for="meta_title">Meta Title</label>
                    <textarea id="meta_title" name="meta_title" class="form-control" rows="3"
                     placeholder="Enter the description ..."
                    >@if(!empty($categoryData['meta_title']))
                    {{ $categoryData['meta_title'] }} @else {{ old('meta_title') }} @endif</textarea>
                </div>
              </div>
              <div class="col-12 col-sm-6">
                <div class="form-group">
                    <label for="meta_description">Description for Meta</label>
                    <textarea id="meta_description" name="meta_description" class="form-control" rows="3"
                    placeholder="Enter the description ...">@if(!empty($categoryData['meta_description']))
                    {{$categoryData['meta_description'] }} @else {{ old('meta_description') }} @endif</textarea>
                </div>
              </div>
              <div class="col-12 col-sm-6">
                <div class="form-group">
                    <label for="meta_keywords">Meta Keywords</label>
                    <textarea  name="meta_keywords" id="meta_keywords" class="form-control" rows="3"
                    placeholder="Enter the description ..." >@if(!empty($categoryData['meta_keywords']))
                    {{$categoryData['meta_keywords'] }} @else {{ old('meta_keywords') }} @endif</textarea>
                </div>
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
