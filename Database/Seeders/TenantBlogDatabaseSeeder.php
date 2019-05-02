<?php

namespace Modules\Blog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Blog\Database\Seeders\TenantDatabaseSeeder\PermissionsBlogTableSeeder;

class TenantBlogDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionsBlogTableSeeder::class);
    }
}
