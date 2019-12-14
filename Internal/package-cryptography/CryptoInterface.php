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

interface CryptoInterface
{
    /**
     * It encrypts the data.
     * 
     * @param string $data
     * @param string|array  $settings
     * 
     * @return string
     */
    public function encrypt(String $data, $settings = []) : String;

    /**
     * It decrypts the data.
     * 
     * @param string $data
     * @param string|array  $settings
     * 
     * @return string
     */
    public function decrypt(String $data, $settings = []) : String;

    /**
     * Generates a random password.
     * 
     * @param int $length
     * 
     * @return string
     */
    public function keygen(Int $length = 8) : String;
}
