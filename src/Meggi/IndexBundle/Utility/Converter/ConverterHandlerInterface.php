<?php
/**
 * Created by PhpStorm.
 * User: archer
 * Date: 21.10.15
 * Time: 21.08
 */

namespace Meggi\IndexBundle\Utility\Converter;


interface ConverterHandlerInterface
{
    public function convert($file_path, $output_file_path = null);
}