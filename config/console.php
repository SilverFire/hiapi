<?php
/**
 * HiAPI Yii2 base project for building API
 *
 * @link      https://github.com/hiqdev/hiapi
 * @package   hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

return [
    'app' => [
        'controllerMap' => [
            'api' => [
                '__class' => \hiapi\console\ApiController::class,
            ],
            'hiapi' => [
                '__class' => \hiapi\console\HiapiController::class,
            ],
            'queue' => [
                '__class' => \hiapi\console\QueueController::class,
            ],
        ],
    ],
    \yii\web\User::class => [
        'identity' => \yii\di\Reference::to('console.default-user'),
    ],
    'console.default-user' => [
        '__class' => \hiapi\models\HiamUserIdentity::class,
        'id' => 1,
        'login' => 'console_user',
    ],
/// BUS
    'bus.responder-middleware'          => \hiqdev\yii2\autobus\bus\BypassMiddleware::class,
    'bus.handle-exceptions-middleware'  => \hiqdev\yii2\autobus\bus\BypassMiddleware::class,
    'bus.loader-middleware'             => \hiqdev\yii2\autobus\bus\BypassMiddleware::class,
];