<?php

    return [
        'id' => 'ID',
        'is_active' => 'Is Active',
        'status' => 'Status',
        'createdby_userid' => 'Created By',
        'updatedby_userid' => 'Updated By',
        'created_at' => 'Created at',
        'created_at' => 'Updated at',

        'languages' => [
            'title' => 'Languages',
            'title_singular' => 'Language',
            'fields' => [
                'language_name' => 'Language Name',
                'language_short_code' => 'Language Short Code',
            ],
        ],
        'countries' => [
            'title' => 'Countries',
            'title_singular' => 'Country',
            'fields' => [
                'country_name' => 'Country Name',
                'country_code' => 'Country Code',
                'default_language_short_code' => 'Default Language Short Code',
                'default_language' => 'Default Language',
            ],
        ],
        'cities' => [
            'title' => 'Cities',
            'title_singular' => 'City',
            'fields' => [
                'city_name' => 'City Name',
                'country' => 'Country',
            ],
        ],
        'badges' => [
            'title' => 'Badges',
            'title_singular' => 'Badge',
            'fields' => [
                'badge_name' => 'Badge Name',
                'badge_cost' => 'Badge Cost',
                'badge_image' => 'Badge Image',
            ],
        ],
        'tags' => [
            'title' => 'Tags',
            'title_singular' => 'Tag',
            'fields' => [
                'tag_name' => 'Tag Name',
                'tag_type' => 'Tag Type',
            ],
        ],
        'zipcodes' => [
            'title' => 'Zipcodes',
            'title_singular' => 'Zipcode',
            'fields' => [
                'zipcode' => 'Zipcode',
            ],
        ],
        'pricing_plans' => [
            'title' => 'Pricing Plans',
            'title_singular' => 'Pricing Plan',
            'fields' => [
                'pricing_plan_name' => 'Pricing plan name',
                'pricing_plan_type' => 'Pricing plan type',
                'pricing_plan_payment_frequency' => 'Pricing plan payment frequency',
                'pricing_plan_duration' => 'Pricing plan duration (in days)',
                'amount' => 'Amount',
                'tax' => 'Tax (in percentage)',
                'total_amount' => 'Total amount',
                'details' => 'Details',
                'valid_from' => 'Valid from',
                'valid_to' => 'Valid to',
            ],
        ],
        'pricing_plan_details' => [
            'title' => 'Pricing Plan Details',
            'title_singular' => 'Pricing Plan Detail',
            'fields' => [
                'pricing_plan_keyname' => 'Key name',
                'pricing_plan_value' => 'Key value',
                'valid_from' => 'Valid from',
                'valid_to' => 'Valid to',
            ],
        ],
        'pricing_plan_histories' => [
            'title' => 'Pricing Plan Histories',
            'title_singular' => 'Pricing Plan History',
            'fields' => [
                'amount' => 'Amount',
                'tax' => 'Tax (in percentage)',
                'total_amount' => 'Total amount',
                'valid_from' => 'Valid from',
                'valid_to' => 'Valid to',
            ],
        ],
        'users' => [
            'title' => 'Users',
            'title_singular' => 'User',
            'fields' => [
                'role' => 'Role',
                'name' => 'Name',
                'first_name' => 'First name',
                'last_name' => 'Last name',
                'email' => 'Email',
                'user_phone' => 'User phone',
                'user_type' => 'User type',
                'user_name' => 'User name',
                'birthday' => 'Birthday',
                'gender' => 'Gender',
                'verified_on' => 'Verified on',
                'about_the_user' => 'About',
                'intro' => 'Intro',
                'vaccination_status' => 'Vaccination status', // health_information_details
                'signup_date' => 'Signup date',
                'plan_type' => 'Plan type',
                'status' => 'Status',
            ],
        ],
    ];
