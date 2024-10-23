<?php
    defined("BASEPATH") OR exit("No direct script access allowed");

    if (file_exists(__DIR__ . '/.env')) {
		$lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		foreach ($lines as $line) {
			if (strpos(trim($line), '#') === 0) continue; // Skip comments
			list($key, $value) = explode('=', $line, 2);
			putenv(trim("$key=$value"));
			$_ENV[trim($key)] = trim($value);
		}
	}