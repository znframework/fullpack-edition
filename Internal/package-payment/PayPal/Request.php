<?php namespace ZN\Payment\PayPal;

use ZN\Request\URL;
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
    public $types = 
    [
        'paypal'  => 'https://www.paypal.com/cgi-bin/webscr',
        'test'    => 'https://www.sandbox.paypal.com/cgi-bin/webscr'
    ];

    /**
     * Protected required
     * 
     * @var array
     */
    protected $required = 
    [
        'cmd'           => '_xclick', 
        'no_note'       => '1'
    ];

    /**
     * Protected $commands
     * 
     * @var array
     */
    protected $commands = 
    [
        'normal'            => '_xclick',
        'cart'              => '_cart',
        'subscribe'         => '_xclick-subscriptions',
        'autoBilling'       => '_xclick-auto-billing',
        'donate'            => '_donations',
        'encrypt'           => '_s-xclick'

    ];

    /**
     * Command
     * 
     * @param string $command
     * 
     * @return $this
     */
    public function command(string $command)
    {
        if( ! isset($this->commands[$command]) )
        {
            throw new Exception\InvalidCommandException(NULL, $command);
        }

        $this->settings['cmd'] = $this->commands[$command];

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
        # Is valid currency
        Currency::get($currency, $code);

        $this->settings['currency_code'] = $code;

        return $this;
    }

    /**
     * Sets return url.
     * 
     * @param string $notify
     * @param string $success
     * @param string $cancel  = NULL
     * 
     * @return $this
     */
    public function returnUrl(string $notify, string $success, string $cancel = NULL)
    {
        $this->settings['notify_url'] = URL::site($notify);

        $this->settings['return'] = URL::site($success);
       
        if( $cancel )
        {
            $this->settings['cancel_return'] = URL::site($cancel);
        }
        
        return $this;
    }

    /**
     * Sets item.
     * 
     * @param string $name
     * @param string $number = NULL
     * @param float  $amount = NULL
     * @param int    $quantity
     * 
     * @return $this
     */
    public function item(string $name, string $number = NULL, float $amount = NULL, int $quantity = NULL)
    {
        $this->settings['items'][] = ['item_name' => $name, 'item_number' => $number, 'amount' => $amount, 'quantity' => $quantity];

        return $this;
    }

    /**
     * Order Id
     * 
     * @param string $name
     * 
     * @return $this
     */
    public function orderId(string $name)
    {
        $this->settings['item_name'] = $name;

        return $this;
    }

    /**
     * Sets name.
     * 
     * @param string $first
     * @param string $last
     * 
     * @return $this
     */
    public function name(string $first, string $last)
    {
        $this->settings['first_name'] = $first;
        $this->settings['last_name']  = $last;

        return $this;
    }

    /**
     * Sets buyer.
     * 
     * @param string $email
     * @param string $id = NULL
     * 
     * @return $this
     */
    public function buyer(string $email, string $id = NULL)
    {
        $this->settings['payer_email'] = $email;
        
        if( $id )
        {
            $this->settings['payer_id'] = $id;
        }
       
        return $this;
    }

    /**
     * Sets seller.
     * 
     * @param string $email
     * @param string $id = NULL
     * 
     * @return $this
     */
    public function seller(string $email, string $id = NULL)
    {
        $this->settings['receiver_email'] = $email;

        if( $id )
        {
            $this->settings['receiver_id'] = $id;
        }
       
        return $this;
    }

    /**
     * Sets seller.
     * 
     * @param string $receiver
     * 
     * @return $this
     */
    public function clientId(string $clientId)
    {
        $this->settings['business'] = $clientId;
        
        return $this;
    }

    /**
     * Sets locale.
     * 
     * @param string $locale
     * 
     * @return $this
     */
    public function locale($locale)
    {
        $this->settings['lc'] = $locale;

        return $this;
    }

    /**
     * Send request.
     * 
     * @param string $type;
     */
    public function send(string $type = 'paypal')
    {
        if( ! isset($this->types[$type]) )
        {
            throw new Exception\InvalidSendTypeException(NULL, $type);
        }

        $this->setMultipleItems();

        $this->setCustomData($this->types[$type]);

        $settings = array_merge($this->required, $this->settings);

        $requestUrl = $this->types[$type] . '?' . http_build_query($settings);

        redirect($requestUrl);
    }

     /**
     * protected set multiple items
     */
    protected function setMultipleItems()
    {
        $items = $this->settings['items'] ?? NULL;

        if( ! empty($items) ) 
        {
            if( count($items) > 1 )
            {
                foreach( $items as $key => $values )
                {
                    $id = $key + 1;
                    
                    foreach( $values as $k => $i ) 
                        if( $i !== NULL ) 
                            $this->settings[$k . '_' . $id] = $values[$k];
                }
            }
            else
            {
                foreach( $items[0] as $key => $item ) 
                    if( $item !== NULL ) 
                        $this->settings[$key] = $item;
            }

            unset($this->settings['items']);
        }
    }

    /**
     * protected set custom data
     */
    protected function setCustomData($url)
    {
        $custom = $this->settings['custom'] ?? '';

        $this->settings['custom'] = $url . ',' . $custom;
    }
}