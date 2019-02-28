<?php
/**
 * Created by PhpStorm.
 * User: o.michaud
 * Date: 28/02/19
 * Time: 13:29
 */

namespace App\Infrastructure;


interface HttpClientInterface
{

    public function send($method,$url,$payload);
}
