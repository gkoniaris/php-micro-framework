<?php

global $settings;

$settings = [];
$settings['DB_HOST'] = getenv("DB_HOST");
$settings['DB_USERNAME'] = getenv("DB_USERNAME");
$settings['DB_PASSWORD'] = getenv("DB_PASSWORD");
$settings['DB_NAME'] = getenv("DB_NAME");
