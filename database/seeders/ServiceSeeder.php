<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [

            [
                'name' => 'Photography',
                'slug' => 'photography',
                'description' => 'Official event photography coverage.',
                'category' => 'Media Coverage',
                'icon' => 'bi-camera',
                'color' => 'primary',
                'sort_order' => 1,
                'requires_schedule' => true,
                'requires_event_details' => true,
            ],

            [
                'name' => 'Videography',
                'slug' => 'videography',
                'description' => 'Official event video coverage.',
                'category' => 'Media Coverage',
                'icon' => 'bi-camera-video',
                'color' => 'danger',
                'sort_order' => 2,
                'requires_schedule' => true,
                'requires_event_details' => true,
            ],

            [
                'name' => 'Press Release',
                'slug' => 'press-release',
                'description' => 'Preparation of press releases.',
                'category' => 'Media',
                'icon' => 'bi-newspaper',
                'color' => 'info',
                'sort_order' => 3,
                'requires_schedule' => true,
                'requires_event_details' => true,
            ],

            [
                'name' => 'Social Media Posting',
                'slug' => 'social-media',
                'description' => 'Official social media publication.',
                'category' => 'Digital Media',
                'icon' => 'bi-facebook',
                'color' => 'primary',
                'sort_order' => 4,
                'requires_schedule' => true,
                'requires_event_details' => true,
            ],

            [
                'name' => 'Livestream',
                'slug' => 'livestream',
                'description' => 'Live streaming of events.',
                'category' => 'Media Coverage',
                'icon' => 'bi-broadcast',
                'color' => 'danger',
                'sort_order' => 5,
                'requires_schedule' => true,
                'requires_event_details' => true,
            ],

            [
                'name' => 'Tarpaulin Layout',
                'slug' => 'tarpaulin',
                'description' => 'Design of tarpaulins.',
                'category' => 'Graphic Design',
                'icon' => 'bi-image',
                'color' => 'warning',
                'sort_order' => 6,
                'requires_attachments' => true,
            ],

            [
                'name' => 'Certificate Layout',
                'slug' => 'certificate',
                'description' => 'Certificate design.',
                'category' => 'Graphic Design',
                'icon' => 'bi-award',
                'color' => 'success',
                'sort_order' => 7,
                'requires_attachments' => true,
            ],

            [
                'name' => 'Invitation Layout',
                'slug' => 'invitation',
                'description' => 'Invitation design.',
                'category' => 'Graphic Design',
                'icon' => 'bi-envelope-paper',
                'color' => 'secondary',
                'sort_order' => 8,
                'requires_attachments' => true,
            ],

        ];

        foreach ($services as $service) {

            Service::create(array_merge([

                'requires_request' => true,
                'requires_organization' => true,
                'requires_contact' => true,
                'requires_schedule' => false,
                'requires_attachments' => false,
                'requires_event_details' => false,
                'requires_approval' => false,
                'is_active' => true,

            ], $service));

        }
    }
}