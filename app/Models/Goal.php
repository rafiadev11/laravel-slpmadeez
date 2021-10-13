<?php

    namespace App\Models;

    use App\Scopes\ActiveStudentScope;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\SoftDeletes;

    class Goal extends Model
    {
        use HasFactory, SoftDeletes;

        public static function boot()
        {
            parent::boot();
            static::addGlobalScope(new ActiveStudentScope());
        }

        protected $table = 'goals';

        protected $fillable = [
            'school_year_id',
            'student_id',
            'disorder_id',
            'annual_minutes',
            'active',
        ];

        public function objectives(): HasMany
        {
            return $this->hasMany(Objective::class, 'goal_id');
        }

        public function schedule(): HasMany
        {
            return $this->hasMany(Schedule::class, 'goal_id');
        }

        public function student(): BelongsTo
        {
            return $this->belongsTo(Student::class, 'student_id');
        }

        public function schoolYear(): BelongsTo
        {
            return $this->belongsTo(SchoolYear::class, 'school_year_id');
        }

        public function disorder(): BelongsTo
        {
            return $this->belongsTo(Disorder::class, 'disorder_id');
        }
    }
