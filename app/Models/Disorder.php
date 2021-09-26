<?php

    namespace App\Models;

    use App\Scopes\AuthenticatedUserScope;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
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
            static::addGlobalScope(new AuthenticatedUserScope());
        }

        public function goals(): HasMany
        {
            return $this->hasMany(Goal::class, 'disorder_id');
        }

        public function user(): BelongsTo
        {
            return $this->belongsTo(User::class, 'user_id');
        }
    }
