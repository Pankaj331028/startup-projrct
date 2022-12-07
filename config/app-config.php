<?php

    return [
        /*
        | Application display date format
        */
        'date_format' => [
            'date' => 'd/M/Y',
            'time' => 'h:i A',
        ],

        /*
        | Default plan pricing, if no pricing available the the default will be provided
        */
        'default_plan_price' => [
            'amount' => env('DEFAULT_PLAN_PRICE_AMOUNT', 200),
            'tax' => env('DEFAULT_PLAN_PRICE_TAX', 2),
            'total_amount' => env('DEFAULT_PLAN_PRICE_TOTAL_AMOUNT', 204),
        ],

        /*
        | Default count to display data in datatable
        */
        'datatable_default_row_count' => 25,

        /*
        | Default API pagination count
        */
        'api_default_pagination_count' => 10,

        /*
        | Whenever user signup via web or via API. Default role will ve customer
        */
        'default_signup_role' => 'customer',

        /*
        | SMS formates for different activities
        */
        'sms_format' => [
            /*
            | Sign in SMS format. ? represent OTP
            */
            'app_auth_otp' => 'Fixoti: Use ? OTP to login.',
        ],
    ];
