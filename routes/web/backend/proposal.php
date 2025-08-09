<?php

use App\Http\Controllers\Web\BackEnd\ProposalController;
use App\Http\Controllers\Web\BackEnd\TeamMemberController;
use Illuminate\Support\Facades\Route;

Route::prefix('proposals')->group(function () {
    Route::get('/', [ProposalController::class, 'index'])->name('be.proposals.index');
    Route::get('/create', [ProposalController::class, 'create'])->name('be.proposals.create');
    Route::post('/', [ProposalController::class, 'store'])->name('be.proposals.store');
    Route::get('/{id}/edit', [ProposalController::class, 'edit'])->name('be.proposals.edit');
    Route::put('/{id}', [ProposalController::class, 'update'])->name('be.proposals.update');
    Route::delete('/{id}', [ProposalController::class, 'destroy'])->name('be.proposals.destroy');
    Route::get('/mass/destroy', [ProposalController::class, 'massDestroy'])->name('be.proposals.mass.destroy');

    // Team Members management
    Route::post('/{proposal}/team-members', [TeamMemberController::class, 'store'])
        ->name('be.proposals.team-members.store');
    Route::delete('/team-members/{teamMember}', [TeamMemberController::class, 'destroy'])
        ->name('be.proposals.team-members.destroy');
});