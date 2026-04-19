<x-mail::message>
# Habari!

Kumbuka kuepuka kushirikisha mtu mwingine nambari hii. Kuthibitisha akaunti yako (au kuingia), tumia nambari ya siri ya OTP ifuatayo:

<x-mail::panel>
# {{ $otp }}
</x-mail::panel>

Nambari hii itadumu kwa muda wa dakika 10 pekee. Ikiwa hukufanya ombi hili, tafadhali puuza barua pepe hii.

Asante,<br>
Timu ya {{ config('app.name') }}
</x-mail::message>
