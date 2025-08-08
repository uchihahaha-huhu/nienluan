<div class="top" style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
    <button type="button" onclick="history.back();" class="btn btn-primary d-flex align-items-center" style="gap: 5px;">
        <i class="fas fa-arrow-left"></i>
        Quay lại
    </button>
    <a href="#" class="main-title" style="font-weight: bold; font-size: 1.5rem;">SẢN PHẨM VỪA XEM</a>
</div>

<div class="bot">
    @if (isset($products))
        @foreach($products as $product)
            <div class="item">
                @include('frontend.components.product_item',['product' => $product])
            </div>
        @endforeach
    @endif
</div>

<div class="bot">
    @if (isset($products))
        @foreach($products as $product)
            <div class="item">
                @include('frontend.components.product_item',['product' => $product])
            </div>
        @endforeach
    @endif
</div>
