<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhatsappClick;
use App\Models\WhatsappContact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WhatsappContactController extends Controller
{
    /**
     * Reporting windows offered on the activity page, in days (null = all time).
     *
     * @var array<string, int|null>
     */
    private const PERIODS = ['7' => 7, '30' => 30, '90' => 90, 'all' => null];

    /**
     * Who tapped WhatsApp in the shop, how many different people that was, and which contact they picked.
     */
    public function index(Request $request): View
    {
        $period = array_key_exists((string) $request->query('period'), self::PERIODS) ? (string) $request->query('period') : '30';
        $days = self::PERIODS[$period];

        $clicks = fn () => WhatsappClick::query()->when($days, fn ($query) => $query->where('created_at', '>=', now()->subDays($days)));

        return view('admin.whatsapp.index', [
            'period' => $period,
            'periods' => array_keys(self::PERIODS),
            'totals' => [
                'clicks' => $clicks()->count(),
                'people' => $clicks()->distinct()->count('visitor'),
                'chats' => $clicks()->where('source', 'chat')->count(),
                'orders' => $clicks()->where('source', 'order')->count(),
            ],
            'byContact' => $clicks()->toBase()
                ->selectRaw("contact_number, max(contact_label) as label, count(*) as clicks, count(distinct visitor) as people, sum(case when source = 'order' then 1 else 0 end) as orders, max(created_at) as last_clicked_at")
                ->groupBy('contact_number')
                ->orderByDesc('clicks')
                ->get(),
            'recent' => $clicks()->with(['user', 'product'])->latest('created_at')->latest('id')->paginate(25)->withQueryString(),
        ]);
    }

    public function edit(): View
    {
        $contacts = WhatsappContact::orderBy('position')->get();

        return view('admin.whatsapp.edit', [
            'slots' => collect(range(0, WhatsappContact::SLOTS - 1))->map(fn (int $index) => $contacts->get($index)),
            'fallbackNumber' => $contacts->isEmpty() ? config('store.whatsapp') : null,
        ]);
    }

    /**
     * Replaces the saved numbers with the filled-in slots; an empty slot is left off the storefront.
     */
    public function update(Request $request): RedirectResponse
    {
        $contacts = collect($request->input('contacts', []))
            ->take(WhatsappContact::SLOTS)
            ->map(fn ($contact) => [
                'label' => trim((string) ($contact['label'] ?? '')),
                'number' => preg_replace('/\D/', '', (string) ($contact['number'] ?? '')),
            ])
            ->values();

        $request->merge(['contacts' => $contacts->all()]);

        $request->validate([
            'contacts' => ['array', 'max:'.WhatsappContact::SLOTS],
            'contacts.*.label' => ['required_with:contacts.*.number', 'max:40'],
            'contacts.*.number' => ['nullable', 'digits_between:8,15'],
        ], [
            'contacts.*.label.required_with' => 'Give WhatsApp number :position a label, e.g. Sales.',
            'contacts.*.number.digits_between' => 'WhatsApp number :position must be 8 to 15 digits, starting with the country code.',
        ]);

        DB::transaction(function () use ($contacts) {
            WhatsappContact::query()->delete();

            $contacts->filter(fn (array $contact) => $contact['number'] !== '')
                ->values()
                ->each(fn (array $contact, int $position) => WhatsappContact::create([...$contact, 'position' => $position]));
        });

        return redirect()->route('admin.whatsapp.edit')->with('status', 'WhatsApp numbers were saved.');
    }
}
