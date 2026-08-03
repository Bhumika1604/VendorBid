<?php

/**
 * Auth Helper
 *
 * Small collection of convenience functions used across
 * controllers and views to check authentication state
 * and current user details without repeating session calls.
 */

if (! function_exists('isLoggedIn')) {
    /**
     * Determine whether any user (admin or contractor) is logged in.
     */
    function isLoggedIn(): bool
    {
        return (bool) session()->get('isLoggedIn');
    }
}

if (! function_exists('isAdmin')) {
    /**
     * Determine whether the logged in user is an Admin.
     */
    function isAdmin(): bool
    {
        return isLoggedIn() && session()->get('role') === 'admin';
    }
}

if (! function_exists('isContractor')) {
    /**
     * Determine whether the logged in user is a Contractor.
     */
    function isContractor(): bool
    {
        return isLoggedIn() && session()->get('role') === 'contractor';
    }
}

if (! function_exists('authUser')) {
    /**
     * Return an array with the currently logged in user's basic details.
     *
     * @return array<string, mixed>|null
     */
    function authUser(): ?array
    {
        if (! isLoggedIn()) {
            return null;
        }

        return [
            'id'    => session()->get('user_id'),
            'name'  => session()->get('name'),
            'email' => session()->get('email'),
            'role'  => session()->get('role'),
        ];
    }
}

if (! function_exists('authId')) {
    /**
     * Shortcut to fetch the currently logged in user's id.
     */
    function authId(): ?int
    {
        return session()->get('user_id');
    }
}
