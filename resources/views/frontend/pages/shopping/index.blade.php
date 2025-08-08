@extends('layouts.app_master_frontend')
@section('css')
<link rel="stylesheet" href="{{ asset('css/cart.min.css') }}">
<!-- Bootstrap CSS thêm nếu project của bạn chưa có -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
@stop

@section('content')
<div class="container cart my-4">
    <div class="row">
        <div class="col-md-7">
            <div class="list">
                <div class="title">THÔNG TIN GIỎ HÀNG</div>
                <div class="list__content">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 100px;"></th>
                                <th style="width: 30%">Sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shopping as $key => $item)
                            <tr>
                                <td>
                                    <a href="{{ route('get.product.detail',\Str::slug($item->name).'-'.$item->id) }}"
                                        title="{{ $item->name }}" class="avatar image contain">
                                        <img alt="" src="{{ pare_url_file($item->options->image) }}" class="lazyload">
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('get.product.detail',\Str::slug($item->name).'-'.$item->id) }}"><strong>{{ $item->name }}</strong></a>
                                </td>
                                <td>
                                    <p><b>{{ number_format($item->price,0,',','.') }} đ</b></p>
                                    <p>
                                        @if ($item->options->price_old)
                                        <span style="text-decoration: line-through;">{{ number_format(number_price($item->options->price_old),0,',','.') }} đ</span>
                                        <span class="sale">- {{ $item->options->sale }} %</span>
                                        @endif
                                    </p>
                                </td>
                                <td>
                                    <div class="qty_number">
                                        <input type="number" min="1" class="input_quantity" name="quantity_14692" value="{{ $item->qty }}" readonly>

                                        <p data-price="{{ $item->price }}" data-url="{{  route('ajax_get.shopping.update', $key) }}" data-id-product="{{  $item->id }}">
                                            <span class="js-increase">+</span>
                                            <span class="js-reduction">-</span>
                                        </p>
                                        <a href="{{  route('get.shopping.delete', $key) }}" class="js-delete-item btn-action-delete"><i class="la la-trash"></i></a>
                                    </div>
                                </td>
                                <td>
                                    <span class="js-total-item">{{ number_format($item->price * $item->qty,0,',','.') }} đ</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mã giảm giá + Tổng tiền -->
                <div class="discount-code-section" style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #ddd;">
                    <form id="discount-form" action="javascript:void(0);" style="margin-bottom: 0;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="discount_code_input" style="font-weight: 600; display: block; margin-bottom: 8px;">Áp dụng mã giảm giá</label>
                            <div class="row" style="display: flex; align-items: center; gap: 10px;">
                                <div class="col-md-8" style="flex: 1;">
                                    <input id="discount_code_input"
                                        type="text"
                                        class="discount_code"
                                        placeholder="Mã giảm giá"
                                        style="
                                        width: 100%; 
                                        padding: 10px 12px; 
                                        font-size: 14px; 
                                        border: 1px solid #ccc; 
                                        border-radius: 4px;
                                        box-sizing: border-box;
                                    ">
                                </div>
                                <div class="col-md-4" style="display: flex; gap: 10px;">
                                    <button type="button"
                                        class="btn-cart-discount"
                                        style="
                                        flex: 1;
                                        padding: 10px;
                                        background-color: #6f42c1; 
                                        border: none; 
                                        color: white; 
                                        font-weight: 600; 
                                        font-size: 14px; 
                                        border-radius: 4px;
                                        cursor: pointer;
                                        transition: background-color 0.3s ease;
                                    "
                                        onmouseover="this.style.backgroundColor='#5936b9'"
                                        onmouseout="this.style.backgroundColor='#6f42c1'">
                                        Áp dụng voucher
                                    </button>
                                    <button type="button"
                                        class="btn-cart-discount-remove"
                                        style="
                                        flex: 1;
                                        padding: 10px;
                                        background-color: #dc3545; 
                                        border: none; 
                                        color: white; 
                                        font-weight: 600; 
                                        font-size: 14px; 
                                        border-radius: 4px;
                                        cursor: pointer;
                                        display: none;
                                        transition: background-color 0.3s ease;
                                    "
                                        onmouseover="this.style.backgroundColor='#bb2d3b'"
                                        onmouseout="this.style.backgroundColor='#dc3545'">
                                        Bỏ sử dụng voucher
                                    </button>
                                </div>
                            </div>
                            <p id="discount-message"
                                style="
                                color: red; 
                                margin-top: 5px; 
                                display: none;
                                font-weight: 600;
                            ">
                            </p>
                        </div>
                    </form>

                    <!-- Tổng tiền xuống đây -->
                    <p style="margin-top: 10px; font-weight: bold; font-size: 18px;">
                        Tổng tiền : <span id="sub-total">{{ \Cart::subtotal(0) }} đ</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="customer">
                <div class="title">THÔNG TIN ĐẶT HÀNG</div>
                <div class="customer__content">
                    <form class="from_cart_register" action="{{ route('post.shopping.pay') }}" method="post" id="form-order">
                        @csrf
                        <div class="form-group">
                            <label for="name">Họ và tên <span class="cRed">(*)</span></label>
                            <input name="tst_name" id="name" required value="{{ get_data_user('web','name') }}" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="phone">Điện thoại <span class="cRed">(*)</span></label>
                            <input name="tst_phone" id="phone" required value="{{ get_data_user('web','phone') }}" type="text" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="email">Email <span class="cRed">(*)</span></label>
                            <input name="tst_email" id="email" required value="{{ get_data_user('web','email') }}" type="text" class="form-control">
                        </div>
                                                <div class="form-group position-relative">
                            <label for="address">Địa chỉ <span class="cRed">(*)</span></label>
                            <input name="tst_address" id="address" required value="{{ get_data_user('web','address') }}" type="text" class="form-control" readonly>
                            <button type="button" id="btn-select-address" class="btn btn-secondary mt-2">Chọn địa chỉ giao hàng</button>
                        </div>
                        <div class="form-group">
                            <label for="note">Ghi chú thêm</label>
                            <textarea name="tst_note" id="note" cols="3" style="min-height: 100px;" rows="2" class="form-control"></textarea>
                        </div>

                        <div class="btn-buy">
                            <button class="buy1 btn btn-purple {{ \Auth::id() ? '' : 'js-show-login' }}" style="width: 100%" type="submit" name="pay" value="online">
                                Thanh toán khi nhận hàng
                            </button>
                            <button class="buy1 btn btn-primary {{ \Auth::id() ? '' : 'js-show-login' }}" style="width: 100%;margin-top: 20px" type="submit" name="pay" value="transfer">
                                Thanh toán Online
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal chọn địa chỉ giao hàng -->
<div class="modal fade" id="modalAddressBook" tabindex="-1" role="dialog" aria-labelledby="modalAddressBookLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="max-width: 1500px; width: 90%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Danh sách địa chỉ giao hàng</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <button class="btn btn-success mb-3" id="btn-add-new-address">Thêm địa chỉ mới</button>
        <table class="table table-bordered" id="address-book-table">
          <thead>
            <tr>
              <th>Tên</th>
              <th>Điện thoại</th>
              <th>Email</th>
              <th>Địa chỉ</th>
              <th>Mặc định</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            <!-- Data sẽ load ajax -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal thêm/sửa địa chỉ -->
<div class="modal fade" id="modalAddressForm" tabindex="-1" role="dialog" aria-labelledby="modalAddressFormLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <form id="form-address" class="modal-content">
      @csrf
      <input type="hidden" id="address_id">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAddressFormLabel">Thêm địa chỉ mới</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <div class="form-group">
          <label>Tên <span class="text-danger">*</span></label>
          <input type="text" id="address_name" name="name" class="form-control" required>
        </div>

        <div class="form-group">
          <label>Điện thoại</label>
          <input type="text" id="address_phone" name="phone" class="form-control">
        </div>

        <div class="form-group">
          <label>Email</label>
          <input type="email" id="address_email" name="email" class="form-control">
        </div>

        <div class="form-group">
          <label>Địa chỉ <span class="text-danger">*</span></label>
          <textarea id="address_address" name="address" class="form-control" required></textarea>
        </div>

        <div class="form-group form-check">
          <input type="checkbox" id="address_is_default" name="is_default" class="form-check-input">
          <label for="address_is_default" class="form-check-label">Mặc định</label>
        </div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Lưu</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
      </div>
    </form>
  </div>
</div>

@stop

@section('script')
<script src="{{ asset('js/cart.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.min.js') }}"></script> <!-- nếu chưa có jquery -->
<!-- Bootstrap JS nếu chưa có -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
$(function() {
    // Mã giảm giá đã có sẵn trong bạn, giữ nguyên code cũ!
    const discountInput = $('.discount_code');
    const btnApply = $('.btn-cart-discount');
    const btnRemove = $('.btn-cart-discount-remove');
    const messageEl = $('#discount-message');
    const subTotalEl = $('#sub-total');
    const LOCAL_STORAGE_KEY = 'applied_discount_code';

    function updateButtonVisibility() {
        const discountCodeInStorage = localStorage.getItem(LOCAL_STORAGE_KEY);
        if (discountCodeInStorage) {
            discountInput.val(discountCodeInStorage).prop('disabled', true);
            btnRemove.show().prop('disabled', false);
            btnApply.prop('disabled', true);
        } else {
            discountInput.val('').prop('disabled', false);
            btnRemove.hide().prop('disabled', true);
            btnApply.prop('disabled', false);
        }
    }

    function loadDiscountState() {
        const discountCodeInStorage = localStorage.getItem(LOCAL_STORAGE_KEY);
        if (discountCodeInStorage) {
            $.ajax({
                url: '{{ route('ajax_get.update.cart.discount') }}',
                dataType: 'json',
                data: { discount_code: discountCodeInStorage },
                success: function(results) {
                    if (results.type === 'success') {
                        subTotalEl.text(results.totalMoney + ' đ');
                        discountInput.val(discountCodeInStorage).prop('disabled', true);
                        btnRemove.show().prop('disabled', false);
                        btnApply.prop('disabled', true);
                        messageEl.css('color', 'green').text('Mã giảm giá được áp dụng!').show();
                    } else {
                        localStorage.removeItem(LOCAL_STORAGE_KEY);
                        discountInput.val('').prop('disabled', false);
                        btnRemove.hide().prop('disabled', true);
                        btnApply.prop('disabled', false);
                        messageEl.text('').hide();
                    }
                },
                error: function() {
                    messageEl.css('color', 'red').text('Lỗi khi tải mã giảm giá.').show();
                }
            });
        } else {
            discountInput.val('').prop('disabled', false);
            btnRemove.hide().prop('disabled', true);
            btnApply.prop('disabled', false);
            messageEl.text('').hide();
        }
    }
    
    loadDiscountState();


    btnApply.click(function () {
        let discount_code = discountInput.val().trim();
        if (!discount_code) {
            messageEl.css('color', 'red').text('Vui lòng nhập mã giảm giá').show();
            return;
        }
        messageEl.text('').hide();

        $.ajax({
            url: '{{ route('ajax_get.update.cart.discount') }}',
            dataType: 'json',
            data: { discount_code: discount_code },
            success: function(results) {
                if (results.type === 'success') {
                    subTotalEl.text(results.totalMoney + ' đ');
                    discountInput.prop("disabled", true);
                    btnRemove.show().prop('disabled', false);
                    btnApply.prop('disabled', true);

                    messageEl.css('color', 'green').text('Mã giảm giá được áp dụng thành công!').show();

                    localStorage.setItem(LOCAL_STORAGE_KEY, discount_code);
                } else {
                    messageEl.css('color', 'red').text(results.text || 'Mã giảm giá không hợp lệ hoặc đã hết hạn!').show();
                }
            },
            error: function() {
                messageEl.css('color', 'red').text('Đã có lỗi xảy ra. Vui lòng thử lại sau!').show();
            }
        });
    });

    btnRemove.click(function () {
        $.ajax({
            url: '{{ route('ajax_get.remove.cart.discount') }}',
            dataType: 'json',
            success: function(results) {
                if (results.type === 'success') {
                    subTotalEl.text(results.totalMoney + ' đ');
                    discountInput.prop("disabled", false).val('');
                    btnRemove.hide().prop("disabled", true);
                    btnApply.prop("disabled", false);

                    messageEl.css('color', 'green').text('Đã bỏ sử dụng mã giảm giá').show();

                    localStorage.removeItem(LOCAL_STORAGE_KEY);
                } else {
                    messageEl.css('color', 'red').text('Lỗi khi bỏ mã giảm giá').show();
                }
            },
            error: function() {
                messageEl.css('color', 'red').text('Đã có lỗi xảy ra. Vui lòng thử lại sau!').show();
            }
        });
    });

    // --- Phần quản lý địa chỉ giao hàng ---
    const userId = {{ \Auth::id() ?? 'null' }};
    if (!userId) {
        alert('Bạn cần đăng nhập để sử dụng chức năng địa chỉ giao hàng.');
        return;
    }

    const modalAddressBook = $('#modalAddressBook');
    const modalAddressForm = $('#modalAddressForm');
    const tbodyAddressBook = $('#address-book-table tbody');
    const btnSelectAddress = $('#btn-select-address');

    const formAddress = $('#form-address');
    const addressIdInput = $('#address_id');
    const inputName = $('#address_name');
    const inputPhone = $('#address_phone');
    const inputEmail = $('#address_email');
    const inputAddress = $('#address_address');
    const inputIsDefault = $('#address_is_default');

    const inputOrderName = $('#name');
    const inputOrderPhone = $('#phone');
    const inputOrderEmail = $('#email');
    const inputOrderAddress = $('#address');

    const apiGetDefaultAddress = `/address-book/user/${userId}/default`;
    const apiGetAddresses = `/address-book/user/${userId}`;
    const apiSetDefaultAddress = `/address-book/set-default`;
    const apiCreateAddress = `/address-book/create`;
    const apiUpdateAddress = (id) => `/address-book/update/${id}`;
    const apiDeleteAddress = (id) => `/address-book/delete/${id}`;

    function loadDefaultAddress() {
        $.getJSON(apiGetDefaultAddress, function(res) {
            if (res.success && res.data) {
                const addr = res.data;
                inputOrderName.val(addr.name);
                inputOrderPhone.val(addr.phone);
                inputOrderEmail.val(addr.email);
                inputOrderAddress.val(addr.address);
            }
        });
    }

    function loadAddressList() {
        tbodyAddressBook.html('<tr><td colspan="6">Đang tải...</td></tr>');
        $.getJSON(apiGetAddresses, function(res) {
            if (res.success) {
                if (res.data.length === 0) {
                    tbodyAddressBook.html('<tr><td colspan="6" class="text-center">Chưa có địa chỉ nào</td></tr>');
                } else {
                    tbodyAddressBook.empty();
                    $.each(res.data, function(i, addr) {
                        const checked = addr.is_default ? 'checked disabled' : '';
                        tbodyAddressBook.append(`
                            <tr data-id="${addr.id}">
                                <td>${addr.name}</td>
                                <td>${addr.phone || ''}</td>
                                <td>${addr.email || ''}</td>
                                <td>${addr.address}</td>
                                <td class="text-center">
                                    <input type="radio" name="default_address" value="${addr.id}" ${checked} title="${addr.is_default ? 'Địa chỉ mặc định' : ''}">
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary btn-edit-address" data-id="${addr.id}">Sửa</button>
                                    <button class="btn btn-sm btn-danger btn-delete-address" data-id="${addr.id}">Xóa</button>
                                </td>
                            </tr>
                        `);
                    });
                }
            } else {
                tbodyAddressBook.html('<tr><td colspan="6">Lỗi tải địa chỉ</td></tr>');
            }
        }).fail(function() {
            tbodyAddressBook.html('<tr><td colspan="6">Lỗi tải địa chỉ</td></tr>');
        });
    }

    btnSelectAddress.on('click', function() {
        loadAddressList();
        modalAddressBook.modal('show');
    });

    tbodyAddressBook.on('change', 'input[name="default_address"]', function() {
        const addressId = $(this).val();
        if (!addressId) return;
        $.ajax({
            url: apiSetDefaultAddress,
            method: 'POST',
            data: {
                address_id: addressId,
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                if (res.success) {
                    loadAddressList();
                    loadDefaultAddress();
                } else {
                    alert(res.message || 'Cập nhật địa chỉ mặc định thất bại');
                }
            },
            error: function() {
                alert('Lỗi không xác định khi cập nhật địa chỉ mặc định');
            }
        });
    });

    $('#btn-add-new-address').on('click', function() {
        addressIdInput.val('');
        formAddress[0].reset();
        inputIsDefault.prop('checked', false);
        modalAddressForm.find('.modal-title').text('Thêm địa chỉ mới');
        modalAddressForm.modal('show');
    });

    tbodyAddressBook.on('click', '.btn-edit-address', function() {
        const id = $(this).data('id');

        $.getJSON(apiGetAddresses, function(res) {
            if (res.success) {
                const address = res.data.find(a => a.id == id);
                if (address) {
                    addressIdInput.val(address.id);
                    inputName.val(address.name);
                    inputPhone.val(address.phone);
                    inputEmail.val(address.email);
                    inputAddress.val(address.address);
                    inputIsDefault.prop('checked', address.is_default);
                    modalAddressForm.find('.modal-title').text('Chỉnh sửa địa chỉ');
                    modalAddressForm.modal('show');
                }
            }
        });
    });

    tbodyAddressBook.on('click', '.btn-delete-address', function() {
        const id = $(this).data('id');
        if (confirm('Bạn có muốn xóa địa chỉ này không?')) {
            $.ajax({
                url: apiDeleteAddress(id),
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    if (res.success) {
                        loadAddressList();
                        loadDefaultAddress();
                    } else {
                        alert(res.message || 'Xóa thất bại');
                    }
                },
                error: function() {
                    alert('Lỗi xóa địa chỉ');
                }
            });
        }
    });

    formAddress.on('submit', function(e) {
        e.preventDefault();

        const id = addressIdInput.val();
        const url = id ? apiUpdateAddress(id) : apiCreateAddress;
        const method = id ? 'PUT' : 'POST';

        const formData = {
            user_id: userId,
            name: inputName.val(),
            phone: inputPhone.val(),
            email: inputEmail.val(),
            address: inputAddress.val(),
            is_default: inputIsDefault.is(':checked') ? 1 : 0,
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            url: url,
            method: method,
            data: formData,
            success: function(res) {
                if (res.success) {
                    modalAddressForm.modal('hide');
                    loadAddressList();
                    loadDefaultAddress();
                } else {
                    alert(res.message || 'Lỗi lưu địa chỉ');
                }
            },
            error: function(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    let errors = xhr.responseJSON.errors;
                    let msg = '';
                    for (let k in errors) {
                        msg += errors[k].join(', ') + '\n';
                    }
                    alert(msg);
                } else {
                    alert('Lỗi lưu địa chỉ');
                }
            }
        });
    });

    loadDefaultAddress();
});
</script>
@stop
