<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = ['name', 'account_name', 'account_number', 'logo', 'logo_public_id', 'is_active'];
}
