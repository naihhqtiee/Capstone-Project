<?php

namespace App\Models;

use CodeIgniter\Model;

class AppointmentModel extends Model
{
    protected $table = 'appointments';
    protected $primaryKey = 'id';

protected $allowedFields = [
    'fullname',
    'email',
    'appointment_date',
    'appointment_time',
    'purpose',
    'status',
    'rejection_reason'
];


    public $timestamps = false;
}
