<?php

namespace App\Livewire\Auth;

use App\Mail\SendOtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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

    // Map form roles to database role names
    private function getDbRole(string $formRole): string
    {
        return match ($formRole) {
            'winga' => 'mfanyakazi',  // Worker role in DB
            'mteja' => 'muajili',     // Employer role in DB
            default => 'mfanyakazi',
        };
    }
    public string $whatsapp = '';

    public function getWilayaOptions(): array
    {
        $map = [
            'Dar es Salaam' => ['Ilala', 'Kinondoni', 'Temeke', 'Ubungo', 'Kigamboni'],
            'Arusha'        => ['Arusha City', 'Arusha', 'Karatu', 'Longido', 'Meru', 'Monduli', 'Ngorongoro'],
            'Mwanza'        => ['Ilemela', 'Kwimba', 'Magu', 'Misungwi', 'Nyamagana', 'Sengerema', 'Ukerewe'],
            'Dodoma'        => ['Bahi', 'Chamwino', 'Chemba', 'Dodoma City', 'Kondoa', 'Kongwa', 'Mpwapwa'],
            'Tanga'         => ['Handeni', 'Kilindi', 'Korogwe', 'Lushoto', 'Mkinga', 'Muheza', 'Pangani', 'Tanga City'],
            'Mbeya'         => ['Busokelo', 'Chunya', 'Kyela', 'Mbarali', 'Mbeya City', 'Mbeya', 'Momba', 'Rungwe'],
            'Morogoro'      => ['Gairo', 'Ifakara', 'Kilombero', 'Kilosa', 'Malinyi', 'Mlimba', 'Morogoro City', 'Morogoro', 'Mvomero', 'Ulanga'],
            'Kilimanjaro'   => ['Hai', 'Moshi City', 'Moshi', 'Mwanga', 'Rombo', 'Same', 'Siha'],
            'Kagera'        => ['Biharamulo', 'Bukoba City', 'Bukoba', 'Karagwe', 'Kyerwa', 'Misenyi', 'Muleba', 'Ngara'],
            'Mara'          => ['Bunda', 'Butiama', 'Musoma City', 'Musoma', 'Rorya', 'Serengeti', 'Tarime'],
            'Pwani'         => ['Bagamoyo', 'Kibaha City', 'Kibaha', 'Kisarawe', 'Mafia', 'Mkuranga', 'Rufiji'],
            'Iringa'        => ['Iringa City', 'Iringa', 'Kilolo', 'Mafinga', 'Mufindi'],
            'Ruvuma'        => ['Madaba', 'Mbinga', 'Namtumbo', 'Nyasa', 'Songea City', 'Songea', 'Tunduru'],
            'Shinyanga'     => ['Kahama', 'Kishapu', 'Msalala', 'Shinyanga City', 'Shinyanga', 'Ushetu'],
            'Singida'       => ['Ikungi', 'Iramba', 'Manyoni', 'Mkalama', 'Singida City', 'Singida'],
            'Tabora'        => ['Igunga', 'Kaliua', 'Nzega', 'Sikonge', 'Tabora City', 'Tabora', 'Urambo', 'Uyui'],
            'Kigoma'        => ['Buhigwe', 'Kakonko', 'Kasulu', 'Kibondo', 'Kigoma City', 'Kigoma', 'Uvinza'],
            'Lindi'         => ['Kilwa', 'Lindi City', 'Lindi', 'Liwale', 'Nachingwea', 'Ruangwa'],
            'Mtwara'        => ['Masasi', 'Mtwara City', 'Mtwara', 'Nanyamba', 'Nanyumbu', 'Newala', 'Tandahimba'],
            'Geita'         => ['Bukombe', 'Chato', 'Geita', 'Mbogwe', 'Nyang\'hwale'],
            'Katavi'        => ['Mlele', 'Mpanda City', 'Mpanda'],
            'Njombe'        => ['Kilolo', 'Ludewa', 'Makete', 'Njombe City', 'Njombe', 'Wanging\'ombe'],
            'Rukwa'         => ['Kalambo', 'Nkasi', 'Sumbawanga City', 'Sumbawanga'],
            'Simiyu'        => ['Bariadi', 'Busega', 'Itilima', 'Maswa', 'Meatu'],
            'Songwe'        => ['Ileje', 'Mbozi', 'Momba', 'Songwe'],
        ];

        return $map[$this->mkoa] ?? [];
    }

    public function updatedMkoa(): void
    {
        $this->wilaya = '';
    }

    public function nextStep(): void
    {
        $this->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15|unique:users',
            'mkoa'  => 'required|string',
            'wilaya' => 'nullable|string',
        ], [
            'mkoa.required' => 'Tafadhali chagua mkoa wako',
        ]);
        $this->step = 2;
    }

    public function prevStep(): void
    {
        $this->step = 1;
    }

    public function register(): mixed
    {
        $rules = [
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:winga,mteja',
        ];

        if ($this->role === 'winga') {
            $rules['whatsapp'] = 'required|string|max:20';
        }

        $this->validate($rules, [
            'whatsapp.required' => 'Namba ya WhatsApp inahitajika kwa Winga (Service Provider)',
        ]);

        $user = User::create([
            'name'               => $this->name,
            'email'              => $this->email,
            'phone'              => $this->phone,
            'whatsapp'           => $this->whatsapp ?: null,
            'mkoa'               => $this->mkoa,
            'wilaya'             => $this->wilaya,
            'password'           => Hash::make($this->password),
            'role'               => $this->role,
            'two_factor_enabled' => true,
        ]);

        $user->assignRole($this->getDbRole($this->role));

        $otp = $user->generateOtp();
        Mail::to($user->email)->send(new SendOtpMail($otp));

        session(['pending_user_id' => $user->id]);

        return redirect()->route('verify-otp');
    }

    public function render()
    {
        return view('livewire.auth.register', [
            'wilayaOptions' => $this->getWilayaOptions(),
        ])->layout('layouts.auth');
    }
}
