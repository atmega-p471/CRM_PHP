<?php

namespace App\Models;

use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Interfaces\WalletFloat;
use Bavix\Wallet\Traits\HasWalletFloat;
use Illuminate\Database\Eloquent\Model;

class Treasury extends Model implements Wallet, WalletFloat
{
    use HasWalletFloat;

    protected $fillable = ['name'];
}
