@extends('layouts.app_master_frontend')
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

    <style>
        <?php $style = file_get_contents('css/product_detail_insights.min.css');echo $style;?>
    </style>
    
@stop
@section('content')
    <div class="container">
        <div class="breadcrumb">
            <ul>
                <li>
                    <a itemprop="url" href="/" title="Home"><span itemprop="title">Trang chủ</span></a>
                </li>
                <li>
                    <a itemprop="url" href="{{ route('get.product.list') }}" title="Sản phẩm"><span
                            itemprop="title">Sản phẩm</span></a>
                </li>

                <li>
                    <a itemprop="url" href="" title="Đăng nhập"><span itemprop="title">{{ $product->pro_name }}</span></a>
                </li>

            </ul>
        </div>
        <div class="card">
            <div class="card-body info-detail">
                <div class="left">
                    <div class="Img">
                    <a href="{{ route('get.product.detail',$product->pro_slug . '-'.$product->id ) }}" title=""class="">
                        <img alt="" src="{{ pare_url_file($product->pro_avatar) }}"
                             class="lazyload">
                    </a>
                    </div>
                    <div class="GroupListImg">
                    <div class="Gro owl-carousel owl-theme owl-custom">
                                @foreach ($images as $img)
                                    <div class="Cl">
                                        <img class="demo cursor" src=" {{ pare_url_file($img->pi_slug) }}">
                                    </div>
                                @endforeach
                            </div>
                    </div>
                </div>
                <div class="right" id="product-detail" data-id="{{ $product->id }}">
                    <h1>{{  $product->pro_name }}</h1>
                    <div class="right__content">
                        <div class="info">

                            <div class="prices">
                                @if ($product->pro_sale)
                                    <p>Giá niêm yết:
                                        <span class="value">{{ number_format($product->pro_price,0,',','.') }} đ</span>
                                    </p>
                                    @php
                                        $price = ((100 - $product->pro_sale) * $product->pro_price)  /  100 ;
                                    @endphp
                                    <p>
                                        Giá bán: <span
                                            class="value price-new">{{  number_format($price,0,',','.') }} đ</span>
                                        <span class="sale">-{{  $product->pro_sale }}%</span>
                                    </p>
                                @else
                                    <p>
                                        Giá bán: <span class="value price-new">{{  number_format($product->pro_price,0,',','.') }} đ</span>
                                    </p>
                                @endif
                                <p>
                                    <span>Lượt xem:&nbsp</span>
                                    <span>{{ $product->pro_view }}</span>
                                </p>
                            </div>
                            <div class="btn-cart">
                                <a href="{{ route('get.shopping.add', $product->id) }}" title=""
                                   onclick="add_cart_detail('17617',0);" class="muangay">
                                    <span>Thêm vào giỏ hàng</span>
                                </a>
                                <a href="{{ route('ajax_get.user.add_favourite', $product->id) }}"
                                   title="Thêm sản phẩm yêu thích"
                                   class="muatragop  {{ !\Auth::id() ? 'js-show-login' : 'js-add-favourite' }}">
                                    <span>Yêu thích</span>
                                </a>
                            </div>
                             <div class="btn-cart">
                              <a href="javascript:void(0);" title="" onclick="add_cart_detail('17617',0);"
    class="muangay buy-now-btn"
    style="background-color: #28a745; color: white; border-radius: 4px; padding: 8px 15px; display: inline-block; text-align: center; text-decoration: none;">
    <span>Mua ngay</span>
</a>

                            </div>
                            <div class="infomation">
                                <h2 class="infomation__title">Thông tin</h2>
                                <div class="infomation__group">

                                    <div class="item">
                                        <p class="text1">Danh mục:</p>
                                        <h3 class="text2">
                                            @if (isset($product->category->c_name))
                                                <a href="{{  route('get.category.list', $product->category->c_slug).'-'.$product->pro_category_id }}">{{ $product->category->c_name }}</a>
                                            @else
                                                "[N\A]"
                                            @endif
                                        </h3>
                                    </div>
                                    @foreach($attribute as $key => $attr)
                                    <div class="item">
                                        @foreach($attr as  $k => $at)
                                            @if (in_array($k, $attributeOld))
                                                <p class="text1">{{ $key }}:</p>
                                                <h3 class="text2">{{ $at['atb_name'] }}</h3>
                                            @endif
                                        @endforeach
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @if (isset($event1))
                            <div class="ads">
                                <a href="#" title="Giam giá" target="_blank"><img alt="Hoan tien" style="width: 100%"
                                                                                  src="{{ pare_url_file($event1->e_banner) }}"></a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="" style="margin-bottom: 10px">
                <h4 class="reviews-title"><b>Nội dung</b></h4>
                {!! $product->pro_content !!}
                @if($product->pro_link)
                <iframe src="{{ $product->pro_link }}" width="100%" height="1000"></iframe>
                @endif
            </div>
            @include('frontend.pages.product_detail.include._inc_ratings')
            <div class="comments">
                <div class="form-comment">
                    <form action="" method="POST">
                        <input type="hidden" name="productId" value="{{ $product->id }}">
                        <div class="form-group">
                            <textarea placeholder="Mời bạn để lại bình luận ..." name="comment" class="form-control" id="" cols="30" rows="5"></textarea>
                        </div>
                        <div class="footer">
                            <p>
                            </p>
                            <button class=" {{ \Auth::id() ? 'js-save-comment' : 'js-show-login' }}">Gửi bình luận</button>
                        </div>
                    </form>
                </div>
                @include('frontend.pages.product_detail.include._inc_list_comments')
            </div>

        </div>
        <div class="card-body product-des">
            <div class="left">
                <div class="tabs">
                    <div class="tabs__content">
                        <div class="product-five">
                            <div class="bot js-product-5 owl-carousel owl-theme owl-custom">
                                @foreach($productsSuggests as $product)
                                    <div class="item">
                                        @include('frontend.components.product_item',['product' => $product])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right">
                @if (isset($event3))
                <a href="#" title="Giam giá" target="_blank"><img alt="Hoan tien" style="width: 100%"
                                                                  src="{{ pare_url_file($event3->e_banner) }}"></a>
                @endif
            </div>
        </div>
    </div>
    @if ($isPopupCaptcha >= 2)
        @include('frontend.pages.product_detail.include._inc_popup_captcha')
    @endif
@stop
@section('script')

    <script>
		var ROUTE_COMMENT = '{{ route('ajax_post.comment') }}';
		var CSS = "{{ asset('css/product_detail.min.css') }}";
		var URL_CAPTCHA = '{{ route('ajax_post.captcha.resume') }}'
        $('.buy-now-btn').click(function(e) {
    e.preventDefault();
    let urlAddCart = "{{ route('get.shopping.add', $product->id) }}";
    // AJAX thêm vào giỏ
    $.get(urlAddCart, function(res) {
        // Có thể xử lý trạng thái, thông báo,… ở đây nếu muốn
        window.location.href = "/don-hang"; // Điều hướng tới trang đơn hàng
    });
});
    </script>
    <script src="{{ asset('js/product_detail.js') }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script>
        $('.Gro.owl-carousel').owlCarousel({
    loop: true,
    margin: 10,
    nav: false,
    items: 3,
    responsive: {
        0: {
            items: 1
        },
        600: {
            items: 2
        },
        1000: {
            items: 3
        }
    }
});

    </script>
    
@endsection
