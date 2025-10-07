<?php

namespace App\Controllers;

use App\Models\AppointmentModel;
use App\Models\AccountModel;
use App\Models\AvailableDateModel;

class AppointmentController extends BaseController
{
    /**
     * Staff: Mark date as available (via manual form if needed)
     */
    public function setAvailableDate()
    {
        $date = $this->request->getPost('date');

        if (empty($date)) {
            return redirect()->back()->with('error', 'Please select a date.');
        }

        $availableModel = new AvailableDateModel();

        // Prevent duplicates
        if ($availableModel->where('date', $date)->first()) {
            return redirect()->back()->with('error', 'This date is already marked as available.');
        }

        $availableModel->insert(['date' => $date]);

        return redirect()->back()->with('success', "Date $date marked as available.");
    }

    /**
     * Staff: Add availability (called from calendar click)
     */
    public function addAvailability()
    {
        $data = $this->request->getJSON(true);
        if (empty($data['date'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No date provided'
            ]);
        }

        $model = new AvailableDateModel();
        if (!$model->where('date', $data['date'])->first()) {
            $model->insert(['date' => $data['date']]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "Date {$data['date']} marked as available"
        ]);
    }

    /**
     * Staff: Remove availability (called from calendar click)
     */
    public function removeAvailability()
    {
        $data = $this->request->getJSON(true);
        if (empty($data['date'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No date provided'
            ]);
        }

        $model = new AvailableDateModel();
        $model->where('date', $data['date'])->delete();

        return $this->response->setJSON([
            'success' => true,
            'message' => "Date {$data['date']} removed from availability"
        ]);
    }

    /**
     * Return all available dates
     */
public function getAvailableDates()
{
    $availableModel = new \App\Models\AvailableDateModel();
    $dates = $availableModel->findAll();

    // ✅ Ensure dates are in 'YYYY-MM-DD' format
    $formattedDates = [];
    foreach ($dates as $row) {
        $formattedDates[] = date('Y-m-d', strtotime($row['date']));
    }

    return $this->response->setJSON($formattedDates);
}



    /**
     * User: Book an appointment
     */
    public function set()
    {
        helper(['form', 'url']);

        $rules = [
            'date'    => 'required|valid_date[Y-m-d]',
            'time'    => 'required',
            'purpose' => 'required|min_length[10]',
             'contactnumber'  => 'required|regex_match[/^[0-9]{11}$/]' 
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->listErrors());
        }

        $appointmentDate = $this->request->getPost('date');
        $appointmentTime = $this->request->getPost('time');
         $contactNumber   = $this->request->getPost('contactnumber');

        // ✅ Check if date is available
        $availableModel = new AvailableDateModel();
        if (!$availableModel->where('date', $appointmentDate)->first()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'This date is not available for appointments.');
        }

        // Prevent booking past dates/times
        if ($appointmentDate < date('Y-m-d')) {
            return redirect()->back()->withInput()
                ->with('error', 'Cannot book appointments for past dates.');
        }
        if ($appointmentDate === date('Y-m-d') && strtotime($appointmentTime) <= time()) {
            return redirect()->back()->withInput()
                ->with('error', 'Cannot book appointments for past times.');
        }

        $model = new AppointmentModel();

        // 1️⃣ Check if fully booked (per-day limit)
        $maxSlotsPerDay = 5;
        $totalBookedForDate = $model->where('appointment_date', $appointmentDate)
            ->whereIn('status', ['Pending', 'Approved'])
            ->countAllResults();

        if ($totalBookedForDate >= $maxSlotsPerDay) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'This date is fully booked. Please choose another.');
        }

        // 2️⃣ Check if time slot already taken
        $existingCount = $model->where('appointment_date', $appointmentDate)
            ->where('appointment_time', $appointmentTime)
            ->whereIn('status', ['Pending', 'Approved'])
            ->countAllResults();

        if ($existingCount > 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'This time slot is already taken.');
        }

        // 3️⃣ Save appointment
        $userId = session()->get('account_id');
        $accountModel = new AccountModel();
        $user = $accountModel->find($userId);

        $model->save([
            'user_id'          => $userId,
            'fullname'         => $user['full_name'],
            'email'            => $user['email'],
            'contact_number'   => $contactNumber, // ✅ new field saved
            'appointment_date' => $appointmentDate,
            'appointment_time' => $appointmentTime,
            'purpose'          => $this->request->getPost('purpose'),
            'status'           => 'Pending'
        ]);

        return redirect()->back()->with('success', 'Appointment successfully set!');
    }

    /**
     * Get available time slots for a date
     */
   public function getAvailableSlots($date)
{
    helper('date');
    $db = \Config\Database::connect();
    $availableModel = new \App\Models\AvailableDateModel();

    date_default_timezone_set('Asia/Manila');
    $today = date('Y-m-d');
    $maxSlotsPerDay = 5;

    // 🔹 Validate date format
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        return $this->response->setJSON([
            'success' => false,
            'available' => [],
            'message' => 'Invalid date format. Expected YYYY-MM-DD.'
        ]);
    }

    // 🔹 Check if date exists in available_dates
    $isAvailable = $availableModel->where('date', $date)->first();
    if (!$isAvailable) {
        return $this->response->setJSON([
            'success' => false,
            'available' => [],
            'message' => 'This date is not available for booking.'
        ]);
    }

    // 🔹 Prevent past bookings
    if ($date < $today) {
        return $this->response->setJSON([
            'success' => false,
            'available' => [],
            'message' => 'Cannot book appointments for past dates.'
        ]);
    }

    // 🔹 Define working hours
    $timeSlots = [
        "08:00:00", "09:00:00", "10:00:00", "11:00:00",
        "13:00:00", "14:00:00", "15:00:00", "16:00:00"
    ];

    // 🔹 Check if fully booked for the day
    $totalCount = $db->table('appointments')
        ->where('appointment_date', $date)
        ->whereIn('status', ['Pending', 'Approved'])
        ->countAllResults();

    if ($totalCount >= $maxSlotsPerDay) {
        return $this->response->setJSON([
            'success' => false,
            'available' => [],
            'message' => 'This date is fully booked.'
        ]);
    }

    // 🔹 Compute available slots
    $availableSlots = [];
    $currentTime = date('H:i:s');
    $isToday = ($date === $today);

    foreach ($timeSlots as $slot) {
        if ($isToday && $slot <= $currentTime) {
            continue; // Skip past time slots for current day
        }

        $count = $db->table('appointments')
            ->where('appointment_date', $date)
            ->where('appointment_time', $slot)
            ->whereIn('status', ['Pending', 'Approved'])
            ->countAllResults();

        if ($count === 0) {
            $availableSlots[] = $slot;
        }
    }

    // 🔹 Return structured JSON
    return $this->response->setJSON([
        'success' => true,
        'available' => $availableSlots,
        'message' => count($availableSlots)
            ? 'Available time slots retrieved successfully.'
            : 'No available time slots left for this date.'
    ]);
}


    /**
     * Delete appointment
     */
    public function delete($id)
    {
        $model = new AppointmentModel();

        if ($model->delete($id)) {
            return redirect()->back()->with('success', 'Appointment deleted successfully.');
        }

        return redirect()->back()->with('error', 'Failed to delete appointment.');
    }
}
