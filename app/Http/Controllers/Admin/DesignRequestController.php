<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DesignRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DesignRequestController extends Controller
{
    public function index(Request $request): View
    {
        $requests = DesignRequest::query()
            ->when($request->string('q')->trim()->value(), fn ($query, $term) => $query->where(fn ($query) => $query
                ->where('first_name', 'like', "%{$term}%")
                ->orWhere('last_name', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")))
            ->when($request->input('status'), fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.design-requests.index', [
            'requests' => $requests,
            'counts' => DesignRequest::toBase()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
        ]);
    }

    public function show(DesignRequest $designRequest): View
    {
        return view('admin.design-requests.show', ['designRequest' => $designRequest]);
    }

    public function update(Request $request, DesignRequest $designRequest): RedirectResponse
    {
        $designRequest->forceFill($request->validate([
            'status' => ['required', Rule::in(array_keys(DesignRequest::STATUSES))],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]))->save();

        return back()->with('status', "Request from {$designRequest->fullName()} was updated.");
    }

    public function destroy(DesignRequest $designRequest): RedirectResponse
    {
        $designRequest->delete();

        return redirect()->route('admin.design-requests.index')->with('status', "Request from {$designRequest->fullName()} was deleted.");
    }
}
