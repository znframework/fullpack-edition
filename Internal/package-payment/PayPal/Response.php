<?php namespace ZN\Payment\PayPal;

use ZN\Services\CURL;
use ZN\Payment\Exception;

class Response
{
    /**
     * Is valid hash
     * 
     * @return bool
     */
    public function isValidHash() : bool
    {
        if( empty($_POST['custom']) )
        {
            return false;
        }

        $query = 'cmd=_notify-validate&' . http_build_query($_POST);

        $url = explode(',', $_POST['custom'])[0];

        $curl = new CURL;

        return $curl->init($url)
                    ->post(true)
                    ->postfields($query)
                    ->returntransfer(true)
                    ->sslVerifypeer(false)
                    ->sslVerifyhost(false)
                    ->exec() === 'VERIFIED';
    }

    /**
     * Is approved
     * 
     * @return bool
     */
    public function isApproved() : bool
    {
        return ! empty($_POST['payment_status']) && ($_POST['payment_status'] ?? NULL) === 'Completed';
    }

    /**
     * Get error messages
     * 
     * @return string|false
     */
    public function error() 
    {
        if( $this->isApproved() )
        {
            return false;
        }

        return $_POST['payment_status'] ?? 'Payment status not found!';
    }
}