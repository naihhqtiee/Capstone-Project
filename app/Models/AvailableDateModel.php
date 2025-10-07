<?php

namespace App\Models;

use CodeIgniter\Model;

class AvailableDateModel extends Model
{
    protected $table      = 'available_dates';
    protected $primaryKey = 'id';
    protected $allowedFields = ['date'];
}
