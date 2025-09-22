<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table      = 'events';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'event_name',
        'description',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'location',
        'audience',
        'file'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
