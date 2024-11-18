<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function getCacheDir(): string
    {
        if($this->environment=="dev"){
            return '/var/log/dev/cache';

        }
        return dirname(__DIR__).'/var/'.$this->environment.'/cache';
    }

    public function getLogDir(): string
    {
        if($this->environment=="dev"){
            return '/var/log/dev/log';
        }
        return dirname(__DIR__).'/var/'.$this->environment.'/log';
    }
}
