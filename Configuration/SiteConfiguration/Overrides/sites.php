<?php

$languageFile = 'LLL:EXT:sentry_client/Resources/Private/Language/locallang_site.xlf:';

$GLOBALS['SiteConfiguration']['site']['columns']['sentryDns'] = [
    'label' => $languageFile . 'sentryDns',
    'description' => $languageFile . 'sentryDns.description',
    'config' => [
        'type' => 'input',
    ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['sentryReportUserInformation'] = [
    'label' => $languageFile . 'sentryReportUserInformation',
    'description' => $languageFile . 'sentryReportUserInformation.description',
    'config' => [
        'type' => 'select',
        'renderType' => 'selectSingle',
        'items' => [
            [
                'label' => 'none',
                'value' => 'none',
            ],
            [
                'label' => 'userid',
                'value' => 'userid',
            ],
        ],
        'default' => 'userid'
    ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['sentryIgnoreMessageRegex'] = [
    'label' => $languageFile . 'sentryIgnoreMessageRegex',
    'description' => $languageFile . 'sentryIgnoreMessageRegex.description',
    'config' => [
        'type' => 'input',
    ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['sentryReportDatabaseConnectionErrors'] = [
    'label' => $languageFile . 'sentryReportDatabaseConnectionErrors',
    'description' => $languageFile . 'sentryReportDatabaseConnectionErrors.description',
    'config' => [
        'type' => 'select',
        'renderType' => 'selectSingle',
        'items' => [
            [
                'label' => 'false',
                'value' => 0,
            ],
            [
                'label' => 'true',
                'value' => 1,
            ],
        ],
        'default' => 0
    ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['sentryRelease'] = [
    'label' => $languageFile . 'sentryRelease',
    'description' => $languageFile . 'sentryRelease.description',
    'config' => [
        'type' => 'input',
    ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['sentryShowEventId'] = [
    'label' => $languageFile . 'sentryShowEventId',
    'description' => $languageFile . 'sentryShowEventId.description',
    'config' => [
        'type' => 'select',
        'renderType' => 'selectSingle',
        'items' => [
            [
                'label' => 'false',
                'value' => 0,
            ],
            [
                'label' => 'true',
                'value' => 1,
            ],
        ],
        'default' => 1
    ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['sentryLogWriterComponentIgnorelist'] = [
    'label' => $languageFile . 'sentryLogWriterComponentIgnorelist',
    'description' => $languageFile . 'sentryLogWriterComponentIgnorelist.description',
    'config' => [
        'type' => 'input',
    ],
];

$GLOBALS['SiteConfiguration']['site']['columns']['sentryDisableDatabaseLogging'] = [
    'label' => $languageFile . 'sentryDisableDatabaseLogging',
    'description' => $languageFile . 'sentryDisableDatabaseLogging.description',
    'config' => [
        'type' => 'select',
        'renderType' => 'selectSingle',
        'items' => [
            [
                'label' => 'false',
                'value' => 0,
            ],
            [
                'label' => 'true',
                'value' => 1,
            ],
        ],
        'default' => 0
    ],
];

$GLOBALS['SiteConfiguration']['site']['types']['0']['showitem'] .= '
    , --div--;Sentry, sentryDns, sentryReportUserInformation, sentryIgnoreMessageRegex, sentryReportDatabaseConnectionErrors, sentryRelease, sentryShowEventId, sentryLogWriterComponentIgnorelist, sentryDisableDatabaseLogging,
';
