<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'first_name',
        'mi',
        'last_name',
        'email',
        'contact_number',
        'department',
        'course',
        'year',
        'account_id'  // ADD THIS LINE
    ];
    
    // Optional: Add timestamps if your table has them
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}