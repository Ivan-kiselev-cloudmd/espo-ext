?php

namespace Espo\Custom\Core\Utils;

class Encryption
{
    private $secret_key = null;
    private $iv = null;
    protected $method = 'AES-256-CBC';

public function __construct($secret_key, $iv = null, $method = 'AES-256-CBC')
{
    $this-&gt;secret_key = $secret_key;
    if ($iv) {
    $this-&gt;iv = hex2bin($iv);
}
}

public static function get_random_key($length)
{
    return openssl_random_pseudo_bytes($length, $crypto_strong);
}

public function get_algorithms()
{
    return $this-&gt;method;
}

public function set_algorithms($algorithm)
{
    $methods = $this-&gt;available_algorithms();
    if (isset($methods[$algorithm])) {
    $this-&gt;method = $algorithm;
} else {
    throw new \Exception('Encryption ['.$algorithm.'] method is not available', 1);
}
}

public function available_algorithms()
{
    return openssl_get_cipher_methods();
}

public function encrypt($string_data)
{    // Use the openssl_encrypt to generate the ciphertext for the given $string_data and return the same.}

public function get_ivlen()
{
    return $this-&gt;ivlen;
}

public function get_iv()
{
    return bin2hex($this-&gt;iv);
}

public function decrypt($string_cypher)
{
    // Use the openssl_decrypt to generate the string text for the given $string_cypher and return the same.
}
}
