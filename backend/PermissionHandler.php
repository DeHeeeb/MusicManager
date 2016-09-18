<?php

/**
 * Created by PhpStorm.
 * User: Ammonix
 * Date: 16.09.2016
 * Time: 16:38
 */
abstract class PermissionHandler
{
    public static function permission(User $user, int $role)
    {
        if ($user->getRole() >= $role) {
            return true;
        } else {
            return false;
        }
    }
}