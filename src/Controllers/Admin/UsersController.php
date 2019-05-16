<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request): JsonResponse
    {
        $filters = json_decode($request->get('filters'));
        $perPage = $request->get('rowsPerPage');
        $users = User::withTrashed();

        if (!empty($filters->name)) {
            $name = strtoupper($filters->name);
            $users = $users->whereRaw("UPPER(name) LIKE '%{$name}%'");
        }
        if (!empty($filters->email)) {
            $email = strtoupper($filters->email);
            $users = $users->whereRaw("UPPER(email) LIKE '%{$email}%'");
        }

        $users = $users->orderBy('deleted_at')->orderBy('name')->latest()->paginate($perPage);
        return response()->json($users);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $roles = Role::select('id', 'name', 'label')->get();
        $roles = $roles->pluck('label', 'name');

        return view('admin.users.create', compact('roles'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate(
            $request,
            [
                'name' => 'required',
                'email' => 'required|string|max:255|email|unique:users',
                'password' => 'required',
                'roles' => 'required'
            ]
        );

        $data = $request->except('password');
        $data['password'] = bcrypt($request->password);
        $user = User::create($data);

        foreach ($request->roles as $role) {
            $user->assignRole($role);
        }

        return redirect('admin/users')->with('flash_message', __('Created with success'));
    }

    /**
     * @param $id
     * @return View
     */
    public function show($id): View
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * @param $id
     * @return View
     */
    public function edit($id): View
    {
        $roles = Role::select('id', 'name', 'label')->get();
        $roles = $roles->pluck('label', 'name');

        $user = User::with('roles')->select('id', 'name', 'email')->findOrFail($id);
        $user_roles = [];
        foreach ($user->roles as $role) {
            $user_roles[] = $role->name;
        }

        return view('admin.users.edit', compact('user', 'roles', 'user_roles'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate(
            $request,
            [
                'name' => 'required',
                'email' => 'required|string|max:255|email|unique:users,email,' . $id,
                'roles' => 'required'
            ]
        );

        $data = $request->except('password');
        if ($request->has('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user = User::findOrFail($id);
        $user->update($data);

        $user->roles()->detach();
        foreach ($request->roles as $role) {
            $user->assignRole($role);
        }

        return redirect('admin/users')->with('flash_message', __('Updated with success'));
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        User::destroy($id);
        return redirect('admin/users')->with('flash_message', __('Deleted with success'));
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function enableUser($id): RedirectResponse
    {
        User::withTrashed()->find($id)->restore();
        return redirect('admin/users')->with('flash_message', __('Retored with success'));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function autocomplete(Request $request): array
    {
        $nome = strtoupper($request->input('name'));
        if (strlen($nome) <= 1) {
            return [];
        }
        return User::whereRaw("UPPER(name) LIKE '%{$nome}%'")->get(['id', 'name'])->toArray();
    }

    /**
     * @param $id
     * @return array
     */
    public function info($id): array
    {
        return User::find($id)->toArray();
    }

    /**
     * @param $id
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function phoneCreate($id, Request $request): array
    {
        $this->validate($request, [
            'tipo' => 'required',
            'telefone' => 'required'
        ]);

        $data = $request->all();
        $data['user_id'] = $id;
        return Telefone::create($data)->toArray();
    }
    /**
     * @param $id
     * @param Request $request
     * @return array
     */
    public function phoneUpdate($id, Request $request): array
    {
        $data = $request->all();
        $telefone = Telefone::findOrFail($id);
        return ['status' => $telefone->update($data)];
    }
    /**
     * @param $id
     * @return int
     */
    public function phoneDestroy($id): int
    {
        return Telefone::destroy($id);
    }
}
