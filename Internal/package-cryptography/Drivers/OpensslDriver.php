<?php namespace ZN\Cryptography\Drivers;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Base;
use ZN\Support;
use ZN\Datatype;
use ZN\Cryptography\CryptoMapping;
use ZN\Cryptography\Exception\InvalidCipherMethodException;

class OpensslDriver extends CryptoMapping
{
	/**
     * Magic constructor
     * 
     * @param void
     * 
     * @return void
     */
	public function __construct()
	{
		Support::func('openssl_open', 'OPENSSL');

		parent::__construct();
	}

	/**
     * It encrypts the data.
     * 
     * @param string $data
     * @param string|array  $settings
     * 
     * @return string
     */
	public function encrypt($data, $settings)
	{
		$this->stringParameter($settings);

		$set = $this->_settings($settings);

		$encode = trim(openssl_encrypt($data, $set->cipher, $set->key, 0, $set->iv));

		return base64_encode($encode);
	}

	/**
     * It decrypts the data.
     * 
     * @param string $data
     * @param array  $settings
     * 
     * @return string
     */
	public function decrypt($data, $settings)
	{
		$this->stringParameter($settings);

		$set  = $this->_settings($settings);
		$data = base64_decode($data);

		return trim(openssl_decrypt(trim($data), $set->cipher, $set->key, 0, $set->iv));
	}

	/**
     * Generates a random password.
     * 
     * @param int $length
     * 
     * @return string
     */
	public function keygen($length)
	{
		return openssl_random_pseudo_bytes($length);
	}

	/**
     * protected vector size
     * 
     * @param string $cipher
	 * @param string $key
     * 
     * @return string
     */
	protected function vectorSize($cipher, $key)
	{
		$iv = openssl_cipher_iv_length($cipher);

		return mb_substr(hash('sha1', $key), 0, $iv);
	}

	/**
     * protected settings
     * 
     * @param array $settings
     * 
     * @return object
     */
    protected function _settings($settings)
    {
		$cipher = $settings['cipher'] ?? 'aes-128';
	 	$key    = $settings['key']    ?? $this->key;
		$mode   = $settings['mode']   ?? 'cbc';
		$cipher = strtolower(Base::suffix($cipher, '-' . $mode));

		if( ! in_array($cipher, openssl_get_cipher_methods()) )
		{
			throw new InvalidCipherMethodException(NULL, $cipher);
		}

		$iv = $this->vectorSize($cipher, $key);
		
        return (object)
        [
            'key'    => $key,
            'iv'     => $iv,
            'cipher' => $cipher
        ];
	}
	
	/**
	 * protected string parameters
	 */
	protected function stringParameter(&$parameters)
	{
		if( is_string($parameters) )
		{
			$ex = explode('-', $parameters);

			$mode = $ex[count($ex) - 1] ?? '';

			array_pop($ex);

			$settings['mode']   = $mode;
			$settings['cipher'] = implode('-', $ex);

			$parameters = $settings;
		}
	}
}
