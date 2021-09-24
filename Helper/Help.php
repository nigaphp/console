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

namespace Nigatedev\Framework\Console\Helper;

use Nigatedev\Framework\Console\Colors;

/**
* Command helper
*
* @author Abass Ben Cheik <abass@todaysdev.com>
*/
class Help
{
    /**
     * @param array $commands
     * @param array $config
     *
     * @return mixed
     */
    public function __construct($commands, $config)
    {
        if ($commands === "default") {
            $this->defaultHelp();
            exit(1);
        }
    }
    
    /**
     * Command line helper
     *
     * @return void
     */
    public function defaultHelp(): void
    {
        echo Colors::info("\n------ Nigatedev Console(CLIA) ------\n\n");
        echo Colors::info(" m:c or make:controller"). "                Create a controller\n";
        echo Colors::info(" --help or -h"). "                          Show this help\n\n";
    }
}
