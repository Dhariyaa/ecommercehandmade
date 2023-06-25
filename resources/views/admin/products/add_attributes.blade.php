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
              <li class="breadcrumb-item active">Product Attributes</li>
            </ol>
          </div>
        </div>
      </div>
      <!-- /.container-fluid -->
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
            @if (Session::has('error_message'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert" style="margin-top: 20px;">
                {{ Session::get('error_message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

        <form name="attributeForm" id="attributeForm" method="post" action="{{ url('admin/add-attributes/'.$productData['id']) }}">@csrf
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
                <div class="col-md-3">
                    <div class="form-group">
                        @if(!empty($productData['main_image']))
                        <img style="width:200px;"
                      src="{{ asset('images/product_images/small/'.$productData['main_image']) }}">
                      @else
                      <img style="width:200px;"
                      src="{{asset('images/product_images/small/no-image.png')}}">
                      @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="product_name">Name of Product: </label> &nbsp; {{ $productData['product_name'] }}
                    </div>
                    <div class="form-group">
                        <label for="description">Product Description: </label>  &nbsp; {{ $productData['description'] }}
                    </div>
                    <div class="form-group">
                        <label for="product_code">Code of Product: </label>  &nbsp; {{ $productData['product_code'] }}
                    </div>
                    <div class="form-group">
                    <div class="field_wrapper">
                        <div>
                            <input id="sku" name="sku[]" type="text" name="sku[]" value=""
                            placeholder="SKU" style="width: 120px;" required=""/>
                            <input id="price" name="price[]" type="number" name="price[]" value=""
                                placeholder="Price" style="width: 120px;" required=""/>
                            <input id="stock" name="stock[]" type="number" name="stock[]" value=""
                                placeholder="Stock" style="width: 120px;" required=""/>


                            <a href="javascript:void(0);" class="add_button" title="Add field">Add</a>
                        </div>
                    </div>
                    </div>
              </div>
        </div>
          <div class="card-footer row bg-white justify-content-end">
           <button type="submit" class="d-inline p-2 bg-dark text-white">Add </button>
          </div>
        </div>
        </form>

        <form name="editAttributeForm" id="editAttributeForm" method="post" action="{{ url('admin/edit-attributes/'.$productData['id'] )}}">@csrf
        <div class="card">
            <div class="card-header">
              <h3 class="card-title">Added Product Attributes</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="products" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>SKU</th>
                  <th>Price</th>
                  <th>Stock</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                  @foreach($productData ['attributes'] as $attribute)
                  <input style="display: none;" type="text" name="attrId[]" value="{{ $attribute['id'] }}">
                <tr>
                  <td>{{ $attribute['id'] }}</td>
                  <td>{{ $attribute['sku'] }}</td>
                  <td>
                <input type="number" name="price[]" value="{{ $attribute['price'] }}" required="">
                </td>
                  <td>
                    <input type="number" name="stock[]" value="{{ $attribute['stock'] }}" required="">
                </td>
                  <td>
                    @if($attribute['status']==1)
                    <a class="updateAtrributeStatus" id="attribute-{{ $attribute['id']  }}" attribute_id="{{$attribute['id'] }}"
                     href="javascript:void(0)">Active</a>
                  @else
                  <a class="updateAttributeStatus" id="attribute-{{ $attribute['id'] }}" attribute_id="{{ $attribute['id']  }}"
                     href="javascript:void(0)">Inactive</a>
                  @endif
                  &nbsp;&nbsp;
                  <a title="Delete Attribute" href="javascript:void(0)" class="ConfirmDelete" record="attribute" recordId="{{ $attribute['id'] }}"
                    ><i class="fas fa-trash"></i></a>
                  </td>
                </tr>
               @endforeach
                </tbody>
              </table>
            </div>
            <div class="card-footer row bg-white justify-content-end">
                <button type="submit" class="d-inline p-2 bg-dark text-white">Update  </button>
               </div>
            <!-- /.card-body -->
          </div>

      </div>
    </form>
</div> <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

@endsection
