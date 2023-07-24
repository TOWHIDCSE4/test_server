<?php

namespace App\Models;

use AjCastro\Searchable\Searchable;
use App\Helper\StatusContractDefineCode;
use App\Models\Base\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WalletTransaction extends BaseModel
{
    use HasFactory;
    use Searchable;

    protected $guarded = [];

    protected $casts = [];

    protected $searchable = [
        'columns' => [],
    ];

}
