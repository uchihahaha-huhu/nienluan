<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressBook extends Model
{
    use HasFactory;

    // Tên bảng (nếu cần thiết, mặc định Laravel sẽ lấy dạng số nhiều của tên model)
    protected $table = 'address_book';

    /**
     * Các trường có thể gán hàng loạt
     */
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'address',
        'is_default',
    ];

    /**
     * Các thuộc tính nên tự động chuyển đổi kiểu dữ liệu
     */
    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Quan hệ: 1 AddressBook thuộc về 1 User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
