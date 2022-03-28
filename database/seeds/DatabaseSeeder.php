<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $faker = Faker\Factory::create();

        $limit = 3;

        for ($i = 0; $i < $limit; $i++) {
            DB::table('orders')->insert([
                'transaction_id' => '00' . $i++ . '-TEST',
                'amount' => $faker->randomDigit,
                'payment_status' => 0
            ]);
        }
    }
}
