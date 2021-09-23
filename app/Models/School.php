<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class School extends Model
    {
        use HasFactory;

        protected $table = 'schools';

        protected $fillable = [
            'user_id',
            'name',
        ];

        public static function boot()
        {
            parent::boot();
            static::creating(function ($school) {
                $school->user_id = auth()->id();
            });
        }
    }
