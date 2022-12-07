<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Database\Query\Builder;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('read-user'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::select('id', 'name')
            // ->isActive()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->prepend('All', 'all');

        $genders = Tag::select('tag_slug', 'tag_name')
            ->where('tag_type', 'gender')
            ->isActive()
            ->orderBy('tag_name')
            ->pluck('tag_name', 'tag_slug')
            ->prepend('All', 'all');

        $inputSearchString = $request->input('s', '');
        $inputRole = $request->input('role', '');
        $inputGender = $request->input('gender', '');

        $selectedRoles = [];
        $selectedGender = [];
        $users = collect([]);

        //if(filled($inputSearchString) || filled($inputRole) || filled($inputGender)) {
            $selectedRoles = $inputRole ? explode(',', $inputRole) : [];
            $selectedGender = $inputRole ? explode(',', $inputRole) : [];

            $users = User::when($selectedRoles, function($query) use ($selectedRoles) {
                    if(!in_array('all', $selectedRoles) || !in_array(999999, $selectedRoles)) {
                        $query->whereIn('user_type', $selectedRoles);
                    }
                })
                ->when($selectedGender, function($query) use ($selectedGender) {
                    if(!in_array('all', $selectedGender) || !in_array(999999, $selectedGender)) {
                        $query->whereIn('gender', $selectedGender);
                    }
                })
                ->when($inputSearchString, function($query) use ($inputSearchString) {
                    $query->where(function($query) use ($inputSearchString) {
                        $query->orWhere('name', 'LIKE', '%'.$inputSearchString.'%')
                            ->orWhere('first_name', 'LIKE', '%'.$inputSearchString.'%')
                            ->orWhere('last_name', 'LIKE', '%'.$inputSearchString.'%')
                            ->orWhere('email', 'LIKE', '%'.$inputSearchString.'%')
                            ->orWhere('user_phone', 'LIKE', '%'.$inputSearchString.'%')
                            ->orWhere('user_name', 'LIKE', '%'.$inputSearchString.'%')
                            ->orWhere('user_type', 'LIKE', '%'.$inputSearchString.'%')
                            ->orWhere('birthday', 'LIKE', '%'.$inputSearchString.'%')
                            ->orWhere('gender', 'LIKE', '%'.$inputSearchString.'%');
                    });
                })
                // ->isActive()
                ->orderBy('name')
                ->paginate(config('app-config.datatable_default_row_count', 25))
                ->withQueryString();
        //}

        return view('users.index', [
            'users' => $users,
            'roles' => $roles,
            'genders' => $genders,
            'selectedRoles' => $selectedRoles,
            'selectedGender' => $selectedGender,
        ]);
    }

    /* public function index(Request $request)
    {
        abort_if(!auth()->user()->can('read-user'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $inputSearchString = $request->input('s', '');

        $users = User::when($inputSearchString, function($query) use ($inputSearchString) {
                $query->where(function($query) use ($inputSearchString) {
                    $query->orWhere('name', 'LIKE', '%'.$inputSearchString.'%')
                        ->orWhere('first_name', 'LIKE', '%'.$inputSearchString.'%')
                        ->orWhere('last_name', 'LIKE', '%'.$inputSearchString.'%')
                        ->orWhere('email', 'LIKE', '%'.$inputSearchString.'%')
                        ->orWhere('user_phone', 'LIKE', '%'.$inputSearchString.'%')
                        ->orWhere('user_name', 'LIKE', '%'.$inputSearchString.'%')
                        ->orWhere('birthday', 'LIKE', '%'.$inputSearchString.'%')
                        ->orWhere('gender', 'LIKE', '%'.$inputSearchString.'%');
                });
            })
            ->isActive()
            ->orderBy('name')
            ->paginate(config('app-config.datatable_default_row_count', 25))
            ->withQueryString();

        return view('users.index', [
            'users' => $users,
        ]);
    } */
}
