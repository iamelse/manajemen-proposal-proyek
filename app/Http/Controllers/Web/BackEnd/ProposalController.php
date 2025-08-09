<?php

namespace App\Http\Controllers\Web\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Enums\PermissionEnum;
use App\Http\Requests\Web\Proposal\StoreProposalRequest;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProposalController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        Gate::authorize(PermissionEnum::READ_PROPOSAL->value);

        $users = User::select('id', 'name')->orderBy('name')->get();
        $allowedFilterFields = ['title', 'description'];
        $allowedSortFields = ['title', 'submitted_at', 'is_approved', 'created_at', 'updated_at'];
        $limits = [10, 25, 50, 100];

        $proposals = Proposal::with('owner')
            ->search(
                keyword: $request->keyword,
                columns: $allowedFilterFields
            )->sort(
                sort_by: $request->sort_by ?? 'submitted_at',
                sort_order: $request->sort_order ?? 'DESC'
            )->when($request->user_id, fn($query, $user_id) =>
                $query->where('user_id', $user_id)
            )->when($request->is_approved !== null, fn($query) =>
                $query->where('is_approved', (bool) $request->is_approved)
            )->paginate($request->query('limit') ?? 10);

        return view('pages.proposal.index', [
            'title' => 'Proposal',
            'users' => $users,
            'proposals' => $proposals,
            'allowedFilterFields' => $allowedFilterFields,
            'allowedSortFields' => $allowedSortFields,
            'limits' => $limits
        ]);
    }

    public function create(): View
    {
        Gate::authorize(PermissionEnum::CREATE_PROPOSAL->value);

        return view('pages.proposal.create', [
            'title' => 'New Proposal',
        ]);
    }

    public function store(StoreProposalRequest $request): RedirectResponse
    {
        Gate::authorize(PermissionEnum::CREATE_PROPOSAL->value);

        try {
            $proposal = DB::transaction(function () use ($request) {
                return Proposal::create([
                    'user_id'      => Auth::id(),
                    'title'        => $request->title,
                    'description'  => $request->description,
                    'submitted_at' => now(),
                    'is_approved'  => false,
                    'meta_data'    => $request->meta_data ?? [],
                ]);
            });

            return redirect()
                ->route('be.proposals.edit', $proposal->id)
                ->with('success', 'Proposal created successfully. Please complete the details.');
        } catch (AuthorizationException $authorizationException) {
            Log::error($authorizationException->getMessage());

            abort(403, 'This action is unauthorized.');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return redirect()
                ->route('be.proposals.create')
                ->with('error', $exception->getMessage());
        }
    }

    public function edit(string $id): View
    {
        Gate::authorize(PermissionEnum::UPDATE_PROPOSAL->value);

        $proposal = Proposal::findOrFail($id);

        $usedUserIds = $proposal->teamMembers()->pluck('user_id')->toArray();

        $users = User::orderBy('name')
            ->where('id', '!=', $proposal->user_id)  // exclude owner
            ->whereNotIn('id', $usedUserIds)          // exclude users yg sudah jadi team member
            ->get();

        return view('pages.proposal.edit', [
            'title' => 'Edit Proposal',
            'proposal' => $proposal->load('teamMembers'),
            'users' => $users
        ]);
    }

    public function update(StoreProposalRequest $request, string $id): RedirectResponse
    {
        Gate::authorize(PermissionEnum::UPDATE_PROPOSAL->value);

        $proposal = Proposal::findOrFail($id);

        try {
            DB::transaction(function () use ($request, $proposal) {
                $proposal->update([
                    'title' => $request->title,
                    'description' => $request->description,
                    'is_approved' => $request->is_approved == 1 ? true : false,
                ]);
            });

            return redirect()->route('be.proposals.edit', $proposal->id)
                ->with('success', 'Proposal updated successfully.');
        } catch (AuthorizationException $authorizationException) {
            Log::error($authorizationException->getMessage());

            abort(403, 'This action is unauthorized.');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return redirect()->route('be.proposals.edit', $proposal->id)
                ->with('error', $exception->getMessage());
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            Gate::authorize(PermissionEnum::DELETE_PROPOSAL->value);

            $proposal = Proposal::findOrFail($id);
            $proposal->delete();

            return redirect()
                ->route('be.proposals.index')
                ->with('success', 'Proposal deleted successfully.');
        } catch (AuthorizationException $authorizationException) {
            Log::error($authorizationException->getMessage());

            abort(403, 'This action is unauthorized.');
        } catch (Exception $e) {
            Log::error("Error deleting proposal (ID: {$id}): " . $e->getMessage());

            return redirect()
                ->route('be.proposals.index')
                ->with('error', 'An error occurred while deleting the proposal.');
        }
    }

    public function massDestroy(Request $request): RedirectResponse
    {
        try {
            Gate::authorize(PermissionEnum::DELETE_PROPOSAL->value);

            $proposalIds = explode(',', $request->input('ids', ''));

            if (!empty($proposalIds)) {
                Proposal::whereIn('id', $proposalIds)->delete();
            }

            return redirect()
                ->route('be.proposals.index')
                ->with('success', 'Proposals deleted successfully.');
        } catch (AuthorizationException $authorizationException) {
            Log::error($authorizationException->getMessage());
            abort(403, 'This action is unauthorized.');
        } catch (Exception $e) {
            Log::error('Error deleting proposals: ' . $e->getMessage());

            return redirect()
                ->route('be.proposals.index')
                ->with('error', 'An error occurred while deleting the proposals.');
        }
    }
}