<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\Agency\UpdateAgencySettingsRequest;
use App\Models\Agency;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AgencySettingsController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display the settings form for the given agency.
     */
    public function edit(Agency $agency): View
    {
        $this->authorize('update', $agency);

        return view('backoffice.agencies.settings.edit', compact('agency'));
    }

    /**
     * Display the notifications settings form.
     */
    public function notifications(Agency $agency): View
    {
        $this->authorize('update', $agency);

        return view('Backoffice.profile.notifications-setting', compact('agency'));
    }

    /**
     * Display the invoice template settings form.
     */
    public function invoiceTemplate(Agency $agency): View
    {
        $this->authorize('update', $agency);

        return view('Backoffice.profile.invoice-template', compact('agency'));
    }

    /**
     * Display the company (system) settings form.
     */
    public function company(Agency $agency): View
    {
        $this->authorize('update', $agency);

        return view('Backoffice.profile.company-setting', compact('agency'));
    }

    /**
     * Display the signatures (branding) settings form.
     */
    public function signatures(Agency $agency): View
    {
        $this->authorize('update', $agency);

        return view('Backoffice.profile.signatures-setting', compact('agency'));
    }

    /**
     * Update the agency settings.
     */
    public function update(UpdateAgencySettingsRequest $request, Agency $agency): RedirectResponse
    {
        $this->authorize('update', $agency);

        $validated = $request->validated();

        // Extract media files from validated data
        $logo = $validated['logo'] ?? null;
        $signature = $validated['signature'] ?? null;
        $stamp = $validated['stamp'] ?? null;

        // Remove media from validated array to avoid storing in settings JSON
        unset($validated['logo'], $validated['signature'], $validated['stamp']);

        // Merge settings (deep merge to avoid overwriting all settings)
        $currentSettings = $agency->settings ?? [];
        $newSettings = array_replace_recursive($currentSettings, $validated);

        // Update settings
        $agency->update(['settings' => $newSettings]);

        // Handle media uploads
        if ($logo) {
            $agency->clearMediaCollection('logo');
            $agency->addMediaFromRequest('logo')
                ->toMediaCollection('logo');
        }

        if ($signature) {
            $agency->clearMediaCollection('signature');
            $agency->addMediaFromRequest('signature')
                ->toMediaCollection('signature');
        }

        if ($stamp) {
            $agency->clearMediaCollection('stamp');
            $agency->addMediaFromRequest('stamp')
                ->toMediaCollection('stamp');
        }

        return redirect()
            ->route('backoffice.agencies.settings.edit', $agency)
            ->with('toast', [
                'title'   => 'Succès',
                'message' => 'Paramètres de l\'agence mis à jour avec succès.',
                'dot'     => '#198754', // green
                'delay'   => 3500,
                'time'    => 'now',
            ]);
    }
}
