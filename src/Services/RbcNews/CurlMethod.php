<?php

namespace App\Services\RbcNews;

use Symfony\Bundle\MakerBundle\Exception\RuntimeCommandException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Polyfill\Intl\Icu\Exception\RuntimeException;

class CurlMethod
{
    public function getHtmlByUrl(string $url): ?string
    {
        $agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36';
        $file = curl_init($url);

        curl_setopt($file, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($file, CURLOPT_HEADER, false);
        curl_setopt($file, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($file, CURLOPT_MAXREDIRS, 5);
        curl_setopt($file, CURLOPT_USERAGENT, $agent);

        try
        {
            $data = curl_exec($file);
            if (Response::HTTP_OK === $httpCode = curl_getinfo($file, CURLINFO_RESPONSE_CODE)) {
                return $data;
            }
            curl_close($file);
        } catch (RuntimeException $exception){
            throw new RuntimeException('Cannot connect to ' . $url);
        }

        throw new HttpException($httpCode);
    }
}