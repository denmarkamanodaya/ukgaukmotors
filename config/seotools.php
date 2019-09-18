<?php

return [
    'meta'      => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults'       => [
            #'title'        => "UK Car Auction Search :: Search ALL UK Car Auctions", // set false to total remove
		'title' => false,
            'description'  => 'FIND YOUR PERFECT MOTOR | UKs Most Powerful Car Auction Search Engine, Cars, Motors, Commercials, Plant and Machinery at Auction. 300,000  Lots Daily', // set false to total remove
            'separator'    => ' - ',
            'keywords'     => [],
            'canonical'    => false, // Set null for using Url::current(), set false to total remove
            'robots'      => 'index,follow'
        ],

        /*
         * Webmaster tags are always added.
         */
        'webmaster_tags' => [
            'google'    => null,
            'bing'      => null,
            'alexa'     => null,
            'pinterest' => null,
            'yandex'    => null,
        ],
    ],
    'opengraph' => [
        /*
         * The default configurations to be used by the opengraph generator.
         */
        'defaults' => [
            'title'       => 'UK Car Auction Search :: Search ALL UK Car Auctions', // set false to total remove
            'description' => 'FIND YOUR PERFECT MOTOR | UKs Most Powerful Car Auction Search Engine, Cars, Motors, Commercials, Plant and Machinery at Auction. 300,000  Lots Daily', // set false to total remove
            'url'         => false, // Set null for using Url::current(), set false to total remove
            'type'        => false,
            'site_name'   => false,
            'images'      => ['http://gaukmotors.co.uk/images/index_top.jpg'],
        ],
    ],
    'twitter' => [
        /*
         * The default values to be used by the twitter cards generator.
         */
        'defaults' => [
          //'card'        => 'summary',
          //'site'        => '@LuizVinicius73',
        ],
    ],
];
