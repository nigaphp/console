<?php
/*
 * This file is part of the Nigatedev framework package.
 *
 * (c) Abass Ben Cheik <abass@todaysdev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nigatedev\Framework\Console;

use Nigatedev\Framework\Console\Exception\ConsoleException;

/**
 * Console Colors
 *
 * @author Abass Ben Cheik <abass@todaysdev.com>
 */
class Colors
{
    
   /**
    * Color with background
    *
    * @param string $message
    *
    * @return string
    */
    public static function temp($type, $subject = "", $message = "")
    {
        $temp = "";
        
        switch ($type) {
            case 'SUCCESS':
                $temp = "\033[42m{$type}\033[0m {$subject} {$message}";
                break;
            case 'INFO':
                $temp = "\033[46m{$type}\033[0m {$subject} {$message}";
                break;
            case 'WARNING':
                $temp = "\033[43m{$type}\033[0m {$subject} {$message}";
                break;
            case 'ERROR':
                $temp = "\033[41m{$type}\033[0m {$subject} {$message}";
                break;
          
            default:
                throw new ConsoleException(self::error("Invalid or undefined message type!"));
            break;
        }
        
        return $temp;
    }
   
   /**
    * Message type success color
    *
    * @param string $message
    *
    * @return string
    */
    public static function success($message)
    {
        return  "\033[32m$message\033[0m";
    }
   
   /**
    * Message type success background
    *
    * @param string $message
    *
    * @return string
    */
    public static function successTemp($message)
    {
        return  "\033[42mSUCCESS\033[0m " . self::success($message);
    }
   
   /**
    * Message type info color
    *
    * @param string $message
    *
    * @return string
    */
    public static function info($message, $title = null)
    {
        if ($title !== null) {
            return  "\033[36m$title\033[0m $message";
        }
        return  "\033[36m$message\033[0m";
    }
   
   /**
    * Message type info background
    *
    * @param string $message
    *
    * @return string
    */
    public static function infoTemp($message)
    {
        return  "\033[46mINFO\033[0m ". self::info($message);
    }
   
   /**
    * Message type warning color
    *
    * @param string $message
    *
    * @return string
    */
    public static function warring($message)
    {
        return  "\033[33m$message\033[0m";
    }
   
   /**
    * Message type warning background
    *
    * @param string $message
    *
    * @return string
    */
    public static function warningTemp($message)
    {
        return  "\033[43mWARNING\033[0m " . self::warring($message);
    }
   
   /**
    * Message type error color
    *
    * @param string $message
    *
    * @return string
    */
    public static function error($message)
    {
        return  "\033[31m$message\033[0m";
    }
    
   /**
    * Message type error background
    *
    * @param string $message
    *
    * @return string
    */
    public static function errorTemp($message)
    {
        return "\033[41mERROR\033[0m " . self::error($message);
    }
    
   /**
    * Message type danger color
    *
    * @param string $message
    *
    * @return string
    */
    public static function danger($message)
    {
        return self::error($message);
    }
}
