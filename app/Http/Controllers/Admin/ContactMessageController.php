<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index()
    {
        $items = ContactMessage::query()->orderByDesc('id')->get();

        return view('admin.contact_messages.index', compact('items'));
    }

    public function show(ContactMessage $message)
    {
        return view('admin.contact_messages.show', compact('message'));
    }

    public function updateStatus(Request $request, ContactMessage $message)
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'max:50'],
        ]);

        $message->status = $data['status'];
        $message->save();

        return redirect()->route('admin.contact-messages.show', $message)->with('status', 'Status updated');
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();

        return redirect()->route('admin.contact-messages.index')->with('status', 'Message deleted');
    }
}

