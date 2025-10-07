<?php

namespace App\Models;

use CodeIgniter\Model;

class AppointmentModel extends Model
{
    protected $table = 'appointments';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',            // 🔗 linked to accounts.id
        'fullname',
        'email',
        'appointment_date',
        'appointment_time',
        'purpose',
        'status',
        'is_read',
        'rejection_reason'
    ];

    public $timestamps = false;
}
