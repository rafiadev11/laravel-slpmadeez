<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\SoftDeletes;

    class Student extends Model
    {
        use HasFactory, SoftDeletes;

        protected $table = 'students';

        protected $fillable = [
            'first_name',
            'last_name',
            'grade',
        ];

        public function goals(): HasMany
        {
            return $this->hasMany(Goal::class, 'student_id');
        }
    }
