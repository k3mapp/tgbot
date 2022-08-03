<?php

function autoload($class) {
    require_once 'helpers/class/'.$class.'.php';
}

spl_autoload_register('autoload');