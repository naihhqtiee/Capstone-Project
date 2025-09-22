<?php

namespace App\Models;

use CodeIgniter\Model;

class EventRegistrationModel extends Model
{
    protected $table      = 'event_registrations';
    protected $primaryKey = 'id';
protected $allowedFields = [
    'event_id', 'user_id', 'full_name', 'email', 
    'contact_number', 'special_requirements', 'created_at'
];

}

