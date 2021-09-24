<?php
/*
 * This file is part of the Nigatedev framework package.
 *
 * (c) Abass Ben Cheik <abass@todaysdev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Nigatedev\Framework\Console\Maker;

use Nigatedev\Framework\Console\Colors;

/**
* Abstract maker
*
* @author Abass Ben Cheik <abass@todaysdev.com>
*/
abstract class AbstractMaker
{
    /**
    * @var array
    */
    protected $commands = [];

    /**
    * @var array
    */
    protected $config = [];
    
    protected const  VALID_FIELDS = [
        "string",
        "integer",
        "datatime",
        "bool",
        "float"
    ];
     
     /**
      * @var const
      */
    protected const DSP = DIRECTORY_SEPARATOR;
    
    /**
    * Constructor
    *
    * @param array $commands
    * @param array $config
    *
    * @return void
    */
    public function __construct($commands, $config)
    {
        $this->commands = $commands;
        $this->config = $config;

    }

    /**
     * @return string
     */
    public function getDir() 
    {
        $docRoot = dirname(__DIR__, 4).$this->config['dir'];
        return  str_replace("../", "/", $docRoot);
    }

    /**
     * @return string
     */
    public function getRoot() 
    {
        return dirname(__DIR__, 4);
    }
    
    /**
     * @return string
     */
    public function getCacheDir() 
    {
        return $this->getRoot()."/var/cache/dev";
    }
    
    /**
     * @var string $model
     * 
     * @return string
     */
    public function getModel($model) 
    {
      $template = __DIR__.self::DSP."Models".self::DSP.$model."Model.php";
      
      if (file_exists($template)) {
         return file_get_contents($template);
      } else {
          die(Colors::danger("Model not found"));
      }
      
    }
    
    /**
     * @var string $type
     * 
     * @return string
     */
    public function getField(string $type)
    {
        $file = __DIR__.self::DSP."Entity".self::DSP."fields".self::DSP.$type."Type.txt";
        return file_get_contents($file);
    }
    
    /**
     * @var string $model
     * 
     * @return string
     */
    public function replaceModel($model, $file) 
    {
        $toReplace = str_replace(
            ["model", "setModel", "getModel"],
            [$model, "set".ucfirst($model), "get".ucfirst($model)], 
            $file
        );
        
        return $toReplace;
    }
}