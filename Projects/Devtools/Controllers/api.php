<?php namespace Project\Controllers;

use Json;
use Method;
use Restful;

class Api extends Controller
{
    /**
     * Main
     */
    public function main()
    {
        if( Method::post('request') )
        {
            $type = Method::post('type');

            if( $data = Method::post('data') )
            {
                $explode = explode(',', $data);
                $newData = [];

                foreach( $explode as $value )
                {
                    $valueEx = explode(':', trim(str_replace(EOL, NULL, $value)));

                    if( isset($valueEx[1]) )
                    {
                        $newData[$valueEx[0]] = $valueEx[1];
                    }
                }

                Restful::data($newData);
            }

            if( $ssl = Method::post('sslVerifyPeer') )
            {
                Restful::sslVerifyPeer((bool) $ssl);
            }

            Masterpage::pdata(['results' => Restful::$type(Method::post('url'))]);

            $infos = Restful::info('all');

            Masterpage::pdata(['infos' => ! empty($infos) ? $infos : Restful::info()]);
        }

        Masterpage::page('rest-api');
    }
}
