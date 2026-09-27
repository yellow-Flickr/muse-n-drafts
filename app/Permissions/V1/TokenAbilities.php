<?php

namespace App\Permissions\V1;

use App\Models\User;

class TokenAbilities
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public const CreatePost = 'posts:create';

    public const UpdatePost = 'posts:update';

    public const ReplacePost = 'posts:replace';

    public const DeletePost = 'posts:delete';

    public const CreateCategory = 'categories:create';

    public const UpdateCategory = 'categories:update';

    public const ReplaceCategory = 'categories:replace';

    public const DeleteCategory = 'categories:delete';

    public const CreateTag = 'tags:create';

    public const UpdateTag = 'tags:update';

    public const ReplaceTag = 'tags:replace';

    public const DeleteTag = 'tags:delete';

    public const CreateUser = 'users:create';

    public const UpdateUser = 'users:update';

    public const ReplaceUser = 'users:replace';

    public const DeleteUser = 'users:delete';

    public static function getAbilities(string $userRole)
    {
        switch ($userRole) {
            case 'writer':
                return [
                    self::CreatePost,
                    self::ReplacePost,
                    self::UpdatePost,
                    self::DeletePost,
                ];
            case 'editor':
                return [
                    self::UpdatePost,
                    self::ReplacePost,
                    self::DeletePost,
                    self::CreateTag,
                    self::ReplaceTag,
                    self::UpdateTag,
                    self::DeleteTag,
                    self::CreateCategory,
                    self::ReplaceCategory,
                    self::UpdateCategory,
                    self::DeleteCategory,
                ];
            case 'admin':
                return [
                    // self::CreatePost,
                    // self::UpdatePost,
                    // self::ReplacePost,
                    // self::DeletePost,
                    // self::CreateTag,
                    // self::ReplaceTag,
                    // self::UpdateTag,
                    // self::DeleteTag,
                    // self::CreateCategory,
                    // self::ReplaceCategory,
                    // self::UpdateCategory,
                    // self::DeleteCategory,
                    // self::CreateUser,
                    // self::ReplaceUser,
                    // self::UpdateUser,
                    // self::DeleteUser,
                    '*'
                ];

            default:
                return [];
        }
    }
}
