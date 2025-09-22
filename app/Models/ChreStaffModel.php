<?php

namespace App\Models;

use CodeIgniter\Model;

class ChreStaffModel extends Model
{
    protected $table = 'chre_staff'; // <-- make sure this matches your database table name
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'first_name',
        'last_name',
        'email',
        'contact_number',
        'department',
        'position',
        'status'
    ];

    // Optional: Automatically manage created_at & updated_at columns
    protected $useTimestamps = true;

    // If you want soft deletes (recommended)
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';
}
