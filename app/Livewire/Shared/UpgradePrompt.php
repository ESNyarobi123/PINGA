<?php

namespace App\Livewire\Shared;

use App\Services\SubscriptionLimitsService;
use Livewire\Component;

class UpgradePrompt extends Component
{
    public string $feature = '';

    public bool $visible = true;

    protected SubscriptionLimitsService $limitsService;

    public function boot(SubscriptionLimitsService $limitsService): void
    {
        $this->limitsService = $limitsService;
    }

    public function close(): void
    {
        $this->visible = false;
    }

    public function render()
    {
        if (! $this->visible) {
            return <<<'blade'
                <div></div>
            blade;
        }

        $user = auth()->user();
        $suggested = $this->limitsService->getSuggestedUpgrade($user);

        if (! $suggested) {
            return <<<'blade'
                <div></div>
            blade;
        }

        $featureMessages = [
            'analytics' => [
                'title' => 'Analytics Haikubaliwi',
                'message' => 'Pata data muhimu kuhusu utendaji wako na mafanikio yako.',
            ],
            'portfolio' => [
                'title' => 'Kikomo cha Picha Kimefikiwa',
                'message' => 'Ongeza picha zaidi za kazi zako ili uonekane zaidi.',
            ],
            'services' => [
                'title' => 'Kikomo cha Huduma Kimefikiwa',
                'message' => 'Weka huduma zaidi ili upate wateja wengi zaidi.',
            ],
            'bids' => [
                'title' => 'Kikomo cha Maombi Kimefikiwa',
                'message' => 'Tuma maombi zaidi ya kazi kila siku.',
            ],
            'custom_url' => [
                'title' => 'URL ya Kibinafsi Haikubaliwi',
                'message' => 'Pata anwani yako mwenyewe ya kipekee wa kushiriki wasifu wako.',
            ],
            'verified' => [
                'title' => 'Verified Tick Haikubaliwi',
                'message' => 'Pata alama ya uhakiki kuonyesha uaminifu wako.',
            ],
            'featured_category' => [
                'title' => 'Uonekano wa Juu Haikubaliwi',
                'message' => 'Onekana mwanzo katika orodha za kategoria.',
            ],
            'priority_support' => [
                'title' => 'Msaada wa Kipaumbele Haikubaliwi',
                'message' => 'Pata msaada haraka zaidi wakati wowote.',
            ],
            'default' => [
                'title' => 'Panda Mpango Wako',
                'message' => 'Fungua uwezo zaidi na faida za Winga Bora.',
            ],
        ];

        $data = $featureMessages[$this->feature] ?? $featureMessages['default'];

        return view('livewire.shared.upgrade-prompt', [
            'title' => $data['title'],
            'message' => $data['message'],
            'suggested' => $suggested,
        ]);
    }
}
