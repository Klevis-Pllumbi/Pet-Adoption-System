<?php
require __DIR__ . '/vendor/autoload.php'; // Include Composer's autoloader

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

class PayPalClient
{

    public static function client()
    {
        return new PayPalHttpClient(self::environment());
    }

    public static function environment()
    {
        $clientId = 'AW15SUTm9moaYDwBt5oEJQ59b8Q_E-4fzzfYep_vFCFvmOkz0gYOJMV1QGh8Lm-4pdbciJ5U-xlODYhC';
        $clientSecret = 'EBt_gpYgDzKLKb9n0o0V2Up2WuUQmbi2lVwaMP_W7gWVUQw3BOFp3qw2A64T2sDsyF-J9ZBU5GA4-ho7';
        return new SandboxEnvironment($clientId, $clientSecret);
    }
}