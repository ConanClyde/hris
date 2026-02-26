<?php

namespace App\Http\Controllers\Auth;

use App\Features\Auth\Actions\RegisterNewUser;
use App\Features\Employees\Models\Division;
use App\Http\Controllers\Controller;
use App\Mail\UserRegisteredMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class RegisterController extends Controller
{
    public function __construct(
        private RegisterNewUser $registerNewUser
    ) {}

    public function create(): Response
    {
        $structure = Division::with(['subdivisions.sections', 'sections'])
            ->get()
            ->map(function ($division) {
                return [
                    'id' => $division->id,
                    'name' => $division->name,
                    'subdivisions' => $division->subdivisions->map(function ($sub) {
                        return [
                            'id' => $sub->id,
                            'name' => $sub->name,
                            'sections' => $sub->sections->map(fn ($sec) => [
                                'id' => $sec->id,
                                'name' => $sec->name,
                            ]),
                        ];
                    }),
                    'sections' => $division->sections->map(fn ($sec) => [
                        'id' => $sec->id,
                        'name' => $sec->name,
                    ]),
                ];
            });

        return Inertia::render('auth/Register', [
            'organizationalStructure' => $structure,
            'canRegister' => true,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $this->registerNewUser->create($request->all());

        event(new Registered($user));

        // Email HR / Admin users about the new self-registration
        $hrRecipients = \App\Models\User::query()
            ->whereIn('role', ['admin', 'hr'])
            ->where('is_active', true)
            ->pluck('email')
            ->all();

        if ($hrRecipients !== []) {
            Mail::to($hrRecipients)->queue(new UserRegisteredMail($user, $request->ip()));
        }

        return redirect()
            ->route('login')
            ->with('status', 'Registration submitted. Your account is pending admin approval.');
    }
}
