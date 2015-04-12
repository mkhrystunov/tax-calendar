<?php

use Silex\Provider\TranslationServiceProvider;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;
$app->error(function (\Exception $e, $code) {
    return new Response($e->getMessage());
});
$app->register(new TranslationServiceProvider(), [
    'locale_fallbacks' => ['en'],
]);
$app->register(new Silex\Provider\DoctrineServiceProvider(), [
    'db.options' => [
        'driver' => 'pdo_pgsql',
        'host' => 'localhost',
        'port' => 5432,
        'user' => 'postgres',
        'password' => '123456',
    ],
]);
$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/views',
]);

$app['translator'] = $app->share($app->extend('translator', function (Translator $translator, $app) {
    $translator->addLoader('yaml', new YamlFileLoader());
    $translator->addResource('yaml', __DIR__ . '/locales/en.yml', 'en');
    return $translator;
}));

$app->get('/', function () use ($app) {
    return $app['twig']->render('main.html.twig', [
        'groups' => [
            [
                'id' => 1,
                'name' => 'first',
            ],
        ]
    ]);
});
$app->get('/{_locale}/{message}/{name}', function ($message, $name) use ($app) {
    $id = 1;
    $sql = "SELECT * FROM posts WHERE id = :id";
    $post = $app['db']->fetchAssoc($sql, [':id' => (int) $id]);

//    print_r(getdate((new DateTime())->getTimestamp()));
    echo '<br>';

    return $app['twig']->render('main.html.twig', [
        'name' => $name,
        'post' => $post,
    ]);
})->value('_locale', 'en');

$app->run();
