<?php

namespace App\Models;

use CodeIgniter\Model;

class NdaModel extends Model
{
    protected $table            = 'nda_uploads';   // ✅ Table name in your DB
    protected $primaryKey       = 'id';            // ✅ Primary key column

    protected $allowedFields    = [
        'account_id',   // ✅ link to accounts table
        'file_name',    // original file name
        'file_path',    // stored file path
        'uploaded_at'   // timestamp
    ];

    protected $useTimestamps = true; // auto-manages created_at & updated_at
}
