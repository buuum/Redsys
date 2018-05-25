<?php

namespace Buuum;

class Redsys
{

    /**
     * @var string
     */
    protected $_enviroment = 'https://sis-t.redsys.es:25443/sis/services/SerClsWSEntrada?wsdl';

    /**
     * @var string
     */
    protected $type = 'webservice';

    /**
     * @var array
     */
    protected $_parameters = [];

    /**
     * @var string
     */
    protected $key;

    /**
     * Redsys constructor.
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * Currency in ISO 4217
     * https://en.wikipedia.org/wiki/ISO_4217
     *
     * @param int $currency
     * @throws \Exception
     */
    public function setCurrency($currency = 978)
    {
        if (is_numeric($currency)) {
            $this->_parameters['DS_MERCHANT_CURRENCY'] = $currency;
        } else {
            throw new \Exception('Currency is not valid');
        }
    }

    /**
     * (required)
     *
     * @param string $value
     * @throws \Exception
     */
    public function setIdentifier($value = 'REQUIRED')
    {
        if (strlen(trim($value)) > 0) {
            $this->_parameters['DS_MERCHANT_IDENTIFIER'] = $value;
        } else {
            throw new \Exception('DS_MERCHANT_IDENTIFIER not valid');
        }
    }

    /**
     * Para Euros las dos últimas posiciones se consideran decimales. (required)
     *
     * @param $amount
     * @throws \Exception
     */
    public function setAmount($amount)
    {
        if ($amount >= 0) {
            $amount = $this->convertNumber($amount);
            $amount = intval(strval($amount * 100));
            $this->_parameters['DS_MERCHANT_AMOUNT'] = $amount;
        } else {
            throw new \Exception('DS_MERCHANT_AMOUNT not valid');
        }
    }

    /**
     * Código FUC asignado al comercio (required)
     *
     * @param $fuc
     * @throws \Exception
     */
    public function setMerchantcode($fuc)
    {
        if (strlen(trim($fuc)) > 0) {
            $this->_parameters['DS_MERCHANT_MERCHANTCODE'] = $fuc;
        } else {
            throw new \Exception('DS_MERCHANT_MERCHANTCODE not valid');
        }
    }

    /**
     * Payment type
     * [T = Pago con Tarjeta + iupay , R = Pago por Transferencia, D = Domiciliacion,
     * C = Sólo Tarjeta (mostrará sólo el formulario para datos de tarjeta)] por defecto es T
     *
     * @param string $method
     * @throws \Exception
     */
    public function setMethod($method = 'T')
    {
        if (strlen(trim($method)) > 0) {
            $this->_parameters['DS_MERCHANT_PAYMETHODS'] = trim($method);
        } else {
            throw new \Exception('DS_MERCHANT_PAYMETHODS not valid');
        }
    }

    /**
     * @param string $url
     */
    public function setNotification($url = "")
    {
        $this->_parameters['DS_MERCHANT_MERCHANTURL'] = $url;
    }

    /**
     * @param string $url
     */
    public function setUrlOk($url = "")
    {
        $this->_parameters['DS_MERCHANT_URLOK'] = $url;
    }

    /**
     * @param string $url
     */
    public function setUrlKo($url = "")
    {
        $this->_parameters['DS_MERCHANT_URLKO'] = $url;
    }

    /**
     * Set Trade name Trade name will be reflected in the ticket trade (Optional)
     *
     * @param string $tradename trade name
     * @throws \Exception
     */
    public function setTradeName($tradename = '')
    {
        if (strlen(trim($tradename)) > 0) {
            $this->_parameters['DS_MERCHANT_MERCHANTNAME'] = trim($tradename);
        } else {
            throw new \Exception('DS_MERCHANT_MERCHANTNAME not valid');
        }
    }

    /**
     * Set product description (optional)
     *
     * @param string $description
     * @throws \Exception
     */
    public function setProductDescription($description = '')
    {
        if (strlen(trim($description)) > 0) {
            $this->_parameters['DS_MERCHANT_PRODUCTDESCRIPTION'] = trim($description);
        } else {
            throw new \Exception('DS_MERCHANT_PRODUCTDESCRIPTION not valid');
        }
    }

    /**
     * Set name of the user making the purchase (required)
     *
     * @param string $titular name of the user (for example Alonso Cotos)
     * @throws \Exception
     */
    public function setTitular($titular = '')
    {
        if (strlen(trim($titular)) > 0) {
            $this->_parameters['DS_MERCHANT_TITULAR'] = trim($titular);
        } else {
            throw new \Exception('DS_MERCHANT_TITULAR not valid');
        }
    }

    /**
     *
     * Los 4 primeros dígitos deben ser numéricos, para los dígitos
     * restantes solo utilizar los siguientes caracteres ASCII (required)
     * Del 30 = 0 al 39 = 9
     * Del 65 = A al 90 = Z
     * Del 97 = a al 122 = z
     *
     * @param $order
     * @throws \Exception
     */
    public function setOrder($order)
    {
        if (strlen(trim($order)) > 0) {
            $this->_parameters['DS_MERCHANT_ORDER'] = $order;
        } else {
            throw new \Exception('DS_MERCHANT_ORDER not valid');
        }
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->_parameters['DS_MERCHANT_ORDER'];
    }

    /**
     * (required)
     * Para el comercio para indicar qué tipo de transacción es. Los posibles valores son:
     * A – Pago tradicional
     * 1 – Preautorización
     * O – Autorización en diferido
     *
     * @param mixed $transaction
     * @throws \Exception
     */
    public function setTransactiontype($transaction = 'A')
    {
        if (strlen(trim($transaction)) > 0) {
            $this->_parameters['DS_MERCHANT_TRANSACTIONTYPE'] = $transaction;
        } else {
            throw new \Exception('DS_MERCHANT_TRANSACTIONTYPE not valid');
        }
    }

    /**
     * (required)
     * Número de terminal que le asignará su banco. Tres se considera su longitud máxima
     *
     * @param int $terminal
     * @throws \Exception
     */
    public function setTerminal($terminal = 1)
    {
        if (intval($terminal) != 0) {
            $this->_parameters['DS_MERCHANT_TERMINAL'] = $terminal;
        } else {
            throw new \Exception('DS_MERCHANT_TERMINAL not valid');
        }
    }

    /**
     * (required)
     * Tarjeta. Su longitud depende del tipo de tarjeta.
     *
     * @param $pan
     * @throws \Exception
     */
    public function setPan($pan)
    {
        if (intval($pan) != 0) {
            $this->_parameters['DS_MERCHANT_PAN'] = $pan;
        } else {
            throw new \Exception('DS_MERCHANT_PAN not valid');
        }
    }

    /**
     * (required)
     * Caducidad de la tarjeta.
     * Su formato es AAMM, siendo AA los dos últimos dígitos del año y MM los dos dígitos del mes.
     *
     * @param $expirydate
     * @throws \Exception
     */
    public function setExpiryDate($expirydate)
    {
        if (strlen(trim($expirydate)) == 4) {
            $this->_parameters['DS_MERCHANT_EXPIRYDATE'] = $expirydate;
        } else {
            throw new \Exception('Expire date is not valid.');
        }
    }

    /**
     * (required)
     * Código CVV2 de la tarjeta.
     *
     * @param $cvv
     * @throws \Exception
     */
    public function setCVV($cvv)
    {
        if (intval($cvv) != 0) {
            $this->_parameters['DS_MERCHANT_CVV2'] = $cvv;
        } else {
            throw new \Exception('CVV is not valid.');
        }
    }

    /**
     * @param bool $flat
     * @throws \Exception
     */
    public function setMerchantDirectPayment($flat = false)
    {
        if (is_bool($flat)) {
            $this->_parameters['DS_MERCHANT_DIRECTPAYMENT '] = $flat;
        } else {
            throw new \Exception('DS_MERCHANT_DIRECTPAYMENT not valid');
        }
    }


    /**
     * @param string $type
     * @return array
     */
    public function firePayment($type = 'test')
    {

        $xml = $this->buildXML();
        $client = new \SoapClient($this->getEnviroment($type));
        $result = $client->trataPeticion(array('datoEntrada' => $xml));
        $response = $this->xmltoArray($result->trataPeticionReturn);
        return $this->checkResponse($response);
    }


    /**
     * @param string $xml
     * @return array
     */
    private function xmltoArray($xml)
    {
        $xml = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $response = json_decode($json, true);
        return $response;
    }

    /**
     *
     * 0000 a 0099 Transacción autorizada para pagos y preautorizaciones
     * 900 Transacción autorizada para devoluciones y confirmaciones
     * 400 Transacción autorizada para anulaciones
     *
     * @param $response
     * @return array
     */
    private function checkResponse($response)
    {

        if (!$this->validCode($response)) {
            return $this->getResponse($this->getErrorCode($response), $this->getErrorCodeData($response), true);
        }

        if (!$this->checkResponseSignature($response['OPERACION'])) {
            return $this->getResponse('SIS0041', $response['OPERACION'], true);
        }

        return $this->getResponse($response['CODIGO'], $response['OPERACION']);
    }

    private function getErrorCode($response){
        $code = $response['CODIGO'];

        if (!is_numeric($code)) {
            return $code;
        }

        return $response['OPERACION']['Ds_Response'];
    }

    private function getErrorCodeData($response){
        $code = $response['CODIGO'];

        if (!is_numeric($code)) {
            return $response['RECIBIDO']['REQUEST']['DATOSENTRADA'];
        }

        return $response['OPERACION'];
    }

    /**
     * @param $postData
     * @return array
     * @throws \Exception
     */
    public function checkPaymentResponse($postData)
    {
        if (isset($postData)) {
            $parameters = $postData["Ds_MerchantParameters"];
            $signatureReceived = $postData["Ds_Signature"];
            $decodec = json_decode($this->decodeParameters($parameters), true);
            $order = $decodec['Ds_Order'];
            $signature = $this->generateSignature($parameters, $order);
            $signature = strtr($signature, '+/', '-_');
            if ($signature === $signatureReceived) {
                return $this->getResponse(0, $decodec);
            } else {
                return $this->getResponse('SIS041', $decodec, true);
            }
        } else {
            throw new \Exception("Error: Redsys response empty");
        }
    }

    /**
     * @param $code
     * @param $response
     * @param bool $error
     * @return array
     */
    private function getResponse($code, $response, $error = false)
    {
        $response_default = [
            'error' => $error,
            'code'  => $code
        ];
        return array_merge($response_default, $response);
    }

    /**
     * @param $response
     * @return bool
     */
    private function checkResponseSignature($response)
    {
        $cadena = $response['Ds_Amount'] . $response['Ds_Order'] . $response['Ds_MerchantCode'] . $response['Ds_Currency'] .
            $response['Ds_Response'] . $response['Ds_TransactionType'] . $response['Ds_SecurePayment'];

        if ($this->generateSignature($cadena, $response['Ds_Order']) != $response['Ds_Signature']) {
            return false;
        }
        return true;
    }

    /**
     * @param $response
     * @return bool
     */
    private function validCode($response)
    {
        $code = $response['CODIGO'];

        if (!is_numeric($code)) {
            return false;
        }

        $code = $response['OPERACION']['Ds_Response'];

        if ($code >= 0 && $code < 100) {
            return true;
        }

        if ($code == 900 || $code == 400) {
            return true;
        }

        return false;
    }

    /**
     * @param $data
     * @param $key
     * @return string
     */
    private function hmac256($data, $key)
    {
        $sha256 = hash_hmac('sha256', $data, $key, true);
        return $sha256;
    }

    /**
     * @param $data
     * @param $key
     * @return string
     */
    private function encrypt_3DES($data, $key)
    {
        $iv = "\0\0\0\0\0\0\0\0";
        $data_padded = $data;
        if (strlen($data_padded) % 8) {
            $data_padded = str_pad($data_padded, strlen($data_padded) + 8 - strlen($data_padded) % 8, "\0");
        }
        $ciphertext = openssl_encrypt($data_padded, "DES-EDE3-CBC", $key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING, $iv);
        return $ciphertext;
    }

    /**
     * @param $data
     * @return string
     */
    private function encodeBase64($data)
    {
        $data = base64_encode($data);
        return $data;
    }

    /**
     * @param $data
     * @return string
     */
    private function decodeBase64($data)
    {
        $data = base64_decode($data);
        return $data;
    }

    /**
     * @param $price
     * @return string
     */
    private function convertNumber($price)
    {
        $number = number_format(str_replace(',', '.', $price), 2, '.', '');
        return $number;
    }

    /**
     * @param $enviroment
     * @param string $type
     * @return string
     * @throws \Exception
     */
    private function getEnviroment($enviroment, $type = 'webservice')
    {
        if (trim($enviroment) == 'live') {
            if ($type == 'webservice') {
                $_enviroment = 'https://sis.redsys.es/sis/services/SerClsWSEntrada?wsdl';
            } else {
                $_enviroment = 'https://sis.redsys.es/sis/realizarPago';
            }
        } elseif (trim($enviroment) == 'test') {
            if ($type == 'webservice') {
                $_enviroment = 'https://sis-t.redsys.es:25443/sis/services/SerClsWSEntrada?wsdl';
            } else {
                $_enviroment = 'https://sis-t.redsys.es:25443/sis/realizarPago';
            }

        } else {
            throw new \Exception('Enviroment not valid');
        }

        return $_enviroment;

    }

    /**
     * @return string
     */
    private function buildXML()
    {
        $datos = $this->getParameters();
        $xml = '<REQUEST>';
        $xml .= $datos;
        $xml .= '<DS_SIGNATUREVERSION>HMAC_SHA256_V1</DS_SIGNATUREVERSION>';
        $xml .= '<DS_SIGNATURE>' . $this->generateSignature($datos, $this->getOrder()) . '</DS_SIGNATURE>';
        $xml .= '</REQUEST>';

        return $xml;
    }

    /**
     * @return string
     */
    private function getParameters()
    {
        $xml = '<DATOSENTRADA>';
        foreach ($this->_parameters as $key => $value) {
            $xml .= '<' . $key . '>' . $value . '</' . $key . '>';
        }
        $xml .= '</DATOSENTRADA>';
        return $xml;
    }

    /**
     * @param $datos
     * @param $order
     * @return string
     */
    public function generateSignature($datos, $order)
    {
        $key = $this->decodeBase64($this->key);
        // Get key with Order and key
        $key = $this->encrypt_3DES($order, $key);
        // Generated Hmac256 of Merchant Parameter
        $result = $this->hmac256($datos, $key);
        // Base64 encoding
        return $this->encodeBase64($result);
    }

    /**
     * @param string $type
     * @param array $options
     * @return string
     */
    public function createForm($type = 'test', $options = [])
    {

        $default_options = [
            'form_name' => 'formname',
            'submit_value' => 'Pay'
        ];

        $options = array_merge($default_options, $options);

        $environment = $this->getEnviroment($type, 'gateaway');
        $form = '
            <form action="' . $environment . '" method="post" name="'.$options['form_name'].'" >
                <input type="hidden" name="Ds_MerchantParameters" value="' . $this->generateMerchantParameters() . '"/>
                <input type="hidden" name="Ds_Signature" value="' . $this->generateSignature($this->generateMerchantParameters(),
                $this->getOrder()) . '"/>
                <input type="hidden" name="Ds_SignatureVersion" value="HMAC_SHA256_V1"/>
                <input type="submit" name="submitname" value="'.$options['submit_value'].'">
            </form>
        ';
        return $form;
    }

    /**
     * @return string
     */
    private function generateMerchantParameters()
    {
        $json = json_encode($this->_parameters);
        return $this->encodeBase64($json);
    }

    /**
     * @param $data
     * @return string
     */
    private function decodeParameters($data)
    {
        $decode = base64_decode(strtr($data, '-_', '+/'));
        return $decode;
    }
}