<?php namespace ZN\Payment\Nestpay;

use ZN\Request\URL;
use ZN\Services\CURL;
use ZN\Request\Method;
use ZN\Payment\Currency;
use ZN\Payment\Exception;
use ZN\Payment\GatewayRequestAbstract;

class Request extends GatewayRequestAbstract
{
    /**
     * Protected banks
     * 
     * @var array
     */
    protected $banks = 
    [
        'isbank'        => 'https://spos.isbank.com.tr/servlet/est3Dgate',
        'akbank'        => 'https://www.sanalakpos.com/servlet/est3Dgate',
        'finansbank'    => 'https://www.fbwebpos.com/servlet/est3Dgate',
        'halkbank'      => 'https://sanalpos.halkbank.com.tr/servlet/est3Dgate',
        'anadolubank'   => 'https://anadolusanalpos.est.com.tr/servlet/est3Dgate',
        'ziraatbank'    => 'https://sanalpos2.ziraatbank.com.tr/fim/est3Dgate',
        'teb'           => 'https://sanalpos.teb.com.tr/fim/est3Dgate',
        'test'          => 'https://entegrasyon.asseco-see.com.tr/fim/est3Dgate'
    ];

     /**
     * Protected card types
     * 
     * @var array
     */
    protected $cardTypes = [1 => 1, 2 => 2, 'visa' => 1, 'mastercard' => 2];

    /**
     * Protected required
     * 
     * @var array
     */
    protected $required = 
    [
        'amount'   => 'Amount', 
        'storeKey' => 'Store Key',
        'clientid' => 'Client ID',
        'okUrl'    => 'OK URL'
    ];

    /**
     * Sets card number.
     * 
     * @param string $number
     * @param string $month
     * @param string $year
     * @param string $cvc
     * 
     * @return $this
     */
    public function card(string $number, string $month, string $year, string $cvc)
    {
        $this->yearAndMonthConverter($year, $month);

        $this->settings['pan'] = $number;
        $this->settings['Ecom_Payment_Card_ExpDate_Year']  = $year;
        $this->settings['Ecom_Payment_Card_ExpDate_Month'] = $month;
        $this->settings['cv2'] = $cvc;

        return $this;
    }

    /**
     * Sets card type.
     * 
     * @param int|string $type
     * 
     * @return $this
     */
    public function cardType($type)
    {
        if( ! isset($this->cardTypes[$type]) )
        {
            throw new Exception\InvalidCardTypeException(NULL, $type);
        }

        $this->settings['cardType'] = $this->cardTypes[$type];

        return $this;
    }

    /**
     * Sets client id.
     * 
     * @param string $id
     * 
     * @return $this
     */
    public function clientId(string $id)
    {
        $this->settings['clientid'] = $id;

        return $this;
    }

    /**
     * Sets order id.
     * 
     * @param string $id
     * 
     * @return $this
     */
    public function orderId(string $id)
    {
        $this->settings['oid'] = $id;

        return $this;
    }

    /**
     * Sets amount.
     * 
     * @param string $amount
     * 
     * @return $this
     */
    public function amount(string $amount)
    {
        $this->settings['amount'] = $amount;

        return $this;
    }

    /**
     * Sets currency.
     * 
     * @param string|int $currency
     * 
     * @return $this
     */
    public function currency($currency)
    {
        $this->settings['currency'] = Currency::get($currency);

        return $this;
    }

    /**
     * Sets random key.
     * 
     * @param string $random
     * 
     * @return $this
     */
    public function randomKey(string $random)
    {
        $this->settings['rnd'] = $random;

        return $this;
    }

    /**
     * Sets return url.
     * 
     * @param string $success
     * @param string $fail
     * 
     * @return $this
     */
    public function returnUrl(string $success, string $fail = NULL)
    {
        $this->settings['okUrl'] = URL::site($success);
        $this->settings['failUrl'] = URL::site($fail ?? $success);

        return $this;
    }

    /**
     * Sets company name.
     * 
     * @param string $name
     * 
     * @return $this
     */
    public function companyName(string $name)
    {
        $this->settings['firmaadi'] = $name;

        return $this;
    }

    /**
     * Sets installment.
     * 
     * @param string $name
     * 
     * @return $this
     */
    public function installment(string $name)
    {
        $this->settings['taksit'] = $name;

        return $this;
    }

    /**
     * Sets process type.
     * 
     * @param string $type
     * 
     * @return $this
     */
    public function processType(string $type)
    {
        $this->settings['islemtipi'] = $type;

        return $this;
    }

    /**
     * Sets store key.
     * 
     * @param string $key
     * 
     * @return $this
     */
    public function storeKey(string $key)
    {
        $this->settings['storeKey'] = $key;

        return $this;
    }

    /**
     * Send request.
     * 
     * @param string $type;
     */
    public function send(string $type)
    {
        $this->missingInformation();
        
        $this->default();

        if( ! isset($this->banks[$type]) )
        {
            throw new Exception\InvalidBankException(NULL, $type);
        }

        $curl = new CURL;

        return $curl->init($this->banks[$type])
                    ->post(true)
                    ->postfields(URL::buildQuery($this->settings))
                    ->returntransfer(true)
                    ->sslVerifypeer(false)
                    ->exec();
    }

    /**
     * Protected signature
     */
    protected function signature()
    {
        $signature =     $this->settings['clientid']          .
                        ($this->settings['oid']       ?? NULL).
                         $this->settings['amount']            .
                         $this->settings['okUrl']             .
                         $this->settings['failUrl']           .
                         $this->settings['islemtipi']         .
                        ($this->settings['taksit']    ?? NULL).
                         $this->settings['rnd']               .
                         $this->settings['storeKey']          ;

        return $this->signatureEncoder($signature);
    }

    /**
     * Protected default
     */
    protected function default()
    {
        $this->settings['storetype'] = '3d_pay';
        $this->settings['islemtipi'] = $this->settings['islemtipi'] ?? 'Auth';
        $this->settings['currency']  = $this->settings['currency']  ?? 949;
        $this->settings['rnd']       = $this->settings['rnd']       ?? time();
        $this->settings['hash']      = $this->signature();
    }
}