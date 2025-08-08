<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="robots" content="noindex, nofollow" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

    <title>{{ $title_page ?? "Đồ án tốt nghiệp" }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="stylesheet" href="{{ asset('fontawesome-5-pro/css/all.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/header.css') }}" />
    <link rel="icon" sizes="32x32" type="image/png" href="{{ asset('ico.png') }}" />
    @if(session('toastr'))
    <script>
        var TYPE_MESSAGE = "{{session('toastr.type')}}";
        var MESSAGE = "{{session('toastr.message')}}";
    </script>
    @endif
    @yield('css')

    <style>
        /* Chat popup container - mặc định đóng (ẩn) */
        #chat-popup {
            position: fixed;
            bottom: 70px;
            right: 20px;
            width: 400px;
            max-width: 95vw;
            height: 600px;
            max-height: 90vh;
            background: white;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
            display: none;
            /* Mặc định đóng */
            flex-direction: column;
            overflow: hidden;
            z-index: 10000;
        }

        /* Header bar */
        #chat-popup>div:first-child {
            background: #007bff;
            color: white;
            padding: 12px 16px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 18px;
        }

        /* Scrollable message area */
        #chat-messages {
            flex: 1 1 auto;
            overflow-y: auto;
            padding: 12px 16px;
            font-size: 15px;
            background-color: #f9f9f9;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        /* Chat input form cố định dưới popup, luôn hiện */
        #chat-form {
            flex: 0 0 auto;
            display: flex;
            border-top: 1px solid #ddd;
            padding: 12px 16px;
            background-color: white;
        }

        #chat-form input[type="text"] {
            flex: 1;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 15px;
            outline: none;
        }

        #chat-form button {
            margin-left: 10px;
            background: #007bff;
            border: none;
            color: white;
            padding: 10px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 15px;
        }

        /* Message bubbles */
        .chat-message {
            display: flex;
            margin-bottom: 12px;
        }

        .chat-message.user {
            justify-content: flex-end;
        }

        .chat-message.bot {
            justify-content: flex-start;
        }

        .chat-message .message-content {
            border-radius: 16px;
            max-width: 85%;
            word-wrap: break-word;
            font-size: 15px;
            line-height: 1.4;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            padding: 12px 16px;
        }

        .chat-message.user .message-content {
            background: #007bff;
            color: #fff;
        }

        .chat-message.bot .message-content {
            background: #e2e6ea;
            color: #212529;
        }
    </style>
</head>

<body>
    @include('frontend.components.header')

    @yield('content')

    @include('frontend.components.footer')

    <script>
        var DEVICE = '{{ device_agent() }}'
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Icon chat cố định góc phải -->
    <div id="chat-icon" style="
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        background-color: #007bff;
        color: white;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        font-size: 30px;
    ">
        <i class="fas fa-comments"></i>
    </div>

    <!-- Popup chat -->
    <div id="chat-popup">
        <div>
            Chat với hệ thống
            <button id="chat-close" type="button" style="
                background:none;
                border:none;
                font-size: 24px;
                color:white;
                cursor:pointer;
                line-height: 1;
                font-weight: bold;
            ">&times;</button>
        </div>

        <div id="chat-messages">
            <!-- Tin nhắn chat sẽ hiển thị ở đây -->
        </div>

        <form id="chat-form">
            <input type="text" id="chat-input" placeholder="Nhập câu hỏi..." required />
            <button type="submit">Gửi</button>
        </form>
    </div>

    <script>
        $(function() {
            const chatIcon = $('#chat-icon');
            const chatPopup = $('#chat-popup');
            const chatClose = $('#chat-close');
            const chatForm = $('#chat-form');
            const chatInput = $('#chat-input');
            const chatMessages = $('#chat-messages');

            // Mặc định đóng: display:none trong CSS
            // Hiện popup: set display:flex để giữ flex container và input cố định
            chatIcon.on('click', function() {
                if (chatPopup.css('display') === 'none') {
                    chatPopup.css('display', 'flex');
                    chatInput.focus();
                } else {
                    chatPopup.css('display', 'none');
                }
            });

            chatClose.on('click', function() {
                chatPopup.css('display', 'none');
            });

            function addMessage(text, isUser = true) {
                const messageClass = isUser ? 'user' : 'bot';
                const messageHtml = `<div class="chat-message ${messageClass}">
                    <div class="message-content">${text}</div>
                </div>`;
                chatMessages.append(messageHtml);
                chatMessages.scrollTop(chatMessages[0].scrollHeight);
            }

            function pareUrlFile(image, folder = '') {
                if (!image) {
                    return '/images/no-image.jpg';
                }
                const explode = image.split('__');

                if (explode.length > 0) {
                    // Chuỗi thời gian có format: yyyy_mm_dd hoặc tương tự, ví dụ "2023_07_30"
                    const timeStr = explode[0].replace(/_/g, '/');
                    const date = new Date(timeStr);
                    const year = date.getFullYear();
                    // getMonth() trả về 0-based, nên cần +1 và thêm số 0 nếu < 10
                    const month = (date.getMonth() + 1).toString().padStart(2, '0');
                    const day = date.getDate().toString().padStart(2, '0');

                    return `/uploads${folder}/${year}/${month}/${day}/${image}`;
                }
                return image; // fallback trả về tên file gốc
            }

            // Viết hàm renderProductCard nếu cần: 
            function renderProductCard(p) {
                const imageUrl = pareUrlFile(p.pro_avatar);
                return `
    <a href="/san-pham/${p.pro_slug}-${p.id}" target="_blank" rel="noopener noreferrer" style="
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
        border: 1px solid #ddd;
        border-radius: 8px;
        margin-bottom: 12px;
        text-decoration: none;
        color: inherit;
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: box-shadow 0.3s ease;
        padding: 8px;
    " onmouseover="this.style.boxShadow='0 4px 15px rgba(0,0,0,0.15)';" onmouseout="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.05)';">
        <img alt="${p.pro_name}"
             src="${imageUrl}"
             class="lazyload lazy"
             style="width: 100px; height: auto; object-fit: contain; border-radius: 6px; flex-shrink: 0;">
        <div style="
            padding-left: 12px;
            flex: 1 1 auto;
            min-width: 0;
        ">
            <h4 style="
                margin: 0 0 6px 0;
                font-weight: 600;
                font-size: 16px;
                line-height: 1.2;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;
            ">${p.pro_name}</h4>
            <div style="
                color: #28a745;
                font-weight: 700;
                font-size: 15px;
            ">
                Giá: ${Number(p.pro_price).toLocaleString('vi-VN')} VNĐ
            </div>
        </div>
    </a>`;
            }

            // Xử lý gửi câu hỏi gửi ajax như bạn đã lập trình...
            chatForm.on('submit', function(e) {
                e.preventDefault();
                const question = chatInput.val().trim();
                if (!question) return;

                addMessage(question, true);
                chatInput.val('');

                $.ajax({
                    url: '{{ route("get.product-chat") }}', // đổi đúng route API
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data: {
                        question: question
                    },
                    success: function(res) {
                        if (res.chat_reply) {
                            addMessage(res.chat_reply, false);
                        } else {
                            addMessage('Xin lỗi, tôi không hiểu câu hỏi của bạn.', false);
                        }
                        if (res.suggested_products && res.suggested_products.length > 0) {
                            let productsHtml = '<div style="margin-top:10px;margin-bottom:10px;">';
                            res.suggested_products.forEach(p => {
                                productsHtml += renderProductCard(p);
                            });
                            productsHtml += '</div>';
                            addMessage(productsHtml, false);
                        }
                    },
                    error: function() {
                        addMessage('Có lỗi khi kết nối đến hệ thống. Vui lòng thử lại sau.', false);
                    }
                });
            });
        });
    </script>

    @yield('script')
</body>

</html>