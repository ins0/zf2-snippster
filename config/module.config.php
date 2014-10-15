<?php
return array(

    'snippster_configuration' => array(
        'cache' => array(
            'adapter' => 'Zend\Cache\Storage\Adapter\Filesystem',
            'options' => array(
                'cache_dir' => './data/cache'
            )
        ),
    ),


    'service_manager' => array(
        'factories' => array(
            'Snippster' => 'Snippster\Service\SnippsterServiceFactory',
        ),
    ),

);