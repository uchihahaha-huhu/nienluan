@extends('layouts.app_master_admin')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Thêm mới tài khoản</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('admin.account_admin.index') }}"> Admin</a></li>
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
                            <div class="form-group {{ $errors->first('name') ? 'has-error' : '' }}">
                                <label for="name">Name <span class="text-danger">(*)</span></label>
                                <input type="text" class="form-control" value="{{ old('name') }}" name="name" placeholder="Name ...">
                                <span class="text-danger js-error-name"></span>
                                @if ($errors->first('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-8">
                            <div class="form-group {{ $errors->first('phone') ? 'has-error' : '' }}">
                                <label for="phone">Phone <span class="text-danger">(*)</span></label>
                                <input type="number" class="form-control" value="{{ old('phone') }}" name="phone" placeholder="Phone ...">
                                <span class="text-danger js-error-phone"></span>
                                @if ($errors->first('phone'))
                                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-8">
                            <div class="form-group {{ $errors->first('email') ? 'has-error' : '' }}">
                                <label for="email">Email <span class="text-danger">(*)</span></label>
                                <input type="email" class="form-control" value="{{ old('email') }}" name="email" placeholder="Email ...">
                                <span class="text-danger js-error-email"></span>
                                @if ($errors->first('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="level">Chức vụ <span class="text-danger">(*)</span></label>
                                <select class="form-control" name="level">
                                    <option value="1">Admin</option>
                                    <option value="2">Nhân viên</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-8">
                            <div class="form-group {{ $errors->first('password') ? 'has-error' : '' }}">
                                <label for="password">Password <span class="text-danger">(*)</span></label>
                                <input type="password" class="form-control" name="password" placeholder="********">
                                <span class="text-danger js-error-password"></span>
                                @if ($errors->first('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-8">
                            <div class="box-footer text-center">
                                <a href="{{ route('admin.account_admin.index') }}" class="btn btn-danger">
                                    Quay lại <i class="fa fa-undo"></i>
                                </a>
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
$(document).ready(function(){
    $('form[role="form"]').submit(function(e){
        // Xóa các lỗi cũ
        $('.text-danger.js-error').remove();

        let valid = true;

        // Lấy giá trị input
        let name = $.trim($('input[name="name"]').val());
        let phone = $.trim($('input[name="phone"]').val());
        let email = $.trim($('input[name="email"]').val());
        let password = $('input[name="password"]').val();

        // Regex kiểm tra email cơ bản
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // Kiểm tra name
        if(name === ''){
            $('input[name="name"]').after('<span class="text-danger js-error">Vui lòng nhập tên.</span>');
            valid = false;
        }

        // Kiểm tra phone: chỉ số, ít nhất 8 chữ số
        if(phone.length < 10 || !/^\d+$/.test(phone)){
            $('input[name="phone"]').after('<span class="text-danger js-error">Số điện thoại phải là số và ít nhất 10 chữ số.</span>');
            valid = false;
        }

        // Kiểm tra email
        if(!emailRegex.test(email)){
            $('input[name="email"]').after('<span class="text-danger js-error">Email không hợp lệ.</span>');
            valid = false;
        }

        // Kiểm tra password tối thiểu 8 ký tự
        if(password.length < 8){
            $('input[name="password"]').after('<span class="text-danger js-error">Mật khẩu phải có ít nhất 8 ký tự.</span>');
            valid = false;
        }

        if(!valid){
            e.preventDefault();
            // Cuộn lên vị trí lỗi đầu tiên giúp người dùng dễ thấy
            $('html, body').animate({
                scrollTop: $('.text-danger.js-error').first().offset().top - 100
            }, 500);
        }
    });
});
</script>
@stop
