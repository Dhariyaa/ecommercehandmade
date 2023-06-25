
<?php use App\Cart; ?>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>Product</th>
        <th>Description</th>
        <th>Quantity/Update</th>
        <th>Unit Price</th>
        <th>Category/Product <br>Discount</th>
        <th>Sub Total</th>
    </tr>
    </thead>
    <tbody>
    <?php $total_price = 0; ?>
    @foreach($userCartItems as $item)
    <?php $attrPrice = Cart::getProductAttrPrice($item['product_id']); ?>
    <tr>
        <td> <img width="60" src="{{ asset('images/product_images/small/'.$item['product']['main_image']) }}"
        alt=""/></td>
        <td>{{ $item['product']['product_name'] }}<br/>Code : {{ $item['product']['product_code'] }}</td>
        <td>
        <div class="input-append">
            <input class="span1" style="max-width:34px"
            value="{{$item ['quantity'] }}" id="appendedInputButtons" size="16" type="text">
                <button class="btn btnItemUpdate qtyMinus" type="button" data-cartid="{{ $item['id'] }}"><i class="icon-minus"></i></button>
                <button class="btn btnItemUpdate qtyPlus" type="button" data-cartid="{{ $item['id'] }}"><i class="icon-plus"></i></button>
                <button class="btn btn-danger btnItemDelete" type="button" data-cartid="{{ $item['id'] }}"><i class="icon-remove icon-white"></i></button>
            </div>
        </td>
        <td>RM {{ $attrPrice }}</td>
        <td>RM.0.00</td>
        <td>RM {{ $attrPrice * $item['quantity'] }}</td>
    </tr>
    <?php $total_price = $total_price +($attrPrice * $item['quantity']); ?>
    @endforeach

    <tr>
        <td colspan="6" style="text-align:right">Sub Total Price:	</td>
        <td> RM {{ $total_price }}</td>
    </tr>
        {{-- <tr>
        <td colspan="6" style="text-align:right">Total Discount:	</td>
        <td> RM.0.00</td>
    </tr> --}}
        <tr>
        <td colspan="6" style="text-align:right"><strong>TOTAL (RM {{$total_price}} - RM.0 ) =</strong></td>
        <td class="label label-important" style="display:block"> <strong> RM {{ $total_price }} </strong></td>
    </tr>
    </tbody>
</table>
