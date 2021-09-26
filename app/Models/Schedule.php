<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\SoftDeletes;

    class Schedule extends Model
    {
        use HasFactory, SoftDeletes;

        protected $table = 'schedules';

        protected $fillable = [
            'goal_id',
            'day',
            'start_time',
            'end_time',
        ];

        public function goal(): BelongsTo
        {
            return $this->belongsTo(Goal::class, 'goal_id');
        }
    }
