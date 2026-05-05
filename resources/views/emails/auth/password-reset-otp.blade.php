<x-mail::message>
# Ombi la Kubadilisha Nenosiri

Umepokea barua pepe hii kwa sababu tulipokea ombi la kubadilisha nenosiri la akaunti yako. Tumia nambari hii ya OTP:

<x-mail::panel>
# {{ $otp }}
</x-mail::panel>

Nambari hii itadumu kwa muda wa dakika 10 pekee. Ikiwa hukufanya ombi hili, tafadhali puuza barua pepe hii — nenosiri lako halitabadilishwa.

Asante,<br>
Timu ya {{ config('app.name') }}
</x-mail::message>
