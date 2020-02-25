<?= view::css([
    'resource' => [
        'bootstrap' =>
            ['css/bootstrap'],
        'metronic' => [
            'admin' => [
                'layout/css' => [
                    'layout',
                    'themes/darkblue'
                ],
                'pages/css/' => ['error'],
            ],
            'css' => ['components', 'plugins','overwrite'],
        ],
        'plugins' => [
            'uniform/css/uniform.default',
        ],
        'base' => ['global']
    ]
]); ?>

<?= view::js([
    'resource' => [
        'jquery' => ['jquery.min'],
        'base' => ['global']
    ]
])?>

