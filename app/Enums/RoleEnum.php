<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMINISTRATOR = 'Administrator';
    case MANAGER = 'Manager';
    case STAFF = 'Staff';

    public function permissions(): array
    {
        return match ($this) {
            self::ADMINISTRATOR => [
                PermissionEnum::READ_DASHBOARD,

                PermissionEnum::CREATE_USER,
                PermissionEnum::READ_USER,
                PermissionEnum::UPDATE_USER,
                PermissionEnum::DELETE_USER,

                PermissionEnum::CREATE_ROLE,
                PermissionEnum::READ_ROLE,
                PermissionEnum::UPDATE_ROLE,
                PermissionEnum::DELETE_ROLE,
                PermissionEnum::UPDATE_ROLE_PERMISSION,

                PermissionEnum::CREATE_PROPOSAL,
                PermissionEnum::READ_PROPOSAL,
                PermissionEnum::UPDATE_PROPOSAL,
                PermissionEnum::DELETE_PROPOSAL,
                PermissionEnum::APPROVE_PROPOSAL,

                PermissionEnum::CREATE_TEAM_MEMBER,
                PermissionEnum::READ_TEAM_MEMBER,
                PermissionEnum::UPDATE_TEAM_MEMBER,
                PermissionEnum::DELETE_TEAM_MEMBER,

                PermissionEnum::CREATE_ATTACHMENT,
                PermissionEnum::READ_ATTACHMENT,
                PermissionEnum::DELETE_ATTACHMENT,

                PermissionEnum::IMPORT_PROPOSAL,
                PermissionEnum::EXPORT_PROPOSAL,
            ],

            self::MANAGER => [
                PermissionEnum::READ_DASHBOARD,

                PermissionEnum::CREATE_PROPOSAL,
                PermissionEnum::READ_PROPOSAL,
                PermissionEnum::UPDATE_PROPOSAL,
                PermissionEnum::APPROVE_PROPOSAL,

                PermissionEnum::CREATE_TEAM_MEMBER,
                PermissionEnum::READ_TEAM_MEMBER,
                PermissionEnum::UPDATE_TEAM_MEMBER,
                PermissionEnum::DELETE_TEAM_MEMBER,

                PermissionEnum::CREATE_ATTACHMENT,
                PermissionEnum::READ_ATTACHMENT,
                PermissionEnum::DELETE_ATTACHMENT,

                PermissionEnum::IMPORT_PROPOSAL,
                PermissionEnum::EXPORT_PROPOSAL,
            ],

            self::STAFF => [
                PermissionEnum::READ_DASHBOARD,

                PermissionEnum::CREATE_PROPOSAL,
                PermissionEnum::READ_PROPOSAL,
                PermissionEnum::UPDATE_PROPOSAL,

                PermissionEnum::CREATE_TEAM_MEMBER,
                PermissionEnum::READ_TEAM_MEMBER,

                PermissionEnum::CREATE_ATTACHMENT,
                PermissionEnum::READ_ATTACHMENT,
            ],
        };
    }
}