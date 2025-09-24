<?php

namespace App\Controllers;

use App\Models\EventModel;

class EventsController extends BaseController
{
    public function index()
    {
        $eventModel = new EventModel();
        $data['events'] = $eventModel->findAll();

        return view('staff/events', $data);
    }

 public function store()
{
    $eventModel = new EventModel();

    $fileName = null;
    $file = $this->request->getFile('attachment');
    if ($file && $file->isValid() && !$file->hasMoved()) {
        $fileName = $file->getRandomName();
        $file->move('uploads/events', $fileName);
    }

    // Capture input
    $start_date = $this->request->getPost('start_date');
    $start_time = $this->request->getPost('start_time');
    $end_date   = $this->request->getPost('end_date');
    $end_time   = $this->request->getPost('end_time');

    // If user did NOT check multi-day → default end = start
    if (empty($end_date)) {
        $end_date = $start_date;
    }
    if (empty($end_time)) {
        $end_time = $start_time;
    }

    // ✅ Save date & time in their respective columns
    $eventModel->save([
        'event_name'  => $this->request->getPost('event_name'),
        'description' => $this->request->getPost('description'),
        'start_date'  => $start_date,
        'start_time'  => $start_time,
        'end_date'    => $end_date,
        'end_time'    => $end_time,
        'location'    => $this->request->getPost('location'),
        'audience'    => $this->request->getPost('audience'),
        'file'        => $fileName,
        'status'      => 'active'
    ]);

    session()->setFlashdata('success', 'Event has been successfully added!');
    return redirect()->to('staff/events');
}
  public function view($id)
    {
        $eventModel = new EventModel();
        $event = $eventModel->find($id);

        return $this->response->setJSON($event);
    }

    public function edit($id)
    {
        $eventModel = new EventModel();
        $event = $eventModel->find($id);

        if (!$event) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Event not found");
        }

        return view('admin/events/edit', ['event' => $event]);
    }

public function update()
{
    $eventModel = new \App\Models\EventModel();

    $id = $this->request->getPost('id');
    if (!$id) {
        return $this->response->setJSON(['success' => false, 'message' => 'Missing event ID.']);
    }

    $data = [
        'event_name'   => $this->request->getPost('event_name'),
        'description'  => $this->request->getPost('description'),
        'location'     => $this->request->getPost('location'),
        'start_date'   => $this->request->getPost('start_date'),
        'end_date'     => $this->request->getPost('end_date'),
        'audience'     => $this->request->getPost('audience'),
        'updated_at'   => date('Y-m-d H:i:s')
    ];

    if ($eventModel->update($id, $data)) {
        return $this->response->setJSON(['success' => true, 'message' => 'Event updated successfully!']);
    } else {
        return $this->response->setJSON(['success' => false, 'message' => 'Failed to update event.']);
    }
}

    public function delete($id)
    {
        $eventModel = new EventModel();
        $eventModel->delete($id);

        return $this->response->setJSON(['success' => true, 'message' => 'Event deleted successfully']);
    }
}


