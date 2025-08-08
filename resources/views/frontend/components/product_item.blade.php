@if (isset($product))
<div class="product-item"
    style="position: relative; padding: 10px; border: 1px solid #eee; border-radius: 5px; overflow: hidden;">

    {{-- Tag giảm giá bo góc trên phải --}}
    @if ($product->pro_sale)
    <div style="
            position: absolute;
            top: 0;
            right: 0;
            background-color: #e74c3c;
            color: white;
            padding: 4px 10px;
            font-weight: bold;
            font-size: 0.85rem;
            border-bottom-left-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            user-select: none;
            z-index: 10;
            ">
        -{{ $product->pro_sale }}%
    </div>
    @endif

    <a href="{{ route('get.product.detail', $product->pro_slug . '-' . $product->id) }}" title=""
        class="avatar image contain" style="display: block; margin-bottom: 10px;">
        <img alt="{{ $product->pro_name }}" data-src="{{ pare_url_file($product->pro_avatar) }}"
            src="{{ asset('images/preloader.gif') }}" class="lazyload lazy"
            style="width: 100%; height: auto; object-fit: contain;">
    </a>
    <a href="{{ route('get.product.detail', $product->pro_slug . '-' . $product->id) }}"
        title="{{ $product->pro_name }}" class="title"
        style="display: block; font-weight: 600; font-size: 1.1rem; margin-bottom: 8px; color: #333; text-decoration: none; max-width: 250px;">
        <h3 style="
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    ">
            {{ $product->pro_name }}
        </h3>
    </a>

    <p class="rating" style="margin-bottom: 10px;">
        <span>
            @php
            $iactive = 0;
            if ($product->pro_review_total){
            $iactive = round($product->pro_review_star / $product->pro_review_total, 2);
            }
            @endphp
            @for($i = 1 ; $i <= 5 ; $i ++) <i class="la la-star {{ $i <= $iactive ? 'active' : '' }}"
                style="color: {{ $i <= $iactive ? '#f1c40f' : '#ccc' }};"></i>
                @endfor
        </span>
        <span class="text" style="color: #555; margin-left: 6px;">{{ $product->pro_review_total }} đánh giá</span>
    </p>
    @if ($product->pro_sale)
    <p>
        @php
        $price = ((100 - $product->pro_sale) * $product->pro_price) / 100 ;
        @endphp
        <span class="price">{{  number_format($price,0,',','.') }} đ</span>
        <span class="price-sale">{{ number_format($product->pro_price,0,',','.') }} đ</span>
    </p>
    @else
   <span class="price">
        {{ number_format($product->pro_price, 0, ',', '.') }} đ
    </p>
    @endif

    <!-- Nút Thêm vào giỏ hàng -->
    <a href="{{ route('get.shopping.add', $product->id) }}" title="Thêm vào giỏ hàng"
        onclick="add_cart_detail('{{ $product->id }}', 0);" class="btn btn-add-cart"
        style="display: inline-block; padding: 8px 16px; background-color: #007bff; color: white; border-radius: 4px; text-align: center; text-decoration: none; font-weight: 600; transition: background-color 0.3s ease;">
        Thêm vào giỏ hàng
    </a>

</div>
@endif