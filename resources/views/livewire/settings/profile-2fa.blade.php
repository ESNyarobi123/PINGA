<div class="bg-white dark:bg-zinc-800 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-6 mt-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-zinc-900 dark:text-white">Uthibitisho wa 2FA (Nambari ya Siri)</h3>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                Ulinzi zaidi kwa akaunti yako ya Winga
            </p>
        </div>
        <div class="flex flex-col items-center">
            @if(auth()->user()->two_factor_enabled)
                <flux:badge color="green" class="mb-2"> Imewezeshwa ✅ </flux:badge>
            @else
                <flux:badge color="zinc" class="mb-2"> Imezimwa ❌ </flux:badge>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mt-4 p-3 rounded-xl bg-winga-50 dark:bg-winga-900/20 border border-winga-200 dark:border-winga-800 text-winga-700 dark:text-winga-300 font-medium text-sm">
            ⭐ {{ session('success') }}
        </div>
    @endif

    <div class="mt-8 pt-6 border-t border-zinc-100 dark:border-zinc-700">
        @if(auth()->user()->two_factor_enabled)
            <div class="flex justify-between items-center bg-zinc-50 dark:bg-zinc-900/50 p-4 rounded-xl">
                <div>
                    <h4 class="font-bold text-zinc-900 dark:text-white">Zima 2FA</h4>
                    <p class="text-sm text-zinc-500 mt-1 max-w-sm">
                        Kuzima huduma hii kutapunguza usalama wa akaunti yako.
                    </p>
                </div>
                <flux:button variant="danger" wire:click="confirmDisable">
                    Zima 2FA Sasa
                </flux:button>
            </div>
            
            <flux:modal variant="flyout" wire:model.live="confirmingDisable">
                <div class="p-6">
                    <div class="text-center mb-6">
                        <div class="text-5xl mb-4">⚠️</div>
                        <h2 class="text-2xl font-bold text-red-600 dark:text-red-500">Uthibitisho</h2>
                        <p class="text-zinc-600 dark:text-zinc-400 mt-2">
                            Una uhakika unataka kukatisha uthibitisho wa 2FA (OTP)? <br>Akaunti yako haitakuwa salama sana tena!
                        </p>
                    </div>

                    <div class="flex gap-4">
                        <flux:button variant="ghost" wire:click="$set('confirmingDisable', false)" class="w-full">
                            Hapana, Futa
                        </flux:button>
                        <flux:button variant="danger" wire:click="disable2FA" class="w-full">
                            Ndio, Nina Uhakika
                        </flux:button>
                    </div>
                </div>
            </flux:modal>
        @else
            <div class="flex justify-between items-center bg-winga-50 dark:bg-winga-900/20 p-4 rounded-xl border border-winga-200 dark:border-winga-800">
                <div>
                    <h4 class="font-bold text-winga-900 dark:text-winga-100">Washa 2FA</h4>
                    <p class="text-sm text-winga-700 mt-1 max-w-sm">
                        Kulinda pesa zako na taarifa zako unapofungua akaunti yako, wacha huduma hii.
                    </p>
                </div>
                <flux:button class="!bg-winga-500 !text-white hover:!bg-winga-600" wire:click="enable2FA">
                    Wezesha 2FA sasa
                </flux:button>
            </div>
        @endif
    </div>
</div>
