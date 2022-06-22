<?php namespace ZN\Cryptography;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Ability\Driver;

class Crypto implements CryptoInterface
{
    use Driver;

    /**
     * Get driver
     * 
     * @const array
     */
    const driver =
    [
        'options'   => ['openssl'],
        'namespace' => 'ZN\Cryptography\Drivers',
        'config'    => 'Cryptography',
        'default'   => 'ZN\Cryptography\CryptographyDefaultConfiguration'
    ];

    /**
     * It encrypts the data.
     * 
     * @param string $data
     * @param string|array  $settings
     * 
     * @return string
     */
    public function encrypt(string $data, $settings = []) : string
    {
        return $this->driver->encrypt($data, $settings);
    }

    /**
     * It decrypts the data.
     * 
     * @param string $data
     * @param string|array  $settings
     * 
     * @return string
     */
    public function decrypt(string $data, $settings = []) : string
    {
        return $this->driver->decrypt($data, $settings);
    }

    /**
     * Generates a random password.
     * 
     * @param int $length
     * 
     * @return string
     */
    public function keygen(int $length = 8) : string
    {
        return $this->driver->keygen($length);
    }
}
