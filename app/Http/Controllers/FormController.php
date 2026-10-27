<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\ContactMessage;
use App\Models\DesignRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FormController extends Controller
{
    public function contact(Request $request): RedirectResponse
    {
        ContactMessage::create($request->validate([
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['nullable', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:5000'],
        ]));

        return back()->with('status', 'Thank you! Your message has been sent. We will be in touch shortly.');
    }

    public function appointment(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'service' => ['required', Rule::in(config('store.services'))],
            'preferred_date' => ['required', 'date', 'after_or_equal:today'],
            'time_slot' => ['required', Rule::in(config('store.time_slots'))],
            'notes' => ['nullable', 'string', 'max:5000'],
            'policy' => ['accepted'],
        ]);

        unset($data['policy']);
        Appointment::create($data);

        return back()->with('status', 'Your appointment request has been received. We will confirm by email.');
    }

    public function design(Request $request): RedirectResponse
    {
        DesignRequest::create($request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'garment_type' => ['required', Rule::in(config('store.garment_types'))],
            'fabrics' => ['nullable', 'array'],
            'fabrics.*' => [Rule::in(config('store.fabrics'))],
            'description' => ['required', 'string', 'max:5000'],
        ]));

        return back()->with('status', 'Your custom design request has been submitted. We will reach out to schedule your consultation.');
    }
}
