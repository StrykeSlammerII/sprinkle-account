<?php

/*
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @copyright Copyright (c) 2019 Alexander Weissman
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/LICENSE.md (MIT License)
 */

/**
 * Russian message token translations for the 'account' sprinkle.
 *
 * @author @rendername
 */
return [
    'ACCOUNT' => [
        '@TRANSLATION' => 'Аккаунт',

        'ACCESS_DENIED' => 'Для получения доступа у вас недостаточно прав.',

        'DISABLED' => 'Аккаунт отключен. Пожалуйста, свяжитесь с нами для получения дополнительной информации.',

        'EMAIL_UPDATED' => 'Email аккаунта обновлён',

        'INVALID' => 'Этот аккаунт не существует. Возможно, он удалён.  Пожалуйста, свяжитесь с нами для получения дополнительной информации.',

        'MASTER_NOT_EXISTS' => 'Вы не можете зарегистрировать аккаунт до тех пор, пока основная учётная запись не будет создана!',
        'MY'                => 'Мой профиль',

        'SESSION_COMPROMISED' => [
            '@TRANSLATION'  => 'Ваша сессия была скомпрометирована. Вы должны выйти на всех устройствах, а затем снова войти и убедиться, что ваши данные не были изменены.',
            'TITLE'         => 'Возможно, ваш аккаунт был скомпрометированн',
            'TEXT'          => 'Возможно, кто-то использовал ваши данные для входа на эту страницу. В целях безопасности все сеансы были завершены. Пожалуйста, повторно <a href="{{url}}"> войдите </a> и проверьте свой аккаунт на подозрительную активность. Рекомендуем сменить пароль.'
        ],
        'SESSION_EXPIRED'       => 'Срок вашей сессии истек. Пожалуйста войдите еще раз.',

        'SETTINGS' => [
            '@TRANSLATION'  => 'Настройки аккаунта',
            'DESCRIPTION'   => 'Обновите настройки своего аккаунта, включая адрес электронной почты, имя и пароль.',
            'UPDATED'       => 'Данные аккаунта обновлены'
        ],

        'TOOLS' => 'Инструменты аккаунта',

        'UNVERIFIED' => 'Ваш аккаунт ещё не подтверждён. Проверьте вашу email почту, в том числе папку спам и следуйте инструкциям.',

        'VERIFICATION' => [
            'NEW_LINK_SENT'     => 'Мы отправили на ваш email новую ссылку для активации {{email}}. Пожалуйста, проверьте папку "Входящие" и "Спам".',
            'RESEND'            => 'Повторно отправить письмо с подтверждением',
            'COMPLETE'          => 'Вы успешно подтвердили свой аккаунт. Теперь вы можете войти.',
            'EMAIL'             => 'Введите email, который вы использовали для регистрации, вам будет повторно отправлено письмо с подтверждением.',
            'PAGE'              => 'Повторно оправить письмо подтверждения на email для нового аккаунта.',
            'SEND'              => 'Проверка по электронной почте для аккаунта',
            'TOKEN_NOT_FOUND'   => 'Код подтверждения не действителен либо аккаунт уже подтверждён',
        ]
    ],

    'EMAIL' => [
        'INVALID'               => 'Нет не одного аккаунта с <strong> {{email}} </strong>.',
        'IN_USE'                => 'Email <strong>{{email}}</strong> уже используется.',
        'VERIFICATION_REQUIRED' => 'Email (указывайте верный - необходим для активации!)'
    ],

    'EMAIL_OR_USERNAME' => 'Имя пользователя или Email',

    'FIRST_NAME' => 'Имя',

    'HEADER_MESSAGE_ROOT' => 'ВЫ АВТОРИЗОВАНЫ С СУПЕР-ПРАВАМИ',

    'LAST_NAME' => 'Фамилия',
    'LOCALE'    => [
        'ACCOUNT' => 'Основной язык для вашего аккаунта',
        'INVALID' => '<strong>{{locale}}</strong> язык недопустим.'
    ],
    'LOGIN' => [
        '@TRANSLATION'      => 'Вход',
        'ALREADY_COMPLETE'  => 'Вы уже выполнили вход!',
        'SOCIAL'            => 'Или войти через',
        'REQUIRED'          => 'Извините, Вы должны авторизоваться для доступа к этому ресурсу.'
    ],
    'LOGOUT' => 'Выход',

    'NAME' => 'Имя',

    'NAME_AND_EMAIL' => 'Имя и email',

    'PAGE' => [
        'LOGIN' => [
            'DESCRIPTION'   => 'Войдите в свой аккаунт {{site_name}}, или Зарегистрируйтесь.',
            'SUBTITLE'      => 'Зарегистрироваться или войти в существующий аккаунт.',
            'TITLE'         => 'Приступим!',
        ]
    ],

    'PASSWORD' => [
        '@TRANSLATION' => 'Пароль',

        'BETWEEN'   => 'Кол-во {{min}}-{{max}} символов',

        'CONFIRM'               => 'Подтверждение пароля',
        'CONFIRM_CURRENT'       => 'Пожалуйста, введите ваш текущий пароль',
        'CONFIRM_NEW'           => 'Подтвердите новый пароль',
        'CONFIRM_NEW_EXPLAIN'   => 'Повторно введите Ваш новый пароль',
        'CONFIRM_NEW_HELP'      => 'Требуется только при выборе нового пароля',
        'CREATE'                => [
            '@TRANSLATION'  => 'Создать пароль',
            'PAGE'          => 'Выберите пароль для вашего аккаунта.',
            'SET'           => 'Установить пароль и войти'
        ],
        'CURRENT'               => 'Текущий пароль',
        'CURRENT_EXPLAIN'       => 'Для продолжения вы должны ввести текущий пароль',

        'FORGOTTEN' => 'Забытый пароль?',
        'FORGET'    => [
            '@TRANSLATION' => 'Я забыл свой пароль',

            'COULD_NOT_UPDATE'  => 'Не удалось обновить пароль.',
            'EMAIL'             => 'Пожалуйста, введите адрес электронной почты, который Вы использовали при регистрации. Ссылка с инструкцией по сбросу пароля будет отправлена вам по электронной почте.',
            'EMAIL_SEND'        => 'Ссылка сброса пароля по Email',
            'INVALID'           => 'Этот запрос сброса пароля не может быть найден, или истек.  Пожалуйста, попробуйте <a href="{{url}}"> повторно сбросить пароль<a>.',
            'PAGE'              => 'Получите ссылку для сброса пароля.',
            'REQUEST_CANNED'    => 'Запрос на сброс пароля отменен.',
            'REQUEST_SENT'      => 'Если email <strong>{{email}}</strong> существует в нашей системе у какого-либо аккаунта, ссылка на сброс пароля будет направлена на <strong>{{email}}</strong>.'
        ],

        'HASH_FAILED'       => 'Хэширование пароля не удалось. Пожалуйста, попробуйте другой пароль, либо свяжитесь с администратором сайта.',
        'INVALID'           => 'Текущий пароль не соответствует тому, который задан в системе.',
        'NEW'               => 'Новый пароль',
        'NOTHING_TO_UPDATE' => 'Невозможно обновить с тем же паролем',

        'RESET' => [
            '@TRANSLATION'      => 'Сбросить пароль',
            'CHOOSE'            => 'Пожалуйста, выберите новый пароль, чтобы продолжить.',
            'PAGE'              => 'Выберите новый пароль для вашего аккаунта.',
            'SEND'              => 'Задать новый пароль и войти'
        ],

        'UPDATED'           => 'Пароль аккаунта обновлён'
    ],

    'PROFILE'       => [
        'SETTINGS'  => 'Настройки профиля',
        'UPDATED'   => 'Настройки профиля обновлены'
    ],

    'RATE_LIMIT_EXCEEDED'       => 'Превышен лимит попыток для этого действия. Вы должны подождать еще {{delay}} секунд, прежде чем вам вам будет разрешено сделать ещё попытку.',

    'REGISTER'      => 'Регистрация',
    'REGISTER_ME'   => 'Зарегистрируйте меня',
    'REGISTRATION'  => [
        'BROKEN'            => 'К сожалению, есть проблема с регистрации аккаунта. Свяжитесь с нами напрямую для получения помощи.',
        'COMPLETE_TYPE1'    => 'Вы успешно зарегистрировались. Теперь вы можете войти.',
        'COMPLETE_TYPE2'    => 'Вы успешно зарегистрировались. Ссылка для активации вашего аккаунта была отправлена на <strong>{{email}}</strong>. Вы сможете войти в систему только после активации аккаунта.',
        'DISABLED'          => 'Извините, регистрация аккаунта была отключена.',
        'LOGOUT'            => 'Извините, вы не можете зарегистрироваться когда уже авторизовались в системе. Сначала выйдите из системы.',
        'WELCOME'           => 'Быстрая и простая регистрация.'
    ],
    'REMEMBER_ME'               => 'Запомнить',
    'REMEMBER_ME_ON_COMPUTER'   => 'Запомнить меня на этом компьютере (не рекомендуется для общедоступных компьютеров)',

    'SIGN_IN_HERE'          => 'Уже есть аккаунт? <a href="{{url}}"> войти.</a>',
    'SIGNIN'                => 'Вход',
    'SIGNIN_OR_REGISTER'    => 'Регистрация или вход',
    'SIGNUP'                => 'Вход',

    'TOS'           => 'Пользовательское соглашение',
    'TOS_AGREEMENT' => 'Регистрируя аккаунт на {{site_title}}, вы принимаете <a {{link_attributes | raw}}> условия и положения</a>.',
    'TOS_FOR'       => 'Правила и условия для {{title}}',

    'USERNAME' => [
        '@TRANSLATION' => 'Пользователь',

        'CHOOSE'        => 'Выберите имя пользователя',
        'INVALID'       => 'Недопустимое имя пользователя',
        'IN_USE'        => '<strong>{{user_name}}</strong> имя пользователя уже используется.',
        'NOT_AVAILABLE' => 'Имя пользователя <strong>{{user_name}}</strong> не доступно. Выберите другое имя или нажмите кнопку «предложить».'
    ],

    'USER_ID_INVALID'       => 'ID запрашиваемого пользователя не существует.',
    'USER_OR_EMAIL_INVALID' => 'Имя пользователя или email не верный.',
    'USER_OR_PASS_INVALID'  => 'Пользователь не найден или пароль является недействительным.',

    'WELCOME' => 'Добро пожаловать, {{first_name}}'
];
