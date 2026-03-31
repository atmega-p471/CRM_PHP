<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

abstract class AbstractEntityController extends Controller
{
    abstract protected static function modelClass(): string;

    abstract protected static function entityKey(): string;

    abstract protected static function cfg(): array;

    abstract protected function validationRules(): array;

    protected static function indexWith(): array
    {
        return ['status'];
    }

    public function index()
    {
        $class = static::modelClass();

        return view('crud.index', [
            'entityKey' => static::entityKey(),
            'cfg' => static::cfg(),
            'items' => $class::query()->with(static::indexWith())->orderByDesc('id')->paginate(20),
        ]);
    }

    public function create()
    {
        $class = static::modelClass();

        return view('crud.form', [
            'entityKey' => static::entityKey(),
            'cfg' => static::cfg(),
            'item' => new $class,
        ]);
    }

    public function store(Request $request)
    {
        $class = static::modelClass();
        $data = $request->validate($this->validationRules());
        $class::query()->create($data);

        return redirect()->route(static::entityKey().'.index')->with('ok', 'Запись создана.');
    }

    public function show(string $id)
    {
        $class = static::modelClass();
        $item = $class::query()->with('status')->findOrFail($id);

        return view('crud.show', [
            'entityKey' => static::entityKey(),
            'cfg' => static::cfg(),
            'item' => $item,
        ]);
    }

    public function edit(string $id)
    {
        $class = static::modelClass();
        $item = $class::query()->findOrFail($id);

        return view('crud.form', [
            'entityKey' => static::entityKey(),
            'cfg' => static::cfg(),
            'item' => $item,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $class = static::modelClass();
        $item = $class::query()->findOrFail($id);
        $data = $request->validate($this->validationRules());
        $item->update($data);

        return redirect()->route(static::entityKey().'.index')->with('ok', 'Запись обновлена.');
    }

    public function destroy(string $id)
    {
        $class = static::modelClass();
        $class::query()->whereKey($id)->delete();

        return redirect()->route(static::entityKey().'.index')->with('ok', 'Запись удалена.');
    }

    protected function statusIdRule(): array
    {
        return [
            'required',
            Rule::exists('statuses', 'id')->where('entity_key', static::entityKey()),
        ];
    }
}
