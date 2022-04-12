<?php

namespace Database\Seeders;

use App\Models\Ability;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Defaults\Operation;
use App\Models\Defaults\Category;
use App\Models\Defaults\Template;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $abilities = collect([
            Ability::create(['name' => 'manage-users'])->id,
            Ability::create(['name' => 'manage-categories'])->id,
            Ability::create(['name' => 'manage-templates'])->id,
        ]);

        User::create([
            'name' =>'admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'is_primary' => true,
            'remember_token' => Str::random(10),
        ])->abilities()->attach($abilities);

        $income = Operation::create([
            'name' => 'income'
        ]);
        $expense = Operation::create([
            'name' => 'expense'
        ]);

        Category::create([
            'name' => 'Various incomes',
            'is_primary' => true,
            'operation_id' => $income->id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        Category::create([
            'name' => 'Various expenses',
            'is_primary' => true,
            'operation_id' => $expense->id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $users = User::factory()->count(10)->create();
        $users->each(function ($user) use ($abilities){
            $user->abilities()->attach($abilities->random(rand(1, $abilities->count())));
        });

        $operation_ids = Operation::all()->pluck('id');
        Category::factory()->count(10)->create([
            'operation_id' => function () use ($operation_ids) {
                return $operation_ids->random();
            }
        ]);

        $category_ids = Category::all()->pluck('id');
        Template::factory()->count(50)->create([
            'category_id' => function () use ($category_ids) {
                return $category_ids->random();
            },
        ]);
    }
}
