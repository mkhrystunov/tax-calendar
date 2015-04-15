<?php

use Model\Group;
use Model\GroupRange;
use Model\Report;
use Model\Tax;
use Repository\GroupRepository;
use Repository\ReportRepository;
use Repository\TaxRepository;
use Silex\Provider\TranslationServiceProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;
use Util\DateUtils;

$loader = require_once __DIR__ . '/../vendor/autoload.php';
$loader->add('Model', __DIR__ . '/../app/');
$loader->add('Repository', __DIR__ . '/../app/');
$loader->add('Util', __DIR__ . '/../app/');

$app = new Silex\Application();
$app['debug'] = true;
$app->error(function (\Exception $e, $code) {
    return new Response($e->getMessage());
});
$app->register(new TranslationServiceProvider(), [
    'locale_fallbacks' => ['ua'],
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
    $translator->addResource('yaml', __DIR__ . '/locales/ua.yml', 'ua');
    return $translator;
}));

$app['repository.tax'] = $app->share(function () use ($app) {
    return new TaxRepository($app['db']);
});
$app['repository.report'] = $app->share(function () use ($app) {
    return new ReportRepository($app['db']);
});
$app['repository.group'] = $app->share(function () use ($app) {
    return new GroupRepository($app['db']);
});


/** @var Tax[] $allTaxes */
$allTaxes = $app['repository.tax']->getAll();
/** @var Group[] $allGroups */
$allGroups = $app['repository.group']->getAll();
/** @var Report[] $allReports */
$allReports = $app['repository.report']->getAll();

$app->get('/', function (Request $request) use ($app, $allTaxes, $allGroups) {
    $selectedGroupId = $request->get('group', 1);

    return $app['twig']->render('main.html.twig', [
        'groups' => $allGroups,
        'selected_group' => $allGroups[$selectedGroupId],
        'taxes' => $allTaxes,
        'languages' => [
            'ua',
            'ru',
        ],
        'cur_year' => (new DateTime())->format('Y'),
    ]);
});

$app->post('/calendar', function (Request $request) use ($app, $allTaxes, $allGroups, $allReports) {
    $group = $allGroups[$request->get('group-id')];
    /** @var Report[] $reports */
    $reports = [];
    foreach ($group->getReportIds() as $reportId) {
        $reports[] = $allReports[$reportId];
    }
    /** @var Tax[] $taxes */
    $taxes = [];
    foreach ($group->getTaxIds() as $taxId) {
        $taxes[] = $allTaxes[$taxId];
    }

    $startDate = (new DateTime($request->get('date-start')));
    $endDate = (new DateTime($request->get('date-end')));

    $actions = [];

    foreach ($taxes as $tax) {
        /** @var GroupRange[] $ranges */
        $ranges = $tax->getRangesForGroup($group->getId());
        if (count($ranges) > 1) {
            $range = $request->get('tax-range-' . $tax->getId());
        } else {
            $range = $ranges[0]->getRange();
        }
        $periods = DateUtils::getPeriodsWithinRange($range, $startDate, $endDate);
        foreach ($periods as $period) {
            $actions[] = sprintf(
                'Pay %s tax from %s until %s for %s',
                $tax->getName(),
                $period->format('d-m-Y'),
                (new DateTime($period->format('d-m-Y') . '+' . ($tax->getDays() - 1) . ' days'))->format('d-m-Y'),
                $range
            );
        }
    }

    foreach ($reports as $report) {
        $periods = DateUtils::getPeriodsWithinRange($report->getRange(), $startDate, $endDate);
        foreach ($periods as $period) {
            $actions[] = sprintf(
                'Rent out your tax report from %s until %s',
                $period->format('d-m-Y'),
                (new DateTime($period->format('d-m-Y') . '+' . ($report->getDays() - 1) . ' days'))->format('d-m-Y')
            );
        }
    }

    return new JsonResponse([
        'actions' => $actions,
    ]);
})->value('_locale', 'en');

$app->run();
