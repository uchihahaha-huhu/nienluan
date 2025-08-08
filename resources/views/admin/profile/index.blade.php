@extends('layouts.app_master_admin')
@section('content')
<section class="content">
                                      @if(session('success'))
    <div class="alert alert-success alert-dismissible" style="margin-top: 15px;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible" style="margin-top: 15px;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ session('error') }}
    </div>
@endif
    <div class="row">
        <div class="col-md-3">
            <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="{{ pare_url_file($admin->avatar) }}" alt="User profile picture">
                    <h3 class="profile-username text-center">{{ $admin->name }}</h3>
                    <p class="text-muted text-center">{{ $admin->email }}</p>
                </div>
                <!-- /.box-body -->
            </div>

        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">Thông tin cá nhân</a></li>
                </ul>
                <div class="tab-content">

                    <div class="tab-pane active" id="settings">
                        <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{ route('admin.profile.update', $admin->id) }}">
                            @csrf
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" value="{{ old('name', $admin->name) }}">
                                    @if ($errors->first('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputEmail" class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" name="email" value="{{ old('email', $admin->email) }}">
                                    @if ($errors->first('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputPhone" class="col-sm-2 control-label">Phone</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="phone" value="{{ old('phone', $admin->phone) }}">
                                    @if ($errors->first('phone'))
                                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputAddress" class="col-sm-2 control-label">Address</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="address" value="{{ old('address', $admin->address) }}">
                                    @if ($errors->first('address'))
                                    <span class="text-danger">{{ $errors->first('address') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputClass" class="col-sm-2 control-label">Class</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="class" value="{{ old('class', $admin->class) }}">
                                    @if ($errors->first('class'))
                                    <span class="text-danger">{{ $errors->first('class') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputAvatar" class="col-sm-2 control-label">Avatar</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" name="avatar" accept=".png, .jpg, .jpeg">
                                    @if ($errors->first('avatar'))
                                    <span class="text-danger">{{ $errors->first('avatar') }}</span>
                                    @endif
                                    <small style="color: red; font-size: 12px; margin-top: 5px; display: block;">
                                        Chỉ chấp nhận định dạng PNG và JPG
                                    </small>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-danger">Cập nhật</button>
                                </div>
                            </div>
                        </form>

                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form.form-horizontal');

        form.addEventListener('submit', function(e) {
            // Ẩn hết lỗi cũ
            form.querySelectorAll('.text-danger').forEach(el => el.style.display = 'none');

            let valid = true;

            // Lấy giá trị các input
            const name = form.querySelector('input[name="name"]').value.trim();
            const email = form.querySelector('input[name="email"]').value.trim();
            const phone = form.querySelector('input[name="phone"]').value.trim();
            const address = form.querySelector('input[name="address"]').value.trim();
            const adminClass = form.querySelector('input[name="class"]').value.trim(); // "class" là từ khóa JS nên dùng adminClass

            const avatarInput = form.querySelector('input[name="avatar"]');
            const avatarFile = avatarInput.files[0];
            const allowedTypes = ['image/png', 'image/jpeg'];

            // Regex kiểm tra email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            // Regex kiểm tra phone chỉ toàn số, ít nhất 8 số
            const phoneRegex = /^\d{8,}$/;

            if (name === '') {
                const error = form.querySelector('input[name="name"]').parentElement.querySelector('.text-danger');
                error.textContent = 'Tên không được để trống';
                error.style.display = 'block';
                valid = false;
            }

            if (!emailRegex.test(email)) {
                const error = form.querySelector('input[name="email"]').parentElement.querySelector('.text-danger');
                error.textContent = 'Email không hợp lệ';
                error.style.display = 'block';
                valid = false;
            }

            if (!phoneRegex.test(phone)) {
                const error = form.querySelector('input[name="phone"]').parentElement.querySelector('.text-danger');
                error.textContent = 'Số điện thoại phải là số và tối thiểu 8 chữ số';
                error.style.display = 'block';
                valid = false;
            }

            if (address === '') {
                const error = form.querySelector('input[name="address"]').parentElement.querySelector('.text-danger');
                error.textContent = 'Địa chỉ không được để trống';
                error.style.display = 'block';
                valid = false;
            }

            if (adminClass === '') {
                const error = form.querySelector('input[name="class"]').parentElement.querySelector('.text-danger');
                error.textContent = 'Lớp học không được để trống';
                error.style.display = 'block';
                valid = false;
            }

            if (avatarFile) {
                if (!allowedTypes.includes(avatarFile.type)) {
                    const error = form.querySelector('input[name="avatar"]').parentElement.querySelector('.text-danger');
                    error.textContent = 'Định dạng avatar phải là PNG hoặc JPG';
                    error.style.display = 'block';
                    valid = false;
                }
            }

            if (!valid) {
                e.preventDefault();
            }
        });
    });
</script>
@stop

@stop