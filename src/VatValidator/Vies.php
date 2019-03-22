<?php

namespace Florowebdevelopment\VatValidator;

use SoapClient;
use stdClass;

class Vies
{
    protected $oSoapClient = null;
    protected $bValid = false;
    protected $sName = '';
    protected $sAddress = '';

    const WSDL_URL = 'http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl';

    /**
     * Construct.
     */
    public function __construct()
    {
        ini_set('default_socket_timeout', 15);

        $oSoapClient = new SoapClient(self::WSDL_URL, [
            'connection_timeout' => 15,
            'exceptions'         => true,
        ]);

        $this->setSoapClient($oSoapClient);
    }

    /**
     * Get Soap Client.
     *
     * @return SoapClient $this->oSoapClient;
     */
    private function getSoapClient(): SoapClient
    {
        return $this->oSoapClient;
    }

    /**
     * Set Soap Client.
     *
     * @param $oSoapClient
     */
    private function setSoapClient(SoapClient $oSoapClient): void
    {
        $this->oSoapClient = $oSoapClient;
    }

    /**
     * Get Name.
     *
     * @return string $this->sName;
     */
    public function getName(): string
    {
        return $this->sName;
    }

    /**
     * Set Name.
     *
     * @param string $sName
     */
    private function setName(string $sName): void
    {
        $this->sName = $sName;
    }

    /**
     * Get Address.
     *
     * @return string $this->sAddress;
     */
    public function getAddress(): string
    {
        return $this->sAddress;
    }

    /**
     * Set Address.
     *
     * @param string $sAddress
     */
    private function setAddress(string $sAddress): void
    {
        $this->sAddress = $sAddress;
    }

    /**
     * Get Valid.
     *
     * @return bool $this->bValid;
     */
    private function getValid(): bool
    {
        return $this->bValid;
    }

    /**
     * Set Valid.
     *
     * @param string $bValid
     */
    private function setValid(bool $bValid): void
    {
        $this->bValid = $bValid;
    }

    /**
     * Is Valid.
     *
     * @return bool $bValid
     */
    public function isValid(): bool
    {
        return $this->getValid();
    }

    /**
     * Validate.
     *
     * @param string $sVatNumber
     *
     * @return bool
     */
    public function validate(string $sVatNumber): bool
    {
        if (!$this->checkVat($sVatNumber)) {
            return false;
        }

        return true;
    }

    /**
     * Check Vat.
     *
     * @param string $sVatNumber
     *
     * @return bool
     */
    private function checkVat($sVatNumber): bool
    {
        $oSoapClient = $this->getSoapClient();

        $oResponse = $oSoapClient->checkVat([
            'countryCode' => substr($sVatNumber, 0, 2),
            'vatNumber'   => substr($sVatNumber, 2, strlen($sVatNumber) - 2),
        ]);

        if (!$oResponse->valid) {
            $this->setValid(false);

            return false;
        }

        $this->setValid(true);

        $this->checkVatResponse($oResponse);

        return true;
    }

    /**
     * Check Vat Response.
     *
     * @param stdClass $oResponse
     */
    private function checkVatResponse(stdClass $oResponse): void
    {
        $sName = (string) $oResponse->name;
        $this->setName($sName);

        $sAddress = (string) $oResponse->address;
        $this->setAddress($sAddress);
    }
}
