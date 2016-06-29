
Yii2 user module.
===================
Standard yii2 module for user management.

Installation
------------

1. Mention module class in app config
```
'modules' => [
    ...
    'user' => [
        'class' => 'bariew\userAbstractModule\Module',
        'params' => [
            'emailConfirm' => false, // whether registration email confirm is required
            'resetTokenExpireSeconds' => 86400 // seconds till password reset token expires
        ]
    ]
]
```

2. Set app user component identity class.
```
'components' => [
    ...
    'user' => [
        'identityClass' => 'bariew\userAbstractModule\models\User',
    ],
    'authClientCollection' => [
        'class' => 'yii\authclient\Collection',
        'clients' => [
            'google' => [
                'class' => 'yii\authclient\clients\GoogleOAuth',
                'clientId' => 'google_client_id',
                'clientSecret' => 'google_client_secret',
            ],
            'facebook' => [
                'class' => 'yii\authclient\clients\Facebook',
                'clientId' => 'facebook_client_id',
                'clientSecret' => 'facebook_client_secret',
            ],
        ],
    ]
]
```