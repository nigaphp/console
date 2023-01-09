<?php
/*
 * This file is part of the Nigatedev framework package.
 *
 * (c) Abass Ben Cheik <abass@abassdev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Nigatedev\Framework\Console\Helper;

use Nigatedev\Framework\Console\Colors;

/**
 * Command helper
 *
 * @author Abass Ben Cheik <abass@abassdev.com>
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
        echo Colors::info("\n------ Nigatedev Console(CLI) ------\n\n");
        echo Colors::info(" --help or -h") . "                          Show this help\n";
        echo Colors::info(" m:c") . "                                   Create a controller\n";
        echo Colors::info(" make:controller") . "                       shortcut: Create a controller\n";
        echo Colors::info(" make:entity") . "                           Create new Entity\n";
        echo Colors::info(" m:e") . "                                   shortcut: Create new Entity\n";
        echo Colors::info(" run:dev") . "                               Start developement server on 8000 as default port\n";
    }
}
