<?php
/*
 * This file is part of the Niga framework package.
 * (c) Abass Dev <abass@todaysdev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Niga\Framework\Console\Maker\Entity;

use Niga\Framework\Console\Colors;
use Niga\Framework\Console\Maker\AbstractMaker;

/**
 * Entity maker
 *
 * @author Abass Dev <abass@todaysdev.com>
 */
class EntityMaker extends AbstractMaker
{
    /**
     * @var array[]
     */
    protected $commands = [];

    /**
     * @var array[]
     */
    protected $config = [];

    /**
     * Constructor
     *
     * @param array[] $commands
     * @param array[] $config
     *
     * @return void
     */
    public function __construct($commands, $config)
    {

        parent::__construct($commands, $config);
        $this->make($this->commands);
    }


    /**
     * @param array $commands
     *
     * @return mixed
     */
    public function make($commands)
    {
        $className = $commands[2] ?? readline(Colors::temp("INFO", "Entity name (e.g:", Colors::info('ProductEntity') . ") \n"));

        if ($this->isSafeClassName($className)) {
            return $this->makeEntity($className);
        }
    }

    /**
     * Check to see if the class name is safe
     *
     * @param string $className
     *
     * @return bool
     */
    public function isSafeClassName($className)
    {
        $className = trim($className);
        if (
            str_ends_with($className, "Entity") && !preg_match("/^\d/", $className)
        ) {
            return true;
        }
        echo Colors::danger("\nBad entity class name\n");
        return false;
    }

    /**
     * @param string $className
     *
     * @return mixed
     */
    public function makeEntity($className)
    {
        $entityModel = $this->getModel("Entity");
        $entityCacheDir = $this->getCacheDir();

        if (!is_dir($entityCacheDir)) {
            mkdir($entityCacheDir, 0777, true);
        }

        $entityCacheFile = $entityCacheDir . DIRECTORY_SEPARATOR . strtolower("cache" . $className);
        if (file_exists($entityCacheFile)) {
            if (readline(Colors::warring("Warring: your entity exist! overwrite (Y\N) \n")) === strtoupper("Y")) {
                unlink($entityCacheFile);
            } else {
                die(Colors::info("Abort ! \n"));
            }
        }
        $fields = [];

        while (true) {
            $newField = trim(readline(Colors::info("\nTape new field or <return> to stop\n⏩ ")));
            if (strlen($newField) < 2) {
                $entity = str_replace(["//{{EntityModelBody}}//", "ModelEntity", "model"], [file_get_contents($entityCacheFile), $className, str_replace("entity", "", strtolower($className))], $entityModel);
                file_put_contents($this->getDir() . "/" . ucfirst($className . ".php"), $entity);
                unlink($entityCacheFile);
                echo (Colors::success("\nDone ! \n\n"));
                break;
            } else {
                $fields["name"] = $newField;
            }


            $newField = trim(readline(Colors::info("Field type " . Colors::warring("(e.g: string, integer)\n⏩ "))));
            if (!in_array($newField, self::VALID_FIELDS)) {
                echo (Colors::danger("Invalid field type ! \n"));
                break;
            } else {
                $fields["type"] = $newField;
            }

            switch ($fields["type"]) {
                case 'string':
                    file_put_contents($entityCacheFile, $this->replaceByFieldName($fields["name"], "string"), FILE_APPEND);
                    break;
                case 'integer':
                    file_put_contents($entityCacheFile, $this->replaceByFieldName($fields["name"], "integer"), FILE_APPEND);
                    break;
                case 'bool':
                    file_put_contents($entityCacheFile, $this->replaceByFieldName($fields["name"], "bool"), FILE_APPEND);
                    break;
            }
        }
    }

    /**
     * @param string $fieldName
     * @param string $fieldType
     *
     * @return string
     */
    public function replaceByFieldName($fieldName, $fieldType)
    {
        return $this->replaceModel($fieldName, $this->getField($fieldType));
    }
}
