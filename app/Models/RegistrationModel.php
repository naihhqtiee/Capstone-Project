<?php
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model 
{
    protected $table = 'accounts';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'full_name',
        'email',
        'password',
        'role',
        'token',
        'status'
    ];
    
    protected $useTimestamps = false;
}