<?php

namespace Application\Library;

class Application
{

    const ROOT_DIRECTORY = "RMSForSECHEO/";
    const CURRENCY_IN_WORD = "Rupees";
    const DATE_FORMAT = "d-M-Y";
    const DATE_TIME_FORMAT_FOR_DB = "Y-m-d H:i:s";
//    const TIME_FORMAT_FOR_DISPLAY = "Y-m-d G:iA";
    const TIME_FORMAT_FOR_DISPLAY = "H:i:s";
    const DATE_FORMAT_FOR_DB = "Y-m-d";
    const DATE_FORMAT_FOR_DISPLAY = "d-M-Y";
    const DATE_TIME_ZONE = "Asia/Karachi";
    const LOCAL_RATE = 1;
    const COMPANY_NAME = 'Housing Society Company';
    const CURL_USER_ROLE = 'Reportor';
    const CURL_USERNAME = 'curl';
    const CURL_PASS = 'curl@1234RMS';
//    const WK_BIN = '/usr/bin/wkhtmltopdf-amd64';
    const WK_BIN = 'wkhtmltopdf'; // for window after seting environment path

    public static function makeDateObjectForDB($dateStr)
    {
        $DateObj = null;
        if ($dateStr != "")
        {
            $DateObj = new \DateTime($dateStr);
            $DateObj->format(\Application\Library\Application::DATE_FORMAT_FOR_DB);
            $DateObj->setTimezone(new \DateTimeZone(\Application\Library\Application::DATE_TIME_ZONE));
            $DateObj->setTime(0, 0, 0);
        }
        return $DateObj;
    }

    public static function makeDateTimeObjectForDB($dateStr)
    {
        $DateObj = null;
        if ($dateStr != "")
        {
            $DateObj = new \DateTime($dateStr, new \DateTimeZone(\Application\Library\Application::DATE_TIME_ZONE));
            $DateObj->format(\Application\Library\Application::DATE_TIME_FORMAT_FOR_DB);
//            $DateObj->setTimezone(new \DateTimeZone(\Application\Library\Application::DATE_TIME_ZONE));
        }
        return $DateObj;
    }
    
    public static function makeDateTimeObjectForDisplay($dateStr)
    {
        $DateObj = null;
        if ($dateStr != "")
        {
            $DateObj = new \DateTime($dateStr, new \DateTimeZone(self::DATE_TIME_ZONE));
            $DateObj->format(self::DATE_FORMAT_FOR_DISPLAY);
//            $DateObj->setTimezone(new \DateTimeZone(\Application\Library\Application::DATE_TIME_ZONE));
        }
        return $DateObj;
    }

    /**
     * Send a POST requst using cURL
     * @param string $url to request
     * @param array $post values to send
     * @param array $options for cURL
     * @return string
     */
    public static function curlPost($url, array $post = NULL, array $options = array())
    {
        $defaults = array(
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 4,
            CURLOPT_POSTFIELDS => http_build_query($post)
        );

        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        if (!$result = curl_exec($ch))
        {
//            trigger_error(curl_error($ch));
            echo 'i am here' . $result;
        }
        curl_close($ch);
        return $result;
    }

    /**
     * Send a GET requst using cURL
     * @param string $url to request
     * @param array $get values to send
     * @param array $options for cURL
     * @return string
     */
    public static function curlGet($url, array $get = NULL, array $options = array())
    {
        $defaults = array(
            CURLOPT_URL => $url . (strpos($url, '?') === FALSE ? '?' : '') . http_build_query($get),
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 4
        );

        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        if (!$result = curl_exec($ch))
        {
            trigger_error(curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    public static function convertNumberInWord($number)
    {
        if (($number < 0) || ($number > 999999999))
        {
            throw new Exception("Number is out of range");
        }

        $Gn = floor($number / 100000);  /* Millions (giga) */
        $number -= $Gn * 100000;
        $kn = floor($number / 1000);     /* Thousands (kilo) */
        $number -= $kn * 1000;
        $Hn = floor($number / 100);      /* Hundreds (hecto) */
        $number -= $Hn * 100;
        $Dn = floor($number / 10);       /* Tens (deca) */
        $n = $number % 10;               /* Ones */

        $res = "";

        if ($Gn)
        {
            $res .= self::convertNumberInWord($Gn) . " Lacs";
        }

        if ($kn)
        {
            $res .= (empty($res) ? "" : " ") .
                    self::convertNumberInWord($kn) . " Thousand";
        }

        if ($Hn)
        {
            $res .= (empty($res) ? "" : " ") .
                    self::convertNumberInWord($Hn) . " Hundred";
        }

        $ones = array("", "One", "Two", "Three", "Four", "Five", "Six",
            "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
            "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen",
            "Nineteen");
        $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty",
            "Seventy", "Eigthy", "Ninety");

        if ($Dn || $n)
        {
            if (!empty($res))
            {
                $res .= " and ";
            }

            if ($Dn < 2)
            {
                $res .= $ones[$Dn * 10 + $n];
            }
            else
            {
                $res .= $tens[$Dn];

                if ($n)
                {
                    $res .= "-" . $ones[$n];
                }
            }
        }

        if (empty($res))
        {
            $res = "zero";
        }

        return $res;
    }

    public static function parseFormErrorMessages(array $errorMessages)
    {
        $errorsList = '';
        if (count($errorMessages))
        {
            $errorsList = '<ul class="error">';
            foreach ($errorMessages as $key => $value)
            {
                $keyModified = str_replace('accept_tcpp', '', $key);
                foreach ($value as $k => $val)
                {
                    $val = str_replace('The input', 'input', $val);

                    switch ($k)
                    {
                        case 'isEmpty':
                            $errorsList .= '<li><label for="' . $key . '">' . ucfirst(str_replace('_', ' ', $keyModified)) . ' ' . $val . '</label></li>';
                            break;
                        case 'notDigits':
                            $errorsList .= '<li><label for="' . $key . '">' . ucfirst(str_replace('_', ' ', $keyModified)) . ' ' . $val . '</label></li>';
                            break;
                        case 'notFloat':
                        case 'objectFound':
                        default :
                            $errorsList .= '<li><label for="' . $key . '">' . ucfirst(str_replace('_', ' ', $keyModified)) . ' ' . $val . '</label></li>';
                            break;
                    }
//                    $errorsList .= '<li><label for="'.$key.'">' .  $val . '</label></li>';
                }
            }
            $errorsList .='</ul>';
        }
        return $errorsList;
    }
    
    public static function showAmountWithDrCr($amount)
    {
        return number_format(abs($amount)) . (($amount > 0) ? ' Dr' : ' Cr');
    }
}