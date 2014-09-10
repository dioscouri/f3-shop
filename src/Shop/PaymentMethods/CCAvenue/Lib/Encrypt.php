<?php
namespace Shop\PaymentMethods\CCAvenue\Lib;

class Encrypt
{
    /**
     * 
     * @param unknown $plainText
     * @param unknown $key
     */
    public static function encrypt($plainText, $key)
    {
        $secretKey = static::hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $openMode = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
        $blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
        $plainPad = static::pkcs5_pad($plainText, $blockSize);
        if (mcrypt_generic_init($openMode, $secretKey, $initVector) != -1)
        {
            $encryptedText = mcrypt_generic($openMode, $plainPad);
            mcrypt_generic_deinit($openMode);
        }
        
        return bin2hex($encryptedText);
    }

    /**
     * 
     * @param unknown $encryptedText
     * @param unknown $key
     * @return unknown
     */
    public static function decrypt($encryptedText, $key)
    {
        $secretKey = static::hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $encryptedText = static::hextobin($encryptedText);
        $openMode = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
        mcrypt_generic_init($openMode, $secretKey, $initVector);
        $decryptedText = mdecrypt_generic($openMode, $encryptedText);
        $decryptedText = rtrim($decryptedText, "\0");
        mcrypt_generic_deinit($openMode);
        
        return $decryptedText;
    }
    
    /**
     * Padding Function
     * 
     * @param unknown $plainText
     * @param unknown $blockSize
     * @return string
     */
    public static function pkcs5_pad($plainText, $blockSize)
    {
        $pad = $blockSize - (strlen($plainText) % $blockSize);
        return $plainText . str_repeat(chr($pad), $pad);
    }
    
    /**
     * Hexadecimal to Binary function for php 4.0 version
     * 
     * @param unknown $hexString
     * @return Ambigous <string, unknown>
     */
    public static function hextobin($hexString)
    {
        $length = strlen($hexString);
        $binString = "";
        $count = 0;
        while ($count < $length)
        {
            $subString = substr($hexString, $count, 2);
            $packedString = pack("H*", $subString);
            if ($count == 0)
            {
                $binString = $packedString;
            }
            
            else
            {
                $binString .= $packedString;
            }
            
            $count += 2;
        }
        
        return $binString;
    }
}