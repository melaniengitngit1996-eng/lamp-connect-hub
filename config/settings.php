<?php

return [

    'general' => [

        'organization_name' => [
            'label' => 'Organization Name',
            'type' => 'text',
            'default' => 'LAMP Church',
            'rules' => ['required', 'string', 'max:255'],
        ],

        'support_email' => [
            'label' => 'Support Email',
            'type' => 'email',
            'default' => null,
            'rules' => ['nullable', 'email'],
        ],

        'auto_approve_members' => [
            'label' => 'Auto Approve Members',
            'type' => 'boolean',
            'default' => false,
            'rules' => ['boolean'],
        ],

    ],

    'chat' => [

        'personal_chat_enabled' => [
            'type' => 'boolean',
            'default' => true,
            'rules' => ['boolean'],
        ],

        'group_chat_enabled' => [
            'type' => 'boolean',
            'default' => true,
            'rules' => ['boolean'],
        ],
        'max_upload_size' => [
            'type' => 'number',
            'default' => 50,
            'rules' => ['required', 'integer', 'min:1', 'max:1024'],
        ],

    ],

    'feed' => [

        'feed_posting_enabled' => [
            'type' => 'boolean',
            'default' => true,
            'rules' => ['boolean'],
        ],

        'feed_comments_enabled' => [
            'type' => 'boolean',
            'default' => true,
            'rules' => ['boolean'],
        ],

    ],

    'drive' => [

        'max_upload_size' => [
            'type' => 'number',
            'default' => 50,
            'rules' => ['required', 'integer', 'min:1', 'max:1024'],
        ],

    ],

];
