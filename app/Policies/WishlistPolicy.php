<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Wishlist;
use Illuminate\Auth\Access\HandlesAuthorization;

class WishlistPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Wishlist');
    }

    public function view(AuthUser $authUser, Wishlist $wishlist): bool
    {
        return $authUser->can('View:Wishlist');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Wishlist');
    }

    public function update(AuthUser $authUser, Wishlist $wishlist): bool
    {
        return $authUser->can('Update:Wishlist');
    }

    public function delete(AuthUser $authUser, Wishlist $wishlist): bool
    {
        return $authUser->can('Delete:Wishlist');
    }

    public function restore(AuthUser $authUser, Wishlist $wishlist): bool
    {
        return $authUser->can('Restore:Wishlist');
    }

    public function forceDelete(AuthUser $authUser, Wishlist $wishlist): bool
    {
        return $authUser->can('ForceDelete:Wishlist');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Wishlist');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Wishlist');
    }

    public function replicate(AuthUser $authUser, Wishlist $wishlist): bool
    {
        return $authUser->can('Replicate:Wishlist');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Wishlist');
    }

}