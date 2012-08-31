<?php
return array(
    'showScriptName' => false,
    'urlFormat' => 'path',
    'rules' => array(
        'http://mobile.mobispot.spb4.webdoka.ru/' => 'mobile',
        '<controller:\w+>/<id:\d+>' => '<controller>/view',
        '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
        '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
    ),
);
