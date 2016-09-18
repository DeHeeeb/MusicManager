<?php
/**
 * Created by PhpStorm.
 * User: Kifkof
 * Date: 15.09.2016
 * Time: 21:48
 */

namespace Gorm;


interface IDBProvider
{
    public function SaveObjects($objectsToSave);

    public function LoadObjects($objectName);
}