<?php

namespace App\Models;

use App\Enums\Gender;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'bmi',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'bmi' => 'float',
            'gender' => Gender::class,
        ];
    }

    protected static function booted(): void
    {
        static::saving(static function (Profile $profile) {
            $profile->bmi = $profile->calculateBMI();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function age(): Attribute
    {
        return Attribute::make(
            get: static fn(Carbon $value) => $value->age,
        );
    }

    public function getHeightAttribute(float $inches): string
    {
        $feet = floor($inches / 12);
        $remainingInches = $inches % 12;

        return "$feet' $remainingInches\"";
    }

    public function setHeightAttribute(string $height): void
    {
        // Regular expression for "5' 11"" format or "5 feet 10 inches" format
        $pattern = '/(\d+)\s*(?:feet|\'|ft)?\s*(\d+)\s*(?:inches?|")?/';

        // Try to match the pattern
        if (!preg_match($pattern, $height, $matches)) {
            // Return null if the input string doesn't match the expected format
            $this->attributes['height'] = null;

            return;
        }

        $feet = (int) $matches[1];   // First matched group is feet
        $inches = (int) $matches[2]; // Second matched group is inches

        $this->attributes['height'] = ($feet * 12) + $inches;
    }

    protected function weight(): Attribute
    {
        return Attribute::make(
            get: static fn (int $weight) => "$weight Lbs",
            set: static function (string $weight) {

                if (!preg_match('/\d+/', $weight, $match)) {
                    return null;
                }

                return (int) floor($match[0]);
            }
        );
    }

    public function calculateBMI(): ?float
    {
        $height = $this->attributes['height'];
        $weight = $this->attributes['weight'];

        if ($weight === null || $weight <= 0) {
            return null;
        }

        if ($height === null || $height <= 0) {
            return null;
        }

        // Convert weight from pounds to kilograms
        $weightInKg = $weight * 0.453592;

        // Convert height from inches to meters
        $heightInM = $height * 0.0254;

        // Calculate BMI (float)
        $bmi = $weightInKg / ($heightInM * $heightInM);

        // Return the BMI rounded to two decimal places
        return round($bmi, 2);
    }

    public function toArray(): array
    {
        $info = parent::toArray();
        $info['age'] = $this->dob->age;

        return $info;
    }
}
