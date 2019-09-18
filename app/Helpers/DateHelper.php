<?php

function feedDate($date)
{
    return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('jS \\of F Y');
}

