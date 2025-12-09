<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chào mừng đến với Predator Group</title>
    <style>
        /* CSS Reset cho Email */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        table { border-collapse: collapse !important; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; font-family: 'Arial', sans-serif; background-color: #121212; color: #E0E0E0; }
        
        /* Font Imports (Lưu ý: Một số mail client cũ sẽ không nhận font ngoài, nên cần font dự phòng) */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Playfair+Display:wght@700&display=swap');
    </style>
</head>
<body style="background-color: #121212; margin: 0; padding: 0;">

    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #121212;">
        <tr>
            <td align="center" style="padding: 40px 10px;">
                
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="max-width: 600px; background-color: #1E1E1E; border: 1px solid #333333; border-radius: 8px; overflow: hidden;">
                    
                    <tr>
                        <td align="center" style="padding: 30px 0; background-color: #1E1E1E; border-bottom: 1px solid #333333;">
                            <a href="{{ route('home') }}" target="_blank" style="text-decoration: none;">
                                <h1 style="margin: 0; font-family: 'Playfair Display', serif; color: #FFFFFF; font-size: 28px; letter-spacing: 2px; text-transform: uppercase;">
                                    PREDATOR<span style="color: #D4AF37;">GROUP</span>
                                </h1>
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="background-color: #161616;">
                            </td>
                    </tr>

                    <tr>
                        <td align="left" style="padding: 40px 30px;">
                            <h2 style="margin: 0 0 20px 0; font-family: 'Playfair Display', serif; font-size: 24px; color: #D4AF37; text-align: center;">
                                Xin chào, {{ $user->name }}!
                            </h2>
                            
                            <p style="margin: 0 0 20px 0; font-family: 'Inter', sans-serif; font-size: 15px; line-height: 1.6; color: #CCCCCC; text-align: center;">
                                Cảm ơn bạn đã tin tưởng và trở thành một phần của <strong>Predator Group</strong>. Tài khoản của bạn đã được khởi tạo thành công.
                            </p>
                            
                            <p style="margin: 0 0 30px 0; font-family: 'Inter', sans-serif; font-size: 15px; line-height: 1.6; color: #CCCCCC; text-align: center;">
                                Tại đây, chúng tôi mang đến những bộ sưu tập thời trang đẳng cấp và những mẫu đồng hồ sang trọng nhất. Hãy bắt đầu khám phá phong cách của riêng bạn ngay hôm nay.
                            </p>

                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('home') }}" style="display: inline-block; padding: 14px 30px; background-color: #D4AF37; color: #000000; font-family: 'Inter', sans-serif; font-size: 14px; font-weight: bold; text-decoration: none; text-transform: uppercase; letter-spacing: 1px; border-radius: 4px;">
                                            Khám Phá Ngay
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <div style="margin: 40px 0 20px 0; border-top: 1px solid #333333;"></div>

                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="font-family: 'Inter', sans-serif; font-size: 14px; color: #888888; text-align: center;">
                                        Email đăng ký: <span style="color: #E0E0E0;">{{ $user->email }}</span><br>
                                        Ngày tham gia: <span style="color: #E0E0E0;">{{ date('d/m/Y') }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 30px; background-color: #161616; border-top: 1px solid #333333;">
                            <p style="margin: 0 0 10px 0; font-family: 'Inter', sans-serif; font-size: 14px; color: #FFFFFF; font-weight: bold;">PREDATOR GROUP</p>
                            <p style="margin: 0 0 10px 0; font-family: 'Inter', sans-serif; font-size: 12px; color: #666666;">
                                Địa chỉ: 123 Đường ABC, Quận XYZ, TP.HCM<br>
                                Hotline: 0123 456 789
                            </p>
                            
                            <table border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0 10px;">
                                        <a href="#" style="color: #888888; text-decoration: none; font-size: 12px; font-family: 'Inter', sans-serif;">Facebook</a>
                                    </td>
                                    <td style="padding: 0 10px; border-left: 1px solid #444;">
                                        <a href="#" style="color: #888888; text-decoration: none; font-size: 12px; font-family: 'Inter', sans-serif;">Instagram</a>
                                    </td>
                                    <td style="padding: 0 10px; border-left: 1px solid #444;">
                                        <a href="#" style="color: #888888; text-decoration: none; font-size: 12px; font-family: 'Inter', sans-serif;">Website</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
                <p style="margin-top: 20px; font-family: 'Inter', sans-serif; font-size: 12px; color: #555555;">
                    Nếu bạn không yêu cầu tạo tài khoản này, vui lòng bỏ qua email này.
                </p>

            </td>
        </tr>
    </table>

</body>
</html>