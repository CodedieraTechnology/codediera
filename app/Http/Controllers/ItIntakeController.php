<?php

namespace App\Http\Controllers;

use App\Models\ItIntake;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ItIntakeController extends Controller
{
    public function create()
    {
        return view('it_intake.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:255'],
            'matriculation_number' => ['required', 'string', 'max:255'],
            'specialization' => ['required', 'string', 'max:255'],
        ]);

        $intake = new ItIntake();
        $intake->fill($data);
        $intake->institution = 'IMSU';
        $intake->department = 'Computer Science';
        $intake->level = '400 Level';
        $intake->place_of_it = 'Codediera Technologies LTD';
        $intake->approval_status = 'pending';
        $intake->save();

        $mailSent = false;
        try {
            $html = '<p>Dear '.e($intake->student_name).',</p>'
                .'<p>Your IT Students Intake form has been submitted successfully.</p>'
                .'<p><strong>Institution:</strong> IMSU<br>'
                .'<strong>Department:</strong> '.e($intake->department).'<br>'
                .'<strong>Level:</strong> '.e($intake->level).'<br>'
                .'<strong>Place of I.T:</strong> '.e($intake->place_of_it).'<br>'
                .'<strong>Specialization:</strong> '.e($intake->specialization).'<br>'
                .'<strong>Status:</strong> PENDING</p>'
                .'<p>Contact your course rep for payment confirmation. Our team will notify you upon approval.</p>'
                .'<p>Regards,<br>'.e(config('app.name')).'</p>';

            Mail::send([], [], function ($message) use ($intake, $html) {
                $message->to($intake->email)
                    ->subject('IT Students Intake Submitted')
                    ->setBody($html, 'text/html');
            });
            $mailSent = true;
        } catch (\Throwable $e) {
            $mailSent = false;
        }

        $status = 'Submitted. Contact your course rep for payment confirmation and approval. Our team will notify you upon approval.';
        if ($mailSent) {
            $status .= ' A confirmation email has been sent to you.';
        }

        return redirect()->route('it-intake')->with('status', $status);
    }
}
