<?php

namespace App\Models;

use App\Scopes\IsNotDeletedScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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

    // Quan hệ với bảng employees (mối quan hệ 1 - nhiều)
    public function employees()
    {
        return $this->hasMany(Employee::class, 'team_id', 'id');
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
        // dùng hàm này để gán giá trị thời gian tốt hơn là gán trực tiếp trên thuộc tính không ?
        static::creating(static function ($model) {

            $model->ins_id = auth()->user()->id ?? 1;
            $model->del_flag = IS_NOT_DELETED;
        });

        static::updating(static function ($model) {
            $model->upd_id = auth()->user()->id ?? 1;
        });
    }

    // sử dụng local scope -> chỉ dùng khi cần gọi
    public function scopeGetAllNoDeleted($query)
    {
        return $query->where('del_flag', 0);
    }

    // các hàm khác
    public function delete()
    {
        $this->del_flag = IS_DELETED;
        // xóa mềm cả các đối tượng khóa ngoại của team -> employees
        $employees = Employee::where('team_id', $this->id)->get();
        foreach ($employees as $employee) {
            //    $employee->del_flag = IS_DELETED;
            $employee->delete();
        }
        return $this->save();
    }

    // Recover deleted record
    public function restore(): bool
    {
        $this->del_flag = IS_NOT_DELETED;
        return $this->save();
    }

    // Check is deleted
    public function trashed()
    {
        return $this->del_flag === IS_DELETED;
    }

    public static function getFieldById($id, $field)
    {
        return self::where('id', $id)->value($field);
    }

    // Accessor and Mutator
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


