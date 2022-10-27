<?php
/**
 * @author <akartis-dev>
 */

namespace App\Services\Application;


class ShellService
{
    /**
     * Execute command shell
     *
     * @param array $options
     */
    public static function executeShell(string $command, array $options)
    {
        $flags = "";
        foreach ($options as $option => $value) {
            if (!is_int($option)) {
                $flags .= sprintf(" %s", sprintf("%s", $option));
            }
            $flags .= sprintf(" %s", $value);

        }

        $commandToExecute = $command .  $flags;
        exec($commandToExecute, $result);

        return $result;
    }
}
