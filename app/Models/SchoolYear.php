<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\SoftDeletes;

    class SchoolYear extends Model
    {
        use HasFactory, SoftDeletes;

        protected $table = 'school_years';

        protected $fillable = [
            'school_id',
            'start',
            'end',
        ];

        public function goals(): HasMany
        {
            return $this->hasMany(Goal::class, 'school_year_id');
        }

        public function school(): BelongsTo
        {
            return $this->belongsTo(School::class, 'school_id');
        }
    }
