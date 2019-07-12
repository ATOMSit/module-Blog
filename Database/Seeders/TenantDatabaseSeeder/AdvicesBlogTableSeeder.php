<?php

namespace Modules\Blog\Database\Seeders\TenantDatabaseSeeder;

use App\Advice;
use App\Plugin;
use Illuminate\Database\Seeder;

class AdvicesBlogTableSeeder extends Seeder
{
    /**
     * @var \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    protected $plugin;

    /**
     * AdvicesBlogTableSeeder constructor.
     */
    public function __construct()
    {
        $this->plugin = Plugin::query()
            ->where('name', 'blog')
            ->first();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $advices = array(
            array("name" => "blog_1", "body" => "Une section blog permet d'améliorer la visibilité de votre site sur internet via la publication régulière d'articles sur la thématique de votre entreprise. Vous ajouterez ainsi du contenu qualitatif qui vous permettra de recevoir des liens d'autres sites relayant vos billets."),
            array("name" => "blog_2", "body" => "Une section blog permet d'humaniser votre site internet, donner a votre entreprise, a travers vos billets un capital de sympathie, informer vos visiteurs sur vos innovations technologiques, événements auxquelles vous participé ou encore l'aspect social de votre entreprise."),
            array("name" => "blog_3", "body" => "Une section blog est un bon moyen de diffuser des informations non commerciales concernant vos produits (retours d'expériences, trucs et astuces ou même présentation en détail de votre produit) qui n'auraient pas d'espace dans vos outils de communication traditionnels."),
            array("name" => "blog_4", "body" => "Une section blog permet d'établir un dialogue privilégié avec vos clients et ainsi d'échanger directement avec eux concernant vos produits, votre entreprise, leurs problèmes, leurs avis ou même leurs suggestions."),
            array("name" => "blog_5", "body" => "Une section blog est un très bon générateur de prospects, en effet elle permet d'attirer des visiteurs sur une thématique donnée, ou qui reconnaitront votre expertise via des tutoraux, vos témoignages clients.")
        );
        $option = Plugin::query()
            ->where('name', 'Blog')
            ->first();
        foreach ($advices as $advice) {
            $db = Advice::query()
                ->where("name", $advice)
                ->first();
            if ($db === null) {
                $db = new Advice([
                    "name" => $advice['name'],
                    "body" => $advice['body']
                ]);
                $option->advices()->save($db);
            } elseif ($db !== null) {
                $db->update([
                    "name" => $advice['name'],
                    "body" => $advice['body']
                ]);
                $db->save();
            }
        }
    }
}