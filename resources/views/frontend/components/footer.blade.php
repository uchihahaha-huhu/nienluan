<div id="footer" style="background: #004D7C !important;">
    <div class="container footer">
        <div class="footer__left">
            <div class="top">
                <div class="item">
                    <div class="title">Về chúng tôi</div>
                    <ul>
                        <li>
                            <a href="{{ route('get.blog.home') }}">Bài viết</a>
                        </li>
                        <li>
                            <a href="{{ route('get.product.list') }}">Sản phẩm</a>
                        </li>
                        <li>
                            <a href="{{ route('get.register') }}">Đăng ký</a>
                        </li>
                        <li>
                            <a href="{{ route('get.login') }}">Đăng nhập</a>
                        </li>
                    </ul>
                </div>
                <div class="item">
                    <div class="title">Tin tức</div>
                    <ul>
                        @if (isset($menus))
                        @foreach($menus as $menu)
                        <li>
                            <a title="{{ $menu->mn_name }}"
                                href="{{ route('get.article.by_menu',$menu->mn_slug.'-'.$menu->id) }}">
                                {{ $menu->mn_name }}
                            </a>
                        </li>
                        @endforeach
                        @endif
                        <li><a href="{{ route('get.contact') }}">Liên hệ</a></li>
                    </ul>
                </div>
                <div class="item">
                    <div class="title">Chính sách</div>
                    <ul>
                        <li><a href="{{ route('get.static.shopping_guide') }}">Hướng dẫn mua hàng</a></li>
                        <li><a href="{{  route('get.static.return_policy') }}">Chính sách đổi trả</a></li>
                    </ul>
                </div>
                <div class="item">
                    <div class="title">Địa Chỉ Cửa Hàng</div>
                    <!-- @if (isset($categoriesHot))
                        <ul>
                            @foreach($categoriesHot as $item)
                                <li>
                                    <a href="{{  route('get.category.list', $item->c_slug.'-'.$item->id) }}" title="{{ $item->c_name }}">{{ $item->c_name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif -->
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d24778.096693542997!2d107.20587687792575!3d10.828415318618507!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x317501c6c3137cd3%3A0x1e83cf96953287d8!2sLong%20Giao%2C%20Long%20Khanh%2C%20Dong%20Nai%2C%20Vietnam!5e1!3m2!1sen!2s!4v1732264192631!5m2!1sen!2s" width="300" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
               
            </div>
            <div class="bot">
                <div class="social">
                    <div class="title">KẾT NỐI VỚI CHÚNG TÔI</div>
                    <p>
                        <a href="" class="fa fa fa-youtube"></a>
                        <a href="" class="fa fa-facebook-official"></a>
                        <a href="" class="fa fa-twitter"></a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script async defer crossorigin="anonymous"
    src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v6.0&appId=3205159929509308&autoLogAppEvents=1">
</script>
