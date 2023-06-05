<?php

include 'api_key.php';

define( 'MY_API_KEY', password_hash( MY_API_KEY_DECODED, PASSWORD_DEFAULT ) );

define( 'ROOT_PATH', '/rc-api' );