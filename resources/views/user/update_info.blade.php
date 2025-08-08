@extends('layouts.app_master_user')
@section('css')
<style>
    <?php
    $style = file_get_contents('css/user.min.css');
    echo $style;
    ?>
    /* Thêm style đơn giản cho form đổi mật khẩu */
    .change-password-section {
        margin-top: 40px;
        border-top: 1px solid #ddd;
        padding-top: 20px;
    }
</style>
@stop


@section('content')
<section>
    <div class="title">Cập nhật thông tin</div>
    <form id="form-update-user" action="" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        <div class="form-group">
            <label for="">Name</label>
            <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" placeholder="">
            <small class="text-danger error-name" style="display:none;"></small>
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Email</label>
            <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}"
                   placeholder="Enter email">
            <small class="text-danger error-email" style="display:none;"></small>
        </div>
        <div class="form-group">
            <label for="">Phone</label>
            <input type="number" name="phone" class="form-control" value="{{ Auth::user()->phone }}"
                   placeholder="Enter phone">
            <small class="text-danger error-phone" style="display:none;"></small>
        </div>
        <div class="form-group">
            <label for="">Address</label>
            <input type="text" name="address" class="form-control" value="{{ Auth::user()->address }}"
                   placeholder="Địa chỉ">
            <small class="text-danger error-address" style="display:none;"></small>
        </div>
        <div class="form-group">
            <div class="upload-btn-wrapper">
                <button class="btn-upload" type="button">Tải avatar lên</button>
                <input type="file" name="avatar" />
            </div>
            <!-- Dòng chữ đỏ cố định dưới nút tải avatar -->
            <small style="color: red; font-size: 12px; margin-top: 5px; display: block; font-style: italic">
                Chỉ chấp nhận định dạng PNG và JPG
            </small>
            <!-- Lỗi hiện khi validate -->
            <small class="text-danger error-avatar" style="display:none;"></small>
        </div>


        <button type="submit" class="btn btn-blue btn-md">Cập nhật</button>
    </form>
</section>

{{-- Form đổi mật khẩu --}}
<section class="change-password-section">
    <div class="title">Đổi mật khẩu</div>
    <form id="form-change-password" action="{{ route('post.change_password') }}" method="POST" novalidate>
        @csrf
        <div class="form-group">
            <label for="current_password">Mật khẩu hiện tại <span class="cRed">(*)</span></label>
            <input type="password" name="current_password" id="current_password" class="form-control" placeholder="Nhập mật khẩu hiện tại" required>
            <small class="text-danger error-current-password" style="display:none;"></small>
        </div>
        <div class="form-group">
            <label for="password">Mật khẩu mới <span class="cRed">(*)</span></label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Nhập mật khẩu mới" required minlength="8">
            <small class="text-danger error-password" style="display:none;"></small>
        </div>
        <div class="form-group">
            <label for="password_confirmation">Xác nhận mật khẩu mới <span class="cRed">(*)</span></label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu mới" required minlength="8">
            <small class="text-danger error-password-confirmation" style="display:none;"></small>
        </div>
        <button type="submit" class="btn btn-blue btn-md">Đổi mật khẩu</button>
    </form>
</section>
@stop


@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formUpdateUser = document.getElementById('form-update-user');
        const formChangePassword = document.getElementById('form-change-password');

        // Validation form cập nhật thông tin (giữ nguyên code)
        formUpdateUser.addEventListener('submit', function(e) {
            document.querySelectorAll('.text-danger').forEach(el => el.style.display = 'none');
            let valid = true;

            const name = formUpdateUser.querySelector('input[name="name"]').value.trim();
            const email = formUpdateUser.querySelector('input[name="email"]').value.trim();
            const phone = formUpdateUser.querySelector('input[name="phone"]').value.trim();
            const address = formUpdateUser.querySelector('input[name="address"]').value.trim();
            const avatarInput = formUpdateUser.querySelector('input[name="avatar"]');
            const avatarFile = avatarInput.files[0];
            const allowedExtensions = ['image/jpeg', 'image/png'];

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const phoneRegex = /^\d{8,}$/;

            if (name.length === 0) {
                const errorName = formUpdateUser.querySelector('.error-name');
                errorName.textContent = "Tên không được để trống";
                errorName.style.display = 'block';
                valid = false;
            }

            if (!emailRegex.test(email)) {
                const errorEmail = formUpdateUser.querySelector('.error-email');
                errorEmail.textContent = "Email không hợp lệ";
                errorEmail.style.display = 'block';
                valid = false;
            }

            if (!phoneRegex.test(phone)) {
                const errorPhone = formUpdateUser.querySelector('.error-phone');
                errorPhone.textContent = "Số điện thoại phải là số và ít nhất 8 chữ số";
                errorPhone.style.display = 'block';
                valid = false;
            }

            if (address.length === 0) {
                const errorAddress = formUpdateUser.querySelector('.error-address');
                errorAddress.textContent = "Địa chỉ không được để trống";
                errorAddress.style.display = 'block';
                valid = false;
            }

            if (avatarFile) {
                if (!allowedExtensions.includes(avatarFile.type)) {
                    const errorAvatar = formUpdateUser.querySelector('.error-avatar');
                    errorAvatar.textContent = "Chỉ chấp nhận định dạng PNG và JPG";
                    errorAvatar.style.display = 'block';
                    valid = false;
                }
            }

            if (!valid) e.preventDefault();
        });

        // Validation form đổi mật khẩu
        formChangePassword.addEventListener('submit', function(e) {
            document.querySelectorAll('.text-danger').forEach(el => el.style.display = 'none');
            let valid = true;

            const currentPassword = formChangePassword.querySelector('input[name="current_password"]').value.trim();
            const password = formChangePassword.querySelector('input[name="password"]').value.trim();
            const passwordConfirmation = formChangePassword.querySelector('input[name="password_confirmation"]').value.trim();

            if (currentPassword.length === 0) {
                const errorCurrentPwd = formChangePassword.querySelector('.error-current-password');
                errorCurrentPwd.textContent = "Vui lòng nhập mật khẩu hiện tại";
                errorCurrentPwd.style.display = 'block';
                valid = false;
            }

            if (password.length < 8) {
                const errorPwd = formChangePassword.querySelector('.error-password');
                errorPwd.textContent = "Mật khẩu mới phải ít nhất 8 ký tự";
                errorPwd.style.display = 'block';
                valid = false;
            }

            if (password !== passwordConfirmation) {
                const errorPwdConfirm = formChangePassword.querySelector('.error-password-confirmation');
                errorPwdConfirm.textContent = "Mật khẩu xác nhận không khớp";
                errorPwdConfirm.style.display = 'block';
                valid = false;
            }

            if (!valid) e.preventDefault();
        });
    });
</script>
@stop
