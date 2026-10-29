
<?php

// Run only from CLI is safer; do not expose a public key generator.
// This script prints a new Laravel-compatible AES-256 key.

$key = 'base64:' . base64_encode(random_bytes(32));

header('Content-Type: text/plain; charset=utf-8');
echo $key;
