<?php

namespace Adminetic\Website\Repositories;

use Adminetic\Website\Contracts\TeamRepositoryInterface;
use Adminetic\Website\Http\Requests\TeamRequest;
use Adminetic\Website\Models\Admin\Team;
use Illuminate\Support\Facades\Cache;

class TeamRepository implements TeamRepositoryInterface
{
    // Team Index
    public function indexTeam()
    {
        $teams = config('adminetic.caching', true)
            ? (Cache::has('teams') ? Cache::get('teams') : Cache::rememberForever('teams', function () {
                return Team::orderBy('position')->get();
            }))
            : Team::orderBy('position')->get();

        return compact('teams');
    }

    // Team Create
    public function createTeam()
    {
        //
    }

    // Team Store
    public function storeTeam(TeamRequest $request)
    {
        $team = Team::create($request->validated());
        $this->uploadImage($team);
    }

    // Team Show
    public function showTeam(Team $team)
    {
        return compact('team');
    }

    // Team Edit
    public function editTeam(Team $team)
    {
        return compact('team');
    }

    // Team Update
    public function updateTeam(TeamRequest $request, Team $team)
    {
        $team->update($request->validated());
        $this->uploadImage($team);
    }

    // Team Destroy
    public function destroyTeam(Team $team)
    {
        $team->delete();
    }

    // Upload Image
    private function uploadImage(Team $team)
    {
        if (request()->has('image')) {
            $team
                ->addFromMediaLibraryRequest(request()->image)
                ->toMediaCollection('image');
        }
    }
}
