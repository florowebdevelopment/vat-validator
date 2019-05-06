<?php

namespace Florowebdevelopment\VatValidator\Vies;

use Florowebdevelopment\VatValidator\Vies\Exceptions\Timeout as ViesTimeoutException;
use SoapClient;
use SoapFault;

class Client extends SoapClient
{
    /**
     * Do Request.
     *
     * @param string $request The XML SOAP request.
     * @param string $location The URL to request.
     * @param string $action The SOAP action.
     * @param int $version The SOAP version.
     * @param int $one_way If one_way is set to 1, this method returns nothing. Use this where a response is not expected.
     */
    public function __doRequest($request, $location, $action, $version, $one_way = NULL)
    {
        $ch = curl_init($location);

        curl_setopt($ch, CURLOPT_VERBOSE, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: text/xml']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);

        $response = curl_exec($ch);

        $errno = curl_errno($ch);
        $error = curl_error($ch);

        if ($errno) {
            if (in_array($errno, [
                CURLE_OPERATION_TIMEDOUT,
                CURLE_OPERATION_TIMEOUTED
            ])) {
                throw new ViesTimeoutException($error);
            } else {
                throw new SoapFault('Client', $error);
            }
        }

        curl_close($ch);

        if (!$one_way) {
            return ($response);
        }
    }
}
