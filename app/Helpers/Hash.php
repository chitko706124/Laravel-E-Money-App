<?php

use Hashids\Hashids;

function idToHash($id)
{
    $hashids = new Hashids('magicpay123!@#', 6);
    return $hashids->encode($id);
}

function HashToId($hash)
{
    $hashids = new Hashids('magicpay123!@#', 6);
    return $hashids->decode($hash)[0];
}
