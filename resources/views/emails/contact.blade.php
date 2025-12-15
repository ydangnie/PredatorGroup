<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Yêu Cầu Liên Hệ Mới</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
        <h2 style="color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 10px;">YÊU CẦU HỖ TRỢ MỚI</h2>
        <p>Xin chào Ban Quản Trị,</p>
        <p>Bạn nhận được một yêu cầu liên hệ mới từ khách hàng qua trang Liên Hệ.</p>

        <div style="margin-top: 20px; padding: 15px; background-color: #f9f9f9; border: 1px solid #eee; border-radius: 5px;">
            <h3 style="color: #555; margin-top: 0;">Chi tiết liên hệ:</h3>
            <p><strong>Họ & tên:</strong> {{ $data['name'] }}</p>
            <p><strong>Email:</strong> <a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a></p>
            <p><strong>Số điện thoại:</strong> {{ $data['phone'] }}</p>
        </div>
        
        <div style="margin-top: 20px;">
            <p style="font-weight: bold; color: #555;">Nội dung tin nhắn:</p>
            <p style="white-space: pre-wrap; background-color: #fff; padding: 15px; border-left: 4px solid #007bff; margin: 10px 0; border-radius: 3px;">{{ $data['message_content'] }}</p>
        </div>

        <p style="margin-top: 30px; font-size: 0.9em; color: #777;">Trân trọng,<br>Hệ thống PredatorWatch Auto-Mailer</p>
    </div>
</body>
</html>