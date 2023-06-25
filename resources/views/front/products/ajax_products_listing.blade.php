<?php use App\Product; ?>
  <div class="tab-pane  active" id="blockView">
            <ul class="thumbnails">
                @foreach($categoryProducts as $product)
                <li class="span3">
                    <div class="thumbnail">
                        <a href="{{ url('product/'.$product['id']) }}">
                            @if(isset($product['main_image']))
                            <?php $product_image_path = 'images/product_images/small/'.$product['main_image']; ?>
                            @else
                            <?php $product_image_path = ''; ?>
                            @endif
                            <?php $product_image_path = 'images/product_images/small/'.$product['main_image']; ?>
                            @if(!empty($product['main_image']) && file_exists($product_image_path))
                            <img style="width: 200px;" src="{{ asset($product_image_path) }}" alt="">
                            @else
                            <img style="width: 200px;" src="{{ asset('images/product_images/small/no-image.png') }}" alt="">
                            @endif
                        <div class="caption">
                            <h5>{{ $product ['product_name'] }} {{ $product ['id'] }} </h5>
                            <p>
                                {{ $product ['product_code'] }}
                            </p>
                            <?php $discounted_price = Product::getDiscountedPrice($product['id']); ?>
                            <h4 style="text-align:center"><a class="btn" href="{{ url('product/'.$product['id']) }}">
                                <i class="icon-zoom-in"></i></a>
                                    </i></a> <a class="btn btn-primary" href="#">
                                        @if($discounted_price>0)
                                       <del>RM {{ $product ['product_price'] }}</del>
                                        @else
                                        RM {{ $product ['product_price'] }}
                                    @endif
                                    </a></h4>
                                    @if($discounted_price>0)
                                    <h4> Discounted Price: RM {{ $discounted_price }} </h4>
                                    @endif
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            <hr class="soft"/>
        </div>
