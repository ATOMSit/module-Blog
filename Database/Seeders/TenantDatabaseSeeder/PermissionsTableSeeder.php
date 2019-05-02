<?php

use Illuminate\Database\Seeder;
use App\Permission;
use App\Role;

class PermissionsTableSeeder extends Seeder
{
    /**
     * @var \Spatie\Permission\Contracts\Role
     */
    protected $atomsit;

    /**
     * @var \Spatie\Permission\Contracts\Role
     */
    protected $proprietaire;

    /**
     * @var \Spatie\Permission\Contracts\Role
     */
    protected $administrateur;

    /**
     * PermissionsTableSeeder constructor.
     */
    public function __construct()
    {
        $this->atomsit = Role::findOrCreate('ATOMSit');

        $this->proprietaire = Role::findOrCreate('Proprietaire');

        $this->administrateur = Role::findOrCreate('Administrateur');
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'blog_post_show',
            'blog_post_create',
            'blog_post_update',
            'blog_post_delete',
            'blog_post_restore',
            'blog_post_forceDelete'
        ];
        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
            $this->atomsit->givePermissionTo($permission);
            $this->proprietaire->givePermissionTo($permission);
            $this->administrateur->givePermissionTo($permission);
        }

    }
}