<?php
/**
 * Configuration file generated by ZF Apigility Admin
 *
 * The previous config file has been stored in ./config/application.config.old
 */
return array(
    'modules' => array(
        'Application',
        'ZF\\Configuration',
        'ZF\\DevelopmentMode',
        'ZF\\Apigility',
        'ZF\\Apigility\\Provider',
        'AssetManager',
        'ZF\\ApiProblem',
        'ZF\\MvcAuth',
        'ZF\\OAuth2',
        'ZF\\Hal',
        'ZF\\ContentNegotiation',
        'ZF\\ContentValidation',
        'ZF\\Rest',
        'ZF\\Rpc',
        'ZF\\Versioning',
        'ZF\\Apigility\\Documentation',
        'DoctrineModule',
        'DoctrineORMModule',
        'Infra',
        'ZfrCors',
        'ZF\\Apigility\\Admin',
    ),
    'module_listener_options' => array(
        'module_paths' => array(
            './module',
            './vendor',
        ),
        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
    ),
);
