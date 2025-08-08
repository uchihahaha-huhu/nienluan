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
                    <form role="form" action="" method="POST">
                        @csrf
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="w_qty">Số lượng <span class="text-danger">(*)</span></label>
                                <input type="number" class="form-control" name="w_qty" placeholder="">
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="w_price">Giá <span class="text-danger">(*)</span></label>
                                <input type="number" class="form-control" name="w_price" placeholder="">
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="w_product_id">Sản phẩm <span class="text-danger">(*)</span></label>
                                <select name="w_product_id" class="form-control" id="">
                                    <option value="0">__ROOT__</option>
                                    @foreach($products as $item)
                                        <option value="{{ $item->id }}">{{ $item->pro_name }}</option>
                                    @endforeach
                                </select>
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
        $('.text-danger.js-error').remove();

        let valid = true;

        let qty = $.trim($('input[name="w_qty"]').val());
        let price = $.trim($('input[name="w_price"]').val());
        let product_id = $('select[name="w_product_id"]').val();

        if (qty === '' || isNaN(qty) || parseInt(qty) < 1 || !Number.isInteger(Number(qty))) {
            $('input[name="w_qty"]').after('<span class="text-danger js-error">Số lượng phải là số nguyên lớn hơn hoặc bằng 1.</span>');
            valid = false;
        }

        if (price === '' || isNaN(price) || parseFloat(price) <= 0) {
            $('input[name="w_price"]').after('<span class="text-danger js-error">Giá phải là số lớn hơn 0.</span>');
            valid = false;
        }

        if (!product_id || product_id == 0) {
            $('select[name="w_product_id"]').after('<span class="text-danger js-error">Vui lòng chọn sản phẩm.</span>');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $('.text-danger.js-error').first().offset().top - 100
            }, 500);
        }
    });
});
</script>
@stop
