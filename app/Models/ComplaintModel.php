<?php

namespace App\Models;

use CodeIgniter\Model;

class ComplaintModel extends Model
{
    protected $table = 'complaints';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'date',
        'time',
        'location',
        'complaint_type',
        'complaint_category',
        'description',
        'impact',
        'files',
        'resolution',
        'resolution_other',
        'is_anonymous',
        'status',
        'is_read',
        'admin_notes',
        'created_at',
        'updated_at'
    ];

    /**
     * ✅ Fetch identified complaints with student + account info
     */
    public function getIdentifiedComplaints()
    {
        return $this->select('
                complaints.*,
                accounts.full_name,
                accounts.email,
                students.contact_number
            ')
            ->join('accounts', 'accounts.id = complaints.user_id', 'left')
            ->join('students', 'students.account_id = accounts.id', 'left')
            ->where('complaints.is_anonymous', 0)
            ->orderBy('complaints.created_at', 'DESC')
            ->findAll();
    }

    /**
     * ✅ Fetch ALL complaints (anonymous + identified)
     * If complaint is anonymous, account/student data will just be NULL
     */
    public function getAllComplaintsWithUserData()
    {
        return $this->select('
                complaints.*,
                accounts.full_name,
                accounts.email,
                students.contact_number
            ')
            ->join('accounts', 'accounts.id = complaints.user_id', 'left')
            ->join('students', 'students.account_id = accounts.id', 'left')
            ->orderBy('complaints.created_at', 'DESC')
            ->findAll();
    }

    /**
     * ✅ Get all complaints submitted by a specific user
     */
    public function getComplaintsByUser($userId)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
}
