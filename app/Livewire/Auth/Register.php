<?php

namespace App\Livewire\Auth;

use App\Mail\SendOtpMail;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Register extends Component
{
    public int $step = 1;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $mkoa = '';

    public string $wilaya = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $role = 'winga';

    public string $whatsapp = '';

    // Map form roles to database role names
    private function getDbRole(string $formRole): string
    {
        return match ($formRole) {
            'winga' => 'mfanyakazi',  // Worker role in DB
            'mteja' => 'muajili',     // Employer role in DB
            default => 'mfanyakazi',
        };
    }

    public function getWilayaOptions(): array
    {
        $map = [
            'Dar es Salaam' => ['Ilala', 'Kinondoni', 'Temeke', 'Ubungo', 'Kigamboni'],
            'Arusha' => ['Arusha City', 'Arusha', 'Karatu', 'Longido', 'Meru', 'Monduli', 'Ngorongoro'],
            'Mwanza' => ['Ilemela', 'Kwimba', 'Magu', 'Misungwi', 'Nyamagana', 'Sengerema', 'Ukerewe'],
            'Dodoma' => ['Bahi', 'Chamwino', 'Chemba', 'Dodoma City', 'Kondoa', 'Kongwa', 'Mpwapwa'],
            'Tanga' => ['Handeni', 'Kilindi', 'Korogwe', 'Lushoto', 'Mkinga', 'Muheza', 'Pangani', 'Tanga City'],
            'Mbeya' => ['Busokelo', 'Chunya', 'Kyela', 'Mbarali', 'Mbeya City', 'Mbeya', 'Momba', 'Rungwe'],
            'Morogoro' => ['Gairo', 'Ifakara', 'Kilombero', 'Kilosa', 'Malinyi', 'Mlimba', 'Morogoro City', 'Morogoro', 'Mvomero', 'Ulanga'],
            'Kilimanjaro' => ['Hai', 'Moshi City', 'Moshi', 'Mwanga', 'Rombo', 'Same', 'Siha'],
            'Kagera' => ['Biharamulo', 'Bukoba City', 'Bukoba', 'Karagwe', 'Kyerwa', 'Misenyi', 'Muleba', 'Ngara'],
            'Mara' => ['Bunda', 'Butiama', 'Musoma City', 'Musoma', 'Rorya', 'Serengeti', 'Tarime'],
            'Pwani' => ['Bagamoyo', 'Kibaha City', 'Kibaha', 'Kisarawe', 'Mafia', 'Mkuranga', 'Rufiji'],
            'Iringa' => ['Iringa City', 'Iringa', 'Kilolo', 'Mafinga', 'Mufindi'],
            'Ruvuma' => ['Madaba', 'Mbinga', 'Namtumbo', 'Nyasa', 'Songea City', 'Songea', 'Tunduru'],
            'Shinyanga' => ['Kahama', 'Kishapu', 'Msalala', 'Shinyanga City', 'Shinyanga', 'Ushetu'],
            'Singida' => ['Ikungi', 'Iramba', 'Manyoni', 'Mkalama', 'Singida City', 'Singida'],
            'Tabora' => ['Igunga', 'Kaliua', 'Nzega', 'Sikonge', 'Tabora City', 'Tabora', 'Urambo', 'Uyui'],
            'Kigoma' => ['Buhigwe', 'Kakonko', 'Kasulu', 'Kibondo', 'Kigoma City', 'Kigoma', 'Uvinza'],
            'Lindi' => ['Kilwa', 'Lindi City', 'Lindi', 'Liwale', 'Nachingwea', 'Ruangwa'],
            'Mtwara' => ['Masasi', 'Mtwara City', 'Mtwara', 'Nanyamba', 'Nanyumbu', 'Newala', 'Tandahimba'],
            'Geita' => ['Bukombe', 'Chato', 'Geita', 'Mbogwe', 'Nyang\'hwale'],
            'Katavi' => ['Mlele', 'Mpanda City', 'Mpanda'],
            'Njombe' => ['Kilolo', 'Ludewa', 'Makete', 'Njombe City', 'Njombe', 'Wanging\'ombe'],
            'Rukwa' => ['Kalambo', 'Nkasi', 'Sumbawanga City', 'Sumbawanga'],
            'Simiyu' => ['Bariadi', 'Busega', 'Itilima', 'Maswa', 'Meatu'],
            'Songwe' => ['Ileje', 'Mbozi', 'Momba', 'Songwe'],
        ];

        return $map[$this->mkoa] ?? [];
    }

    public function updatedMkoa(): void
    {
        $this->wilaya = '';
    }

    public function updatedEmail(): void
    {
        $this->email = $this->normalizeEmail($this->email);
    }

    public function updatedPhone(): void
    {
        $this->phone = $this->normalizePhone($this->phone);
    }

    public function updatedWhatsapp(): void
    {
        if ($this->whatsapp !== '') {
            $this->whatsapp = $this->normalizePhone($this->whatsapp);
        }
    }

    public function nextStep(): void
    {
        $this->normalizeContactFields();
        $this->validate($this->accountRules(), $this->validationMessages());
        $this->step = 2;
    }

    public function prevStep(): void
    {
        $this->step = 1;
    }

    public function register(): mixed
    {
        $this->normalizeContactFields();

        try {
            $this->validate(
                array_merge($this->accountRules(), $this->finalStepRules()),
                $this->validationMessages(),
            );
        } catch (ValidationException $exception) {
            if ($this->hasAccountFieldErrors($exception)) {
                $this->step = 1;
            }

            throw $exception;
        }

        try {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'whatsapp' => $this->whatsapp ?: null,
                'mkoa' => $this->mkoa,
                'wilaya' => $this->wilaya,
                'password' => Hash::make($this->password),
                'role' => $this->role,
                'two_factor_enabled' => true,
            ]);
        } catch (UniqueConstraintViolationException $exception) {
            $this->handleUniqueConstraintViolation($exception);

            return null;
        }

        $user->assignRole($this->getDbRole($this->role));

        $otp = $user->generateOtp();
        Mail::to($user->email)->send(new SendOtpMail($otp));

        session(['pending_user_id' => $user->id]);

        return redirect()->route('verify-otp');
    }

    /**
     * @return array<string, mixed>
     */
    private function accountRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'phone' => [
                'required',
                'string',
                'regex:/^[0-9]{9,15}$/',
                Rule::unique('users', 'phone'),
            ],
            'mkoa' => 'required|string',
            'wilaya' => 'nullable|string',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function finalStepRules(): array
    {
        $rules = [
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:winga,mteja',
        ];

        if ($this->role === 'winga') {
            $rules['whatsapp'] = 'required|string|regex:/^[0-9]{9,15}$/';
        } else {
            $rules['whatsapp'] = 'nullable|string|regex:/^[0-9]{9,15}$/';
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    private function validationMessages(): array
    {
        return [
            'mkoa.required' => 'Tafadhali chagua mkoa wako',
            'email.unique' => __('messages.auth.email_taken'),
            'phone.unique' => __('messages.auth.phone_taken'),
            'phone.regex' => __('messages.auth.phone_invalid'),
            'whatsapp.required' => 'Namba ya WhatsApp inahitajika kwa Winga (Service Provider)',
            'whatsapp.regex' => __('messages.auth.phone_invalid'),
        ];
    }

    private function normalizeContactFields(): void
    {
        $this->email = $this->normalizeEmail($this->email);
        $this->phone = $this->normalizePhone($this->phone);

        if ($this->whatsapp !== '') {
            $this->whatsapp = $this->normalizePhone($this->whatsapp);
        }
    }

    private function normalizeEmail(string $email): string
    {
        return strtolower(trim($email));
    }

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if (strlen($digits) === 12 && str_starts_with($digits, '255')) {
            return '0'.substr($digits, 3);
        }

        return $digits;
    }

    private function hasAccountFieldErrors(ValidationException $exception): bool
    {
        return $exception->validator->errors()->hasAny([
            'name',
            'email',
            'phone',
            'mkoa',
            'wilaya',
        ]);
    }

    private function handleUniqueConstraintViolation(UniqueConstraintViolationException $exception): void
    {
        $message = $exception->getMessage();

        if (str_contains($message, 'users_email_unique') || str_contains($message, 'for key \'users.email\'')) {
            $this->step = 1;
            $this->addError('email', __('messages.auth.email_taken'));

            return;
        }

        if (str_contains($message, 'users_phone_unique') || str_contains($message, 'for key \'users.phone\'')) {
            $this->step = 1;
            $this->addError('phone', __('messages.auth.phone_taken'));

            return;
        }

        throw $exception;
    }

    public function render()
    {
        return view('livewire.auth.register', [
            'wilayaOptions' => $this->getWilayaOptions(),
        ])->layout('layouts.auth');
    }
}
