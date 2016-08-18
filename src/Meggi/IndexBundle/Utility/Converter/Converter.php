<?php
/**
 * Created by PhpStorm.
 * User: archer
 * Date: 21.10.15
 * Time: 21.11
 */

namespace Meggi\IndexBundle\Utility\Converter;


class Converter
{
    protected $handler;

    public function __construct(ConverterHandlerInterface $handler)
    {
        $this->handler = $handler;
    }

    public function convert($file_path)
    {
        return $this->handler->convert($file_path);
    }
}