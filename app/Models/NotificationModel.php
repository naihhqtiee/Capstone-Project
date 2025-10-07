<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table      = 'notifications';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'type',
        'message',
        'is_read',
        'created_at'
    ];

    // Enable automatic timestamps if you want CI to handle created_at
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // no updated_at column
}
