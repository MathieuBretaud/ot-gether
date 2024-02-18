<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'is_IRL',
        'participant_min',
        'participant_max',
        'start_date',
        'end_date',
        'event_category_id',
        'event_tag_id',
        'creator_id',
        'slug',
        'picture',
        'title',
        'description',
        'address',
        'city',
        'region',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(EventCategory::class, 'event_category_id');
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(EventTag::class, 'event_tag_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_user');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
