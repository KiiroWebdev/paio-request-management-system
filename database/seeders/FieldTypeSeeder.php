<?php

namespace Database\Seeders;

use App\Models\FieldType;
use Illuminate\Database\Seeder;

class FieldTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [

            [
                'name' => 'Text',
                'key' => 'text',
                'icon' => 'bi-input-cursor-text',
                'description' => 'Single line text input',
            ],

            [
                'name' => 'Textarea',
                'key' => 'textarea',
                'icon' => 'bi-textarea-t',
                'description' => 'Multiple line text input',
            ],

            [
                'name' => 'Number',
                'key' => 'number',
                'icon' => 'bi-123',
                'description' => 'Numeric input',
            ],

            [
                'name' => 'Date',
                'key' => 'date',
                'icon' => 'bi-calendar-event',
                'description' => 'Date picker',
            ],

            [
                'name' => 'Time',
                'key' => 'time',
                'icon' => 'bi-clock',
                'description' => 'Time picker',
            ],

            [
                'name' => 'Select',
                'key' => 'select',
                'icon' => 'bi-menu-button-wide',
                'description' => 'Dropdown selection',
            ],

            [
                'name' => 'Checkbox',
                'key' => 'checkbox',
                'icon' => 'bi-check2-square',
                'description' => 'Multiple choice checkboxes',
            ],

            [
                'name' => 'File',
                'key' => 'file',
                'icon' => 'bi-paperclip',
                'description' => 'File upload',
            ],

        ];

        foreach ($types as $type) {

            FieldType::updateOrCreate(

                ['key' => $type['key']],

                $type

            );

        }
    }
}