<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::where('email', config('app.admin_email'))->first();
        if (!$user) {
            echo "User not created. Run php artisan setup:user\n";

            return;
        }

        $profileFile = __DIR__."/ProfileSeeder.php";
        if (file_exists($profileFile)) {
            echo "Creating profile...\n";
            $this->call(ProfileSeeder::class);
            echo "Done!\n\n";
        } else {
            echo "No profile seeder\n\n";
        }

        $medicinesFile = __DIR__."/MedicineSeeder.php";
        if (file_exists($medicinesFile)) {
            echo "Creating medicines...\n";
            $this->call(MedicineSeeder::class);
            echo "Done!\n\n";
        } else {
            echo "No medicines seeder\n\n";
        }

        $scheduleFile = __DIR__."/ScheduleSeeder.php";
        if (file_exists($scheduleFile)) {
            echo "Creating Schedule...\n";
            $this->call(ScheduleSeeder::class);
            echo "Done!\n\n";
        } else {
            echo "No schedule seeder\n\n";
        }
    }
}
