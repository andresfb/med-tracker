<?php

namespace App\Models;

use App\Enums\Frequency;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Schedule extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'time_of_day' => 'string',
            'start_date'  => 'date',
            'end_date'    => 'date',
            'frequency'   => Frequency::class,
        ];
    }

    protected function timeOfDay(): Attribute
    {
        return Attribute::make(
            get: static fn(string $value) => Carbon::today()->setTimeFromTimeString($value),
            set: static fn(string $value) => Carbon::today()->setTimeFromTimeString($value)->format('H:i'),
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }
}
