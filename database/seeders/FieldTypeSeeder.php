<?php

namespace Database\Seeders;

use App\Models\FieldType;
use Illuminate\Database\Seeder;

class FieldTypeSeeder extends Seeder
{
    public function run(): void
    {
        $fieldTypes = [

            [
                'name' => 'Text',
                'icon' => 'bi-input-cursor-text',
                'description' => 'Single line text'
            ],

            [
                'name' => 'Textarea',
                'icon' => 'bi-textarea-t',
                'description' => 'Multiple line text'
            ],

            [
                'name' => 'Number',
                'icon' => 'bi-123',
                'description' => 'Numeric value'
            ],

            [
                'name' => 'Date',
                'icon' => 'bi-calendar-event',
                'description' => 'Date picker'
            ],

            [
                'name' => 'Time',
                'icon' => 'bi-clock',
                'description' => 'Time picker'
            ],

            [
                'name' => 'Select',
                'icon' => 'bi-menu-button',
                'description' => 'Dropdown list'
            ],

            [
                'name' => 'Checkbox',
                'icon' => 'bi-check-square',
                'description' => 'Yes / No'
            ],

            [
                'name' => 'File',
                'icon' => 'bi-paperclip',
                'description' => 'File Upload'
            ],

        ];

        foreach ($fieldTypes as $fieldType) {

            FieldType::firstOrCreate(
                ['name' => $fieldType['name']],
                $fieldType
            );

        }
    }
}