<?php
namespace Kuzmich\TaskBundle\Helper;
class Methods
{
    /**
     * @param $url
     * @return mixed
     */
    public static function getRequest($url)
    {
        if($curl = curl_init())
        {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($curl);
            curl_close($curl);
        }

        else
        {
            $result = null;
        }

        return json_decode($result, true);
    }

    /**
     * @param $url
     * @param $condition
     * @return mixed
     */
    public static function postRequest($url, $condition)
    {
        if($curl = curl_init())
        {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt ($curl, CURLOPT_HEADER, 0);
            curl_setopt ($curl, CURLOPT_POSTFIELDS, $condition);
            $result = curl_exec($curl);
            curl_close($curl);
        }

        else
        {
            $result = null;
        }

        return json_decode($result, true);
    }

}