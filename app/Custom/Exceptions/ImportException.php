<?php


namespace App\Custom\Exceptions;


class ImportException extends \Error
{

    /**
     * @var array
     */
    public $raw = [];

    /**
     * GatewayErrorException constructor.
     *
     * @param string $message
     * @param int $code
     * @param array $raw
     */
    public function __construct($message, $code, $raw = '')
    {
        parent::__construct($message, intval($code));
        $this->raw = $raw;
    }


    /**
     *
     * @return array
     */
    public function getRaw()
    {
        return $this->raw;
    }


}
