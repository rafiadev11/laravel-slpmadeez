<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;

    class Disorder extends Model
    {
        use HasFactory, SoftDeletes;

        protected $table = 'disorders';

        protected $fillable = [
            'user_id',
            'name',
        ];

        public static function boot()
        {
            parent::boot();
            static::creating(function ($disorder) {
                $disorder->user_id = auth()->id();
            });
        }

        public function user()
        {
            return $this->belongsTo(User::class, 'user_id');
        }
    }
