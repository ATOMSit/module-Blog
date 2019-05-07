<?php

namespace Modules\Blog\Database\Seeders\TenantDatabaseSeeder;

use App\Advice;
use App\Plugin;
use Illuminate\Database\Seeder;

class PluginsBlogTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plugins = array(
            array("name" => "blog", "price" => 00.00),
        );
        foreach ($plugins as $plugin) {
            $db = Plugin::query()
                ->where("name", $plugin["name"])
                ->first();
            if ($db === null) {
                $db = new Plugin([
                    "name" => $plugin['name'],
                    "price" => $plugin['price']
                ]);
                $db->save();
            } elseif ($db !== null) {
                $db->update([
                    "name" => $plugin['name'],
                    "price" => $plugin['price']
                ]);
                $db->save();
            }
        }
    }
}