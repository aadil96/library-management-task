
<?php

return [
    # Code Range Limit
    'min' => 100,
    'max' => 1024,

    # Error Message Code Mapping
    'map' => [
        // Api_Code => <Message_key>
        \App\Lib\ApiCode::OK => 'custom_api_en.ok',
        \App\Lib\ApiCode::SUCCESS => 'custom_api_en.success',
        \App\Lib\ApiCode::BAD_REQUEST => 'custom_api_en.bad_request',
        \App\Lib\ApiCode::UNAUTHENTICATED => 'custom_api_en.unauthenticated',
        \App\Lib\ApiCode::UNAUTHORIZED => 'custom_api_en.unauthorized',
        \App\Lib\ApiCode::NOT_FOUND => 'custom_api_en.not_found',
        \App\Lib\ApiCode::VALIDATION_ERROR => 'custom_api_en.validation_error',
        \App\Lib\ApiCode::SERVER_ERROR => 'custom_api_en.server_error',
//        \App\Lib\ApiCode::INVALID_CREDENTIALS => 'custom_api_en.invalid_credentials',
//        \App\Lib\ApiCode::INVALID_SIGNATURE => 'custom_api_en.invalid_signature',
//        \App\Lib\ApiCode::NO_RECORD_FOUND => 'custom_api_en.no_record_found',
//        \App\Lib\ApiCode::OTP_SENT => 'custom_api_en.otp_sent',
//        \App\Lib\ApiCode::OTP_VERIFIED => 'custom_api_en.otp_verified',
//        \App\Lib\ApiCode::SERVICE_FAILURE => 'custom_api_en.service_failure',
//        \App\Lib\ApiCode::SUB_CANCELLED => 'custom_api_en.sub_cancelled',
//        \App\Lib\ApiCode::SUB_INACTIVE => 'custom_api_en.sub_inactive',
//        \App\Lib\ApiCode::SUB_PAUSED => 'custom_api_en.sub_paused',
//        \App\Lib\ApiCode::SUB_REMSUMED => 'custom_api_en.sub_resumed',
//        \App\Lib\ApiCode::SUB_START_FAILURE => 'custom_api_en.sub_start_failure',
//        \App\Lib\ApiCode::SUB_STARTED => 'custom_api_en.sub_started',
//        \App\Lib\ApiCode::SUB_TRIAL_START => 'custom_api_en.sub_trial_start',
//        \App\Lib\ApiCode::USER_EXISTS => 'custom_api_en.user_exists',
    ]
];
