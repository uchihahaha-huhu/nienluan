<header class="header">
    <div class="topbar">
        <div class="container Df">
            @if (Auth::check())
            <a href="">Xin chào {{ Auth::user()->name }}</a>
            <a href="{{  route('get.user.dashboard') }}">Quản lý tài khoản</a>
            <a href="{{  route('get.logout') }}">Đăng xuất </a>
            @else
            <a href="{{  route('get.register') }}">Đăng ký</a>
            <a href="{{  route('get.login') }}">Đăng nhập</a>
            @endif
        </div>
    </div>
    <div class="header-navbar">
        <nav class="container Gr">
            <div class="header-logo">
                <a href="/"><img
                        src="https://png.pngtree.com/template/20190423/ourmid/pngtree-laptop-logo-template-design--laptop-logo-with-modern-frame-image_144738.jpg"
                        alt="logo" loading="lazy"></a>
            </div>
            <div class="header-menu">
                <ul class="menu-list">
                    <li><a class="active" href="/">Trang chủ</a></li>
                    <!-- <li><a href="coupon">Danh Mục</a></li> -->
                    <li><a href="{{route('get.product.list')}}">Sản phẩm</a></li>
                    <li><a href="{{route('get.blog.home')}}">Tin Tức</a></li>
                    <li><a href="{{route('get.contact')}}">Liên hệ</a></li>
                </ul>
            </div>
            <div class="header-action">
                <ul class="action">
                    <li>
                        <form action="{{ $link_search ?? route('get.product.list',['k' => Request::get('k')]) }}"
                            method="get">
                            <div class="position-relative">
                                <input type="text" name="k" value="{{ Request::get('k') }}" class="form-control"
                                    placeholder="Bạn muốn tìm gì?">
                                <button type="submit" class="btn-search">
                                    <i class="fal fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </li>
                    @if (Auth::check())
                    <li class="wishlist">
                        <a class="action-link" href="/account/favourite">
                            <i class="fal fa-heart"></i>
                        </a>
                    </li>
                    @else
                    <li class="wishlist">
                        <a class="action-link" href="/account/register">
                            <i class="fal fa-heart"></i>
                        </a>
                    </li>
                    @endif
                    <li class="shopping-cart">
                        <a class="action-link shopping-cart-btn" href="{{route('get.shopping.list')}}">
                            <i class="fal fa-shopping-cart"></i>
                            <span class="cartnum">{{ \Cart::count() }}</span>
                        </a>

                    </li>
                    <li class="my-account position-relative ">
                        <a class="action-link jSh" href="#">
                            <i class="fal fa-user"></i>
                        </a>
                        <div class="my-account-dropdown ">
                            <span class="title">Tiện ích</span>
                            <ul>
                                @if (Auth::check())
                                <li>
                                    <a href="{{ route('get.user.dashboard') }}">Tài khoản</a>
                                </li>
                                @else
                                <li>
                                    <a href="{{ url('/account/register') }}">Tài khoản</a>
                                </li>
                                @endif

                                <li>
                                    <a href="coming-soon">Bắt đầu trả hàng</a>
                                </li>
                                <li>
                                    <a href="coming-soon">Hỗ trợ</a>
                                </li>
                                <li>
                                    <a href="coming-soon">Ngôn ngữ</a>
                                </li>
                            </ul>
                        </div>

                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>