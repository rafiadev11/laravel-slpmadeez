<?php

    namespace App\Models;

    use App\Scopes\AuthenticatedUserScope;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\SoftDeletes;

    class School extends Model
    {
        use HasFactory, SoftDeletes;

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
            static::addGlobalScope(new AuthenticatedUserScope());
        }

        public function user(): BelongsTo
        {
            return $this->belongsTo(User::class, 'user_id');
        }

        public function schoolYears(): HasMany
        {
            return $this->hasMany(SchoolYear::class, 'school_id');
        }
    }
