<?php namespace Arcanedev\Support\Bases;

use Illuminate\Console\Command as IlluminateCommand;
use Symfony\Component\Console\Helper\TableSeparator;

/**
 * Class     Command
 *
 * @package  Arcanedev\Support\Laravel\Bases
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Command extends IlluminateCommand
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Execute the console command.
     */
    abstract public function handle();

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Create table separator
     *
     * @return \Symfony\Component\Console\Helper\TableSeparator
     */
    protected function tableSeparator()
    {
        return new TableSeparator;
    }

    /**
     * Display header.
     *
     * @param  string  $text
     *
     * @deprecated Use the frame($text) method instead.
     */
    protected function header($text)
    {
        $this->frame($text);
    }

    /**
     * Display frame the text info.
     *
     * @param  string  $text
     */
    protected function frame($text)
    {
        $line   = '+'.str_repeat('-', strlen($text) + 4).'+';
        $this->info($line);
        $this->info("|  $text  |");
        $this->info($line);
    }
}
