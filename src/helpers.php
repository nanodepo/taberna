<?php

if (!function_exists('taberna')) {
    /**
     * The helper helps to receive and send flash messages.
     *
     * @return stdClass
     */
    function taberna(): stdClass
    {
        return literal(...config('taberna'));
    }
}
