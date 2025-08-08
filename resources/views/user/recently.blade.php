@extends('layouts.app_master_user')
@section('css')
    <style>
        <?php $style = file_get_contents('css/user.min.css');echo $style;?>
    </style>
@stop
@section('content')
    <section>
        <div class="title">Danh sách bình luận của bạn</div>
           @if (isset($products))
        @foreach($products as $product)
            <div class="item">
                @include('frontend.components.product_item',['product' => $product])
            </div>
        @endforeach
    @endif
    </section>
@stop

@section('script')
    
@stop
