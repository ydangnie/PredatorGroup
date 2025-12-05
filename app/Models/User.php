<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'avatar',
    'so_dien_thoai',
    'dia_chi',
    'gioi_tinh', // Mới thêm
    'ngay_sinh', // Mới thêm
    'google_id'
];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google_id'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',

        ];
    }
    public function laAdmin()
    {
        return $this->role === 'admin';
    }
    // Thêm vào trong class User
    // Thêm vào trong class User
public function addresses() {
    return $this->hasMany(UserAddress::class);
}

public function orders() {
    return $this->hasMany(Order::class); // Giả sử bạn đã có Model Order
}

    
}
