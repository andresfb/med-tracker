<?php

namespace App\Models;

use App\Enums\Gender;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'bmi',
        'height_inches',
        'weight_inches',
    ];

    protected function casts(): array
    {
        return [
            'age' => 'integer',
            'bmi' => 'float',
            'height_inches' => 'integer',
            'weight_pounds' => 'integer',
            'gender' => Gender::class,
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Profile $profile) {
            $profile->bmi = $this->calculateBMI($profile->weight_pounds, $profile->height_inches);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function height(): Attribute
    {
        return Attribute::make(
            get: static function (float $inches) {
                $feet = floor($inches / 12);
                $remainingInches = $inches % 12;

                return "$feet' $remainingInches\"";
            },

            set: static function (string $height) {
                // Regular expression for "5' 11"" format or "5 feet 10 inches" format
                $pattern = '/(\d+)\s*(?:feet|\'|ft)?\s*(\d+)\s*(?:inches?|")?/';

                // Try to match the pattern
                if (!preg_match($pattern, $height, $matches)) {
                    // Return null if the input string doesn't match the expected format
                    return null;
                }

                $feet = (int) $matches[1];   // First matched group is feet
                $inches = (int) $matches[2]; // Second matched group is inches

                return ($feet * 12) + $inches;
            }
        );
    }

    protected function weight(): Attribute
    {
        return Attribute::make(
            get: static fn (int $weight) => $weight,
            set: static fn (int $weight) => $weight,
        );
    }

    private function calculateBMI($weight, $height): float
    {
        // Convert weight from pounds to kilograms
        $weightInKg = $weight * 0.453592;

        // Convert height from inches to meters
        $heightInM = $height * 0.0254;

        // Calculate BMI (float)
        $bmi = $weightInKg / ($heightInM * $heightInM);

        // Return the BMI rounded to two decimal places
        return round($bmi, 2);
    }
}
