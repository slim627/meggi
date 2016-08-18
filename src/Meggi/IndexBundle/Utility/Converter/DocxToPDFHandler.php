<?php
/**
 * Created by PhpStorm.
 * User: archer
 * Date: 21.10.15
 * Time: 21.11
 */

namespace Meggi\IndexBundle\Utility\Converter;

use CloudConvert\Exceptions\ApiBadRequestException;
use CloudConvert\Exceptions\ApiConversionFailedException;
use CloudConvert\Exceptions\ApiTemporaryUnavailableException;
use CloudConvert\Api;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class DocxToPDFHandler implements ConverterHandlerInterface
{
    /**
     * @var string
     */
    protected $api_key;

    /**
     * @var Logger
     */
    protected $logger;

    public function __construct($api_key, Logger $logger)
    {
        $this->api_key = $api_key;
        $this->logger = $logger;
    }

    public function convert($file_path, $output_file_path = null)
    {
        if(!file_exists($file_path)){
            throw new FileNotFoundException();
        }

        $output_file_path = ($output_file_path !== null) ? $output_file_path : preg_replace('/\.docx/', '.pdf', $file_path);

        $api = new Api($this->api_key);
        try {

            $api->convert([
                'inputformat' => 'docx',
                'outputformat' => 'pdf',
                'input' => 'upload',
                'file' => fopen($file_path, 'r'),
            ])
                ->wait()
                ->download($output_file_path);

            return $output_file_path;

        } catch (ApiBadRequestException $e) {
            $this->logger->error("Something with your request is wrong: " . $e->getMessage());
        } catch (ApiConversionFailedException $e) {
            $this->logger->error("Conversion failed, maybe because of a broken input file: " . $e->getMessage());
        }  catch (ApiTemporaryUnavailableException $e) {
            $this->logger->error("API temporary unavailable: " . $e->getMessage());
            $this->logger->error("We should retry the conversion in " . $e->retryAfter . " seconds");
        } catch (\Exception $e) {
            // network problems, etc..
            $this->logger->error("Something else went wrong: " . $e->getMessage());
        }

        return false;
    }
}