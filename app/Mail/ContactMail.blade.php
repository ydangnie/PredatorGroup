<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Khởi tạo đối tượng tin nhắn mới.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Lấy envelope (tiêu đề) của tin nhắn.
     */
    public function envelope(): Envelope
    {
        // Tiêu đề email mà người nhận (admin) sẽ thấy
        return new Envelope(
            subject: 'Yêu Cầu Liên Hệ Mới từ PredatorWatch',
        );
    }

    /**
     * Lấy nội dung tin nhắn (sử dụng template Blade).
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact', // Template Blade sẽ được tạo ở Bước 5
        );
    }
}