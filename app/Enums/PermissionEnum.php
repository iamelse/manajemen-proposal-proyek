<?php

namespace App\Enums;

enum PermissionEnum: string
{
    // Dashboard
    case READ_DASHBOARD = 'dashboard_read';

    // User Management
    case CREATE_USER = 'users_create';
    case READ_USER = 'users_read';
    case UPDATE_USER = 'users_update';
    case DELETE_USER = 'users_delete';

    // Role Management
    case CREATE_ROLE = 'roles_create';
    case READ_ROLE = 'roles_read';
    case UPDATE_ROLE = 'roles_update';
    case DELETE_ROLE = 'roles_delete';
    case UPDATE_ROLE_PERMISSION = 'roles_update_permission';

    // Proposal
    case CREATE_PROPOSAL = 'proposals_create';
    case READ_PROPOSAL = 'proposals_read';
    case UPDATE_PROPOSAL = 'proposals_update';
    case DELETE_PROPOSAL = 'proposals_delete';
    case APPROVE_PROPOSAL = 'proposals_approve';

    // Team Member
    case CREATE_TEAM_MEMBER = 'team_members_create';
    case READ_TEAM_MEMBER = 'team_members_read';
    case UPDATE_TEAM_MEMBER = 'team_members_update';
    case DELETE_TEAM_MEMBER = 'team_members_delete';

    // Attachment
    case CREATE_ATTACHMENT = 'attachments_create';
    case READ_ATTACHMENT = 'attachments_read';
    case DELETE_ATTACHMENT = 'attachments_delete';

    // Import & Export
    case IMPORT_PROPOSAL = 'proposals_import';
    case EXPORT_PROPOSAL = 'proposals_export';

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}