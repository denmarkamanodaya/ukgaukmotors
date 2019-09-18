<?php

Blade::extend(function($value)
{
    return preg_replace('/(\s*)@break(\s*)/', '$1<?php break; ?>$2', $value);
});