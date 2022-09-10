<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Faker;


class AppFixtures extends Fixture
{
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 3; $i++) {
            $category = new Category();

            $category->setName(ucfirst($faker->word));
            $category->setSlug($this->slugify($category->getName()));

            //random emoji api
            $response = $this->client->request(
                'GET',
                'https://ranmoji.herokuapp.com/emojis/api/v.1.0/?count=1'
            );
            $content = $response->toArray();
            $category->setIcon($content['emoji']);

            $category->setDescription($faker->sentence(20));

            $manager->persist($category);
        }

        $manager->flush();

        for ($i = 0; $i < 10; $i++) {
            $article = new Article();

            $article->setTitle(ucfirst($faker->sentence(10, false)));
            $article->setSlug($this->slugify($article->getTitle()));
            $article->setContent($faker->paragraph(50));
            $article->setCreatedAt($faker->dateTimeBetween('-6 months'));
            $article->setUpdatedAt($faker->dateTimeBetween('-6 months'));

            $categories = $manager->getRepository(Category::class)->findAll();
            $randNumb = rand(1, 3);

            for ($j = 0; $j < $randNumb; $j++) {
                $article->addCategory($categories[rand(0, count($categories) - 1)]);
            }

            $manager->persist($article);
        }

        $manager->flush();
    }

    private static function slugify($text, string $divider = '-')
    {
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, $divider);
        $text = preg_replace('~-+~', $divider, $text);
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}
