<?php

namespace App\Models;
use CodeIgniter\Model;

class AccountModel extends Model
{
    protected $table = 'accounts';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'full_name', 'email', 'password', 'role', 'token', 'status', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
}
