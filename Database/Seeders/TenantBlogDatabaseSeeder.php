<?php

namespace Modules\Blog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Blog\Database\Seeders\TenantDatabaseSeeder\AdvicesBlogTableSeeder;
use Modules\Blog\Database\Seeders\TenantDatabaseSeeder\PermissionsBlogTableSeeder;
use Modules\Blog\Database\Seeders\TenantDatabaseSeeder\PluginsBlogTableSeeder;

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

        $this->call(PluginsBlogTableSeeder::class);

        $this->call(AdvicesBlogTableSeeder::class);
    }
}
