<?php

namespace App\Http\Controllers\Web\BackEnd;

use App\Enums\PermissionEnum;
use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\TeamMember;
use App\Models\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class TeamMemberController extends Controller
{
    public function store(Request $request, Proposal $proposal)
    {
        try {
            Gate::authorize(PermissionEnum::CREATE_TEAM_MEMBER->value);

            $validated = $request->validate([
                'user_id'   => 'required|array',
                'user_id.*' => 'exists:users,id',
            ]);

            foreach ($validated['user_id'] as $userId) {
                
                $user = User::find($userId);

                if ($user) {
                    $teamMember = TeamMember::firstOrCreate(
                        ['user_id' => $user->id],     // cari berdasarkan user_id
                        ['name' => $user->name]       // kalau belum ada, buat baru dengan nama user
                    );

                    $proposal->teamMembers()->syncWithoutDetaching([$teamMember->id]);
                }
            }

            return back()->with('success', 'Team members added successfully.');
        } catch (AuthorizationException $e) {
            Log::error($e->getMessage());
            abort(403, 'This action is unauthorized.');
        } catch (Exception $e) {
            Log::error('Error adding team members: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while adding team members.');
        }
    }

    public function destroy($id)
    {
        try {
            Gate::authorize(PermissionEnum::DELETE_TEAM_MEMBER->value);

            $member = TeamMember::findOrFail($id);
            $memberName = $member->name;

            $member->delete();

            return back()->with('success', "Team member '{$memberName}' removed successfully.");
        } catch (AuthorizationException $e) {
            Log::error($e->getMessage());
            abort(403, 'This action is unauthorized.');
        } catch (Exception $e) {
            Log::error("Error removing team member (ID: {$id}): " . $e->getMessage());
            return back()->with('error', 'An error occurred while removing the team member.');
        }
    }
}
