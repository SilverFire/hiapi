<?php
/**
 * HiAPI Yii2 base project for building API
 *
 * @link      https://github.com/hiqdev/hiapi
 * @package   hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

return array_filter([
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@vendor/bower' => '@vendor/bower-asset',
        '@vendor/npm' => '@vendor/npm-asset',
    ],
    'controllerNamespace' => 'hiapi\controllers',
    'bootstrap' => array_filter([
        'debug' => empty($params['debug.enabled']) ? null : 'debug',
    ]),
    'components' => [
        'request' => [
            'enableCsrfCookie' => false,
            'enableCsrfValidation' => false,
        ],
        'mailer' => [
            'composer' => [
                'viewPath' => '@hiapi/views/mail',
                'htmlLayout' => '@hiapi/views/layouts/mail-html',
                'textLayout' => '@hiapi/views/layouts/mail-text',
            ],
        ],
        'urlManager' => [
            '__class' => \yii\web\UrlManager::class,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                'default' => [
                    'pattern' => '<version:v\d+>/<resource:[\w-]+>/<action:[\w-]+>/<bulk:(bulk)?>',
                    'route' => 'api/command',
                    'defaults' => [
                        'version' => 'v1',
                        'bulk' => false,
                    ],
                ],
            ],
        ],
    ],
    'modules' => array_filter([
        'debug' => empty($params['debug.enabled']) ? null : array_filter([
            '__class' => \yii\debug\Module::class,
            'allowedIPs' => isset($params['debug.allowedIps']) ? $params['debug.allowedIps'] : null,
            'historySize' => isset($params['debug.historySize']) ? $params['debug.historySize'] : null,
        ]),
    ]),
    'container' => [
        'singletons' => [
        /// BUS
            'bus.responder-middleware' => [
                '__class' => ($_ENV['ENABLE_JSONAPI_RESPONSE'] ?? false)
                    ? \hiapi\middlewares\JsonApiMiddleware::class
                    : \hiapi\middlewares\LegacyResponderMiddleware::class,
            ],
            'bus.handle-exceptions-middleware' => \hiapi\middlewares\HandleExceptionsMiddleware::class,
            'bus.loader-middleware' => \hiqdev\yii2\autobus\bus\LoadFromRequestMiddleware::class,

        /// Request & response
            \Psr\Http\Message\ServerRequestInterface::class => function ($container) {
                return \GuzzleHttp\Psr7\ServerRequest::fromGlobals();
            },
            \Psr\Http\Message\ResponseInterface::class => function ($container) {
                return new \GuzzleHttp\Psr7\Response();
            },
            \WoohooLabs\Yin\JsonApi\Request\RequestInterface::class => \WoohooLabs\Yin\JsonApi\Request\Request::class,
            \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface::class => \WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory::class,
        ],
    ],
]);
