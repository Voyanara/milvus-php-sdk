<?php

namespace Voyanara\MilvusSdk\Facades;
use Illuminate\Support\Facades\Facade;
class Milvus extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Voyanara\MilvusSdk\Milvus::class;
    }
}