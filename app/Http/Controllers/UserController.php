<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    protected function cfg(): array
    {
        return [
            'label' => 'Сотрудники',
            'index' => [
                'name' => 'Имя',
                'email' => 'Email',
                'role' => 'Роль',
                'status_id' => 'Статус',
            ],
            'fields' => [
                'name' => 'Имя',
                'email' => 'Email',
                'role' => 'Роль (manager, master, mechanic, painter)',
                'password' => 'Пароль',
                'status_id' => 'Статус',
            ],
        ];
    }

    public function index()
    {
        return view('crud.index', [
            'entityKey' => 'users',
            'cfg' => $this->cfg(),
            'items' => User::query()->with('status')->orderByDesc('id')->paginate(20),
        ]);
    }

    public function create()
    {
        return view('crud.form', [
            'entityKey' => 'users',
            'cfg' => $this->cfg(),
            'item' => new User,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'string', 'max:64'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'status_id' => [
                'required',
                Rule::exists('statuses', 'id')->where('entity_key', 'users'),
            ],
        ]);

        User::query()->create($data);

        return redirect()->route('users.index')->with('ok', 'Сотрудник создан.');
    }

    public function show(string $id)
    {
        $item = User::query()->with('status')->findOrFail($id);

        return view('crud.show', [
            'entityKey' => 'users',
            'cfg' => $this->cfg(),
            'item' => $item,
        ]);
    }

    public function edit(string $id)
    {
        $item = User::query()->findOrFail($id);

        return view('crud.form', [
            'entityKey' => 'users',
            'cfg' => $this->cfg(),
            'item' => $item,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $item = User::query()->findOrFail($id);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($item->id)],
            'role' => ['required', 'string', 'max:64'],
            'status_id' => [
                'required',
                Rule::exists('statuses', 'id')->where('entity_key', 'users'),
            ],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'string', 'min:6', 'confirmed'];
        }

        $data = $request->validate($rules);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $item->update($data);

        return redirect()->route('users.index')->with('ok', 'Данные обновлены.');
    }

    public function destroy(string $id)
    {
        if ((string) auth()->id() === $id) {
            return redirect()->route('users.index')->withErrors('Нельзя удалить свою учётную запись.');
        }

        User::query()->whereKey($id)->delete();

        return redirect()->route('users.index')->with('ok', 'Сотрудник удалён.');
    }
}
