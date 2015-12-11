<?php

// AutoLoader do Composer
$loader = require __DIR__.'/vendor/autoload.php';
// Inclusão das classes ao AutoLoader
$loader->add('doctrine', __DIR__.'/src');


use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

// Se for falso usa o APC como cache, se for true usa cache em arrays
$isDevMode = false;

// Path das entidades
$paths = array(__DIR__ . '/src/Doctrine/Model');

// Configurações do banco de dados
$dbParams = array(
    'driver'   => 'pdo_mysql',
    'user'     => 'root',
    'password' => 'root',
    'dbname'   => 'doctrine',
);

$config = Setup::createConfiguration($isDevMode);

// Leitor das annotations das entidades
$driver = new AnnotationDriver(new AnnotationReader(), $paths);
$config->setMetadataDriverImpl($driver);

// Registra as annotations do Doctrine
AnnotationRegistry::registerFile(
    __DIR__ . '/vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php'
);

// Cria o entityManager
$entityManager = EntityManager::create($dbParams, $config);