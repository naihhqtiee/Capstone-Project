<?php

namespace App\Controllers;

use App\Models\AppointmentModel;

class AppointmentController extends BaseController
{
public function set()
{
    helper(['form', 'url']);

    $rules = [
        'fullname' => 'required|min_length[3]',
        'email'    => 'required|valid_email',
        'date'     => 'required|valid_date[Y-m-d]',
        'time'     => 'required',
        'purpose'  => 'required|min_length[10]'
    ];

    if (! $this->validate($rules)) {
        return redirect()->back()
                         ->withInput()
                         ->with('errors', $this->validator->listErrors());
    }

    $appointmentDate = $this->request->getPost('date');
    $appointmentTime = $this->request->getPost('time');

    // Prevent booking for past dates/times
    if ($appointmentDate < date('Y-m-d')) {
        return redirect()->back()->withInput()
                         ->with('error', 'Cannot book appointments for past dates.');
    }
    if ($appointmentDate === date('Y-m-d') && strtotime($appointmentTime) <= time()) {
        return redirect()->back()->withInput()
                         ->with('error', 'Cannot book appointments for past times.');
    }

    $model = new AppointmentModel();

    // 1️⃣ Check if the whole date is already full
    $totalBookedForDate = $model->where('appointment_date', $appointmentDate)
                               ->whereIn('status', ['Pending', 'Approved'])
                               ->countAllResults();

    // 8 slots per day (08:00, 09:00, 10:00, 11:00, 13:00, 14:00, 15:00, 16:00)
    $maxSlotsPerDay = 5;
    if ($totalBookedForDate >= $maxSlotsPerDay) {
        return redirect()->back()
                         ->withInput()
                         ->with('error', 'This date is fully booked. Please choose another date.');
    }

    // 2️⃣ Check if this specific slot is available
    $existingCount = $model->where('appointment_date', $appointmentDate)
                          ->where('appointment_time', $appointmentTime)
                          ->whereIn('status', ['Pending', 'Approved'])
                          ->countAllResults();

    if ($existingCount > 0) {
        return redirect()->back()
                         ->withInput()
                         ->with('error', 'This time slot is already taken.');
    }

    // 3️⃣ Save appointment if checks pass
    $model->save([
        'fullname'         => $this->request->getPost('fullname'),
        'email'            => $this->request->getPost('email'),
        'appointment_date' => $appointmentDate,
        'appointment_time' => $appointmentTime,
        'purpose'          => $this->request->getPost('purpose'),
        'status'           => 'Pending'
    ]);

    return redirect()->back()->with('success', 'Appointment successfully set!');
}


public function getAvailableSlots($date)
{
    $db = \Config\Database::connect();
    $maxSlotsPerDay = 5; // ✅ Same as in set()

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        return $this->response->setJSON([
            "available" => [],
            "error" => "Invalid date format"
        ]);
    }

    date_default_timezone_set('Asia/Manila');
    $today = date('Y-m-d');
    if ($date < $today) {
        return $this->response->setJSON([
            "available" => [],
            "error" => "Cannot book appointments for past dates"
        ]);
    }

    $timeSlots = ["08:00:00", "09:00:00", "10:00:00", "11:00:00",
                  "13:00:00", "14:00:00", "15:00:00", "16:00:00"];

    // ✅ Check if date is already fully booked (max 5)
    $totalCount = $db->table('appointments')
                    ->where('appointment_date', $date)
                    ->whereIn('status', ['Pending', 'Approved'])
                    ->countAllResults();

    if ($totalCount >= $maxSlotsPerDay) {
        return $this->response->setJSON([
            "available" => [],
            "error" => "This date is fully booked."
        ]);
    }

    $availableSlots = [];
    $currentTime = date('H:i:s');
    $isToday = ($date === $today);

    foreach ($timeSlots as $slot) {
        if ($isToday && $slot <= $currentTime) {
            continue;
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

    return $this->response->setJSON(["available" => $availableSlots]);
}
public function delete($id)
{
    $model = new AppointmentModel();
    
    if ($model->delete($id)) {
        return redirect()->back()->with('success', 'Appointment deleted successfully.');
    }

    return redirect()->back()->with('error', 'Failed to delete appointment.');
}



}