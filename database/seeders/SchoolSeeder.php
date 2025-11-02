<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 5 schools with realistic data
        $schools = \App\Models\School::factory(5)->create();

        // Create default configurations for each school
        foreach ($schools as $school) {
            \App\Models\SchoolConfiguration::createDefaultsForSchool($school->id);
        }

        // Create some additional custom configurations with unique keys
        $faker = \Faker\Factory::create();
        foreach ($schools as $school) {
            $customConfigs = [
                'custom_field_1' => ['value' => $faker->word, 'type' => 'string', 'is_public' => false],
                'custom_field_2' => ['value' => $faker->numberBetween(1, 100), 'type' => 'integer', 'is_public' => true],
                'custom_field_3' => ['value' => $faker->boolean, 'type' => 'boolean', 'is_public' => false],
            ];

            foreach ($customConfigs as $key => $config) {
                \App\Models\SchoolConfiguration::setForSchool(
                    $school->id,
                    $key,
                    $config['value'],
                    $config['type'],
                    'Custom configuration field',
                    $config['is_public']
                );
            }
        }

        $this->command->info('Created ' . $schools->count() . ' schools with configurations.');
    }
}
