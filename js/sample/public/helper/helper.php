<?php

function getJquery()
{
    return 'jquery-1.11.3.js';
}

function getMainJs()
{
    $baseName = basename($_SERVER['SCRIPT_FILENAME']);
    if ( !$baseName ) {
        return '';
    }
    return substr($baseName,0,4) . '.js';
}

