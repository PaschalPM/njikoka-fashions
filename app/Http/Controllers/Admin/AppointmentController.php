<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    /**
     * Upcoming requests first (soonest date at the top), then past ones; "past" lists only dates before today.
     */
    public function index(Request $request): View
    {
        $isPast = $request->input('when') === 'past';

        $appointments = Appointment::query()
            ->when($request->string('q')->trim()->value(), fn ($query, $term) => $query->where(fn ($query) => $query
                ->where('first_name', 'like', "%{$term}%")
                ->orWhere('last_name', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")))
            ->when($request->input('status'), fn ($query, $status) => $query->where('status', $status))
            ->when($isPast, fn ($query) => $query->whereDate('preferred_date', '<', today())->orderByDesc('preferred_date'))
            ->unless($isPast, fn ($query) => $query->whereDate('preferred_date', '>=', today())->orderBy('preferred_date'))
            ->paginate(20)
            ->withQueryString();

        return view('admin.appointments.index', [
            'appointments' => $appointments,
            'counts' => Appointment::toBase()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
        ]);
    }

    public function show(Appointment $appointment): View
    {
        return view('admin.appointments.show', ['appointment' => $appointment]);
    }

    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        $appointment->forceFill($request->validate([
            'status' => ['required', Rule::in(array_keys(Appointment::STATUSES))],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]))->save();

        return back()->with('status', "Appointment for {$appointment->fullName()} was updated.");
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        $appointment->delete();

        return redirect()->route('admin.appointments.index')->with('status', "Appointment for {$appointment->fullName()} was deleted.");
    }
}
