<?php

namespace App\Models;

use App\Scopes\IsNotDeletedScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class Team extends Model
{
    use HasFactory;
    // sử dụng traint để fake data
    // Đặt tên bảng nếu không muốn theo quy tắc mặc định
    protected $table = 'm_teams';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;
    public const CREATED_AT = 'ins_datetime';
    public const UPDATED_AT = 'upd_datetime';
    protected $fillable = ['name', 'del_flag'];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    // chuyển đổi dữ liệu thành kiểu Carbon -> cần phải format lại hoặc sử dụng accesstor
    //    protected $casts = [
    //        'ins_datetime' => 'datetime:d-m-Y H:i:s', // Tùy chỉnh định dạng nhưng hiện tại chưa thấy gì? chỗ này hơn cấn cấn
    //        'upd_datetime' => 'datetime:d-m-Y H:i:s',
    //    ];

    // sử dụng global scope -> tự động áp dụng cho tất cả truy vấn
    protected static function booted(): void
    {
        static::addGlobalScope(new IsNotDeletedScope());
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(static function ($model) {
            $model->ins_id = auth()->check() ? auth()->user()->id : 1;
            $model->del_flag = IS_NOT_DELETED;
        });

        static::updating(static function ($model) {
            $model->upd_id = auth()->check() ? auth()->user()->id : 1;
        });
    }

    // sử dụng local scope -> chỉ dùng khi cần gọi
    public function scopeGetAllNoDeleted($query)
    {
        return $query->where('del_flag', 0);
    }

    public function delete()
    {
        $this->del_flag = IS_DELETED;
        Employee::where('team_id', $this->id)->update(['del_flag' => IS_DELETED]);
        return $this->save();
    }

    protected function insDatetime(): Attribute
    {
        return Attribute::make(
            get: static fn($value) => $value ? Carbon::parse($value)->format('d-m-Y') : null
        );
    }

    protected function updDatetime(): Attribute
    {
        return Attribute::make(
            get: static fn($value) => $value ? Carbon::parse($value)->format('d-m-Y') : null
        );
    }

    protected function updId(): Attribute
    {
        return Attribute::make(
            get: static fn($value) => $value !== null ? (int) $value : 'Null'
        );
    }
}
