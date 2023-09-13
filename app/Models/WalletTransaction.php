<?php

namespace App\Models;

use AjCastro\Searchable\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;
    use Searchable;

    protected $guarded = [];

    public const DEPOSIT = 1;
    public const WITHDRAW = 2;

    public const COMPLETED = 1;

    protected $casts = [];

    protected $searchable = [
        'columns' => [],
    ];
}
