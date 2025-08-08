@extends('layouts.app_master_admin')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Thêm mới </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"> Create</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <div class="box-body">
                    <form role="form" action="" method="POST" novalidate>
                        @csrf
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="w_qty">Số lượng <span class="text-danger">(*)</span></label>
                                <input type="number" class="form-control" name="w_qty" value="{{ $warehouse->w_qty }}" placeholder="">
                                <span class="text-danger js-error-w_qty"></span>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="w_price">Giá <span class="text-danger">(*)</span></label>
                                <input type="number" class="form-control" name="w_price" value="{{ $warehouse->w_price }}" placeholder="">
                                <span class="text-danger js-error-w_price"></span>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="w_product_id">Sản phẩm <span class="text-danger">(*)</span></label>
                                <select name="w_product_id" class="form-control" id="">
                                    <option value="0">__ROOT__</option>
                                    @foreach($products as $item)
                                        <option value="{{ $item->id }}" {{ $warehouse->w_product_id == $item->id ? "selected" : "" }}>{{ $item->pro_name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger js-error-w_product_id"></span>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="box-footer text-center">
                                <button type="submit" class="btn btn-success">Lưu dữ liệu <i class="fa fa-save"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.box -->
    </section>
    <!-- /.content -->
@stop

@section('script')
<script>
$(document).ready(function() {
    $('form[role="form"]').submit(function(e) {
        // Xóa các thông báo lỗi cũ
        $('.js-error-w_qty').text('');
        $('.js-error-w_price').text('');
        $('.js-error-w_product_id').text('');

        let valid = true;

        // Lấy giá trị
        let w_qty = $.trim($('input[name="w_qty"]').val());
        let w_price = $.trim($('input[name="w_price"]').val());
        let w_product_id = $('select[name="w_product_id"]').val();

        // Validate w_qty: số nguyên >= 1
        if (w_qty === '' || isNaN(w_qty) || parseInt(w_qty) < 1 || !Number.isInteger(Number(w_qty))) {
            $('.js-error-w_qty').text('Số lượng phải là số nguyên lớn hơn hoặc bằng 1.');
            valid = false;
        }

        // Validate w_price: số > 0
        if (w_price === '' || isNaN(w_price) || parseFloat(w_price) <= 0) {
            $('.js-error-w_price').text('Giá phải là số lớn hơn 0.');
            valid = false;
        }

        // Validate w_product_id: chọn sản phẩm khác 0
        if (!w_product_id || w_product_id == 0) {
            $('.js-error-w_product_id').text('Vui lòng chọn sản phẩm.');
            valid = false;
        }

        if (!valid) {
            e.preventDefault(); // Ngăn form submit nếu có lỗi
            // Cuộn tới lỗi đầu tiên
            $('html, body').animate({
                scrollTop: $('.text-danger:visible').first().offset().top - 100
            }, 500);
        }
    });
});
</script>
@stop
