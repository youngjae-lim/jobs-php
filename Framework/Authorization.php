<?php

namespace Framework;

class Authorization
{
    /**
     * Check if current logged-in user owns a resource.
     *
     * @param  int  $resourceOwnerId
     * @return bool
     */
    public static function isOwner($resourceOwnerId)
    {
        $sessionUser = Session::get('user');

        if (is_null($sessionUser)) {
            return false;
        }

        if (! isset($sessionUser['id'])) {
            return false;
        }

        return $sessionUser['id'] === $resourceOwnerId;
    }
}
