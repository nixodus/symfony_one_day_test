<?php


namespace GithubstatsBundle\Services;


/**
 * @param string $url
 * @param array|string $postFiels
 * @return ApiResponse
 * @throws \Exception
 */

class ApiCaller{


    function call($url, $postFiels=''){

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0');

        curl_setopt($ch, CURLOPT_URL, $url);

        if(!empty($postFiels)){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFiels);
        }

        $response = curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);



        if (!$response) {
            throw new CurlException('Unknown error while connecting via cURL', $responseCode);
        }

        $this->handleNon200Response($response, $responseCode);


        $apiResponse = new ApiResponse();
        $apiResponse->contents = json_decode($response,true);
        $apiResponse->code = $responseCode;
        return $apiResponse;

    }

    /**
     * @param mixed $response
     * @param int $responseCode
     */
    private function handleNon200Response($response, $responseCode)
    {
        if ($responseCode >= 200 && $responseCode < 300) {
            return;
        }
        $decodedResponse = json_decode($response, true);
        if (isset($decodedResponse['error'])) {
            $this->error = $decodedResponse['error'];
        }
        throw new CurlException(
            isset($decodedResponse['message']) ? $decodedResponse['message'] : $response, $responseCode
        );
    }



}