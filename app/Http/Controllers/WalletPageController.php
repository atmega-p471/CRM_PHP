<?php

namespace App\Http\Controllers;

use App\Models\Treasury;
use Illuminate\Http\Request;

class WalletPageController extends Controller
{
    public function index()
    {
        $treasury = Treasury::query()->firstOrCreate(['name' => 'main']);

        $transactions = $treasury->transactions()
            ->orderByDesc('id')
            ->paginate(25);

        return view('wallet.index', [
            'treasury' => $treasury,
            'transactions' => $transactions,
        ]);
    }

    public function deposit(Request $request)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'comment' => ['nullable', 'string', 'max:500'],
        ]);

        $treasury = Treasury::query()->firstOrCreate(['name' => 'main']);
        $treasury->depositFloat($data['amount'], [
            'comment' => $data['comment'] ?? 'Приход',
        ]);

        return redirect()->route('wallet.index')->with('ok', 'Сумма внесена.');
    }

    public function withdraw(Request $request)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'comment' => ['nullable', 'string', 'max:500'],
        ]);

        $treasury = Treasury::query()->firstOrCreate(['name' => 'main']);
        $treasury->withdrawFloat($data['amount'], [
            'comment' => $data['comment'] ?? 'Расход',
        ]);

        return redirect()->route('wallet.index')->with('ok', 'Сумма снята.');
    }
}
