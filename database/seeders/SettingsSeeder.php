<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Seed default project settings.
     */
    public function run(): void
    {
        foreach ($this->settings() as $setting) {
            Settings::updateOrCreate(
                ['name' => $setting['name']],
                [
                    'value' => $setting['value'],
                    'description' => $setting['description'],
                ],
            );
        }
    }

    /**
     * Return default settings used by the application.
     *
     * @return array<int, array{name: string, value: string, description: string}>
     */
    private function settings(): array
    {
        return [
            [
                'name' => 'COLUMNS_NUMBER',
                'value' => '3',
                'description' => 'Number of catalog columns on the frontend',
            ],
            [
                'name' => 'ADD_LINKS_WITHOUT_CHECK',
                'value' => '0',
                'description' => 'Publish submitted links without moderation',
            ],
            [
                'name' => 'EMAIL_ADMIN',
                'value' => 'admin@example.com',
                'description' => 'Administrator notification email',
            ],
        ];
    }
}
