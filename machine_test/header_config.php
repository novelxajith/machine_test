<?php
// header_config.php - Centralized configuration for security headers

// Enable XSS protection
header("X-XSS-Protection: 1; mode=block");

// Set content type options to prevent content sniffing
header("X-Content-Type-Options: nosniff");

// Enable Strict-Transport-Security to force the use of HTTPS
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");

// Enable Content Security Policy (CSP) to control resources loaded by your page
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline';");

// Prevent browsers from embedding your site in an iframe (clickjacking protection)
header("X-Frame-Options: DENY");

// Disable the Server header to obscure information about the server
header("Server: ");

// Set a Referrer-Policy to control how much referrer information is included in requests
header("Referrer-Policy: no-referrer");
?>
