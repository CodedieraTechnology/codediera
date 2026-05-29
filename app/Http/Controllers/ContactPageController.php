<?php

namespace App\Http\Controllers;

use App\Models\ContactSetting;

class ContactPageController extends Controller
{
    public function index()
    {
        $contactSettings = ContactSetting::query()->first();

        return view('pages.contact', compact('contactSettings'));
    }
}

