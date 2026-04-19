<div>
    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('messages.admin_settings.title') }}</h1>
            <p class="text-zinc-500 dark:text-zinc-400">{{ __('messages.admin_settings.subtitle') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="exportSettings"
                    class="px-4 py-2 bg-zinc-600 hover:bg-zinc-700 text-white rounded-lg text-sm font-medium transition">
                📤 {{ __('messages.admin_settings.export_settings') }}
            </button>
            <button wire:click="clearCache"
                    wire:confirm="{{ __('messages.admin_settings.confirm_clear_cache') }}"
                    class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm font-medium transition">
                🗑️ {{ __('messages.admin_settings.clear_cache') }}
            </button>
            <button wire:click="runBackup"
                    wire:confirm="{{ __('messages.admin_settings.confirm_backup') }}"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                💾 {{ __('messages.admin_settings.run_backup') }}
            </button>
        </div>
    </div>

    {{-- Category Navigation --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-2 mb-6">
        <div class="flex flex-wrap gap-2">
            @foreach($categories as $key => $category)
            <button wire:click="switchCategory('{{ $key }}')"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition
                        {{ $activeCategory === $key 
                            ? 'bg-blue-600 text-white shadow-md' 
                            : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700' }}">
                <span>{{ $category['icon'] }}</span>
                <span>{{ $category['name'] }}</span>
            </button>
            @endforeach
        </div>
    </div>

    {{-- Settings Content --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="p-6">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">
                    {{ $categories[$activeCategory]['icon'] }} {{ $categories[$activeCategory]['name'] }}
                </h2>
                <p class="text-zinc-600 dark:text-zinc-400">{{ $categories[$activeCategory]['description'] }}</p>
            </div>

            <form wire:submit="saveSettings" class="space-y-6">
                @switch($activeCategory)
                    @case('general')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_settings.site_name') }}</label>
                                <input wire:model.live="settings.site_name"
                                       type="text"
                                       class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                @error('settings.site_name')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_settings.site_email') }}</label>
                                <input wire:model.live="settings.site_email"
                                       type="email"
                                       class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                @error('settings.site_email')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_settings.site_phone') }}</label>
                                <input wire:model.live="settings.site_phone"
                                       type="tel"
                                       class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_settings.site_address') }}</label>
                                <input wire:model.live="settings.site_address"
                                       type="text"
                                       class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_settings.site_description') }}</label>
                                <textarea wire:model.live="settings.site_description"
                                          rows="3"
                                          class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_settings.default_user_role') }}</label>
                                <select wire:model.live="settings.default_user_role" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                    <option value="winga">{{ __('messages.admin_settings.winga_provider') }}</option>
                                    <option value="mteja">{{ __('messages.admin_settings.muajiri_client') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="border-t border-zinc-200 dark:border-zinc-700 pt-6">
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_settings.registration_settings') }}</h3>
                            <div class="space-y-3">
                                <label class="flex items-center gap-3">
                                    <input wire:model.live="settings.allow_registrations" type="checkbox" class="rounded">
                                    <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ __('messages.admin_settings.allow_registrations') }}</span>
                                </label>
                                <label class="flex items-center gap-3">
                                    <input wire:model.live="settings.require_email_verification" type="checkbox" class="rounded">
                                    <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ __('messages.admin_settings.require_email_verify') }}</span>
                                </label>
                                <label class="flex items-center gap-3">
                                    <input wire:model.live="settings.require_phone_verification" type="checkbox" class="rounded">
                                    <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ __('messages.admin_settings.require_phone_verify') }}</span>
                                </label>
                                <label class="flex items-center gap-3">
                                    <input wire:model.live="settings.maintenance_mode" type="checkbox" class="rounded">
                                    <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ __('messages.admin_settings.maintenance_mode') }}</span>
                                </label>
                            </div>
                        </div>

                    @break
                    @case('financial')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_settings.commission_rate') }}</label>
                                <input wire:model.live="settings.commission_rate"
                                       type="number"
                                       min="0"
                                       max="100"
                                       step="0.1"
                                       class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                @error('settings.commission_rate')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_settings.withdrawal_fee_rate') }}</label>
                                <input wire:model.live="settings.withdrawal_fee_rate"
                                       type="number"
                                       min="0"
                                       max="100"
                                       step="0.1"
                                       class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                @error('settings.withdrawal_fee_rate')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_settings.min_withdrawal') }}</label>
                                <input wire:model.live="settings.min_withdrawal_amount"
                                       type="number"
                                       min="0"
                                       class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                @error('settings.min_withdrawal_amount')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_settings.max_withdrawal') }}</label>
                                <input wire:model.live="settings.max_withdrawal_amount"
                                       type="number"
                                       min="0"
                                       class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                @error('settings.max_withdrawal_amount')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="border-t border-zinc-200 dark:border-zinc-700 pt-6">
                            <label class="flex items-center gap-3">
                                <input wire:model.live="settings.auto_approve_withdrawals" type="checkbox" class="rounded">
                                <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ __('messages.admin_settings.auto_approve_withdrawals') }}</span>
                            </label>
                        </div>

                    @break
                    @case('payment')
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_settings.snippe_gateway') }}</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">API Key</label>
                                        <input wire:model.live="settings.snippe_api_key"
                                               type="password"
                                               class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Secret Key</label>
                                        <input wire:model.live="settings.snippe_secret_key"
                                               type="password"
                                               class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Webhook URL</label>
                                        <input wire:model.live="settings.snippe_webhook_url"
                                               type="url"
                                               class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                    @break
                    @case('email')
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_settings.email_config') }}</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_settings.email_driver') }}</label>
                                        <select wire:model.live="settings.email_driver" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                            <option value="smtp">SMTP</option>
                                            <option value="mailgun">Mailgun</option>
                                            <option value="ses">Amazon SES</option>
                                            <option value="sendmail">Sendmail</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_settings.from_email') }}</label>
                                        <input wire:model.live="settings.mail_from_address"
                                               type="email"
                                               class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_settings.from_name') }}</label>
                                        <input wire:model.live="settings.mail_from_name"
                                               type="text"
                                               class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                    </div>
                                </div>
                            </div>

                            @if($settings['email_driver'] === 'smtp')
                            <div class="border-t border-zinc-200 dark:border-zinc-700 pt-6">
                                <h4 class="text-md font-semibold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_settings.smtp_settings') }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">SMTP Host</label>
                                        <input wire:model.live="settings.mail_host"
                                               type="text"
                                               class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">SMTP Port</label>
                                        <input wire:model.live="settings.mail_port"
                                               type="number"
                                               class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Username</label>
                                        <input wire:model.live="settings.mail_username"
                                               type="text"
                                               class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Password</label>
                                        <input wire:model.live="settings.mail_password"
                                               type="password"
                                               class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Encryption</label>
                                        <select wire:model.live="settings.mail_encryption" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                            <option value="">None</option>
                                            <option value="tls">TLS</option>
                                            <option value="ssl">SSL</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="button" wire:click="testEmailConfiguration"
                                            class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm font-medium transition">
                                        📧 {{ __('messages.admin_settings.send_test_email') }}
                                    </button>
                                </div>
                            </div>
                            @endif
                        </div>

                    @break
                    @case('realtime')
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_settings.pusher_config') }}</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">App ID</label>
                                        <input wire:model.live="settings.pusher_app_id"
                                               type="text"
                                               class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">App Key</label>
                                        <input wire:model.live="settings.pusher_app_key"
                                               type="text"
                                               class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">App Secret</label>
                                        <input wire:model.live="settings.pusher_app_secret"
                                               type="password"
                                               class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">App Cluster</label>
                                        <input wire:model.live="settings.pusher_app_cluster"
                                               type="text"
                                               class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                    @break
                    @case('analytics')
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_settings.analytics_tracking') }}</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Google Analytics ID</label>
                                        <input wire:model.live="settings.google_analytics_id"
                                               type="text"
                                               placeholder="GA-XXXXXXXXX-X"
                                               class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Facebook Pixel ID</label>
                                        <input wire:model.live="settings.facebook_pixel_id"
                                               type="text"
                                               placeholder="XXXXXXXXXXXXXXXXXX"
                                               class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Google Maps API Key</label>
                                        <input wire:model.live="settings.google_maps_api_key"
                                               type="password"
                                               class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-zinc-200 dark:border-zinc-700 pt-6">
                                <h4 class="text-md font-semibold text-zinc-900 dark:text-white mb-4">reCAPTCHA</h4>
                                <div class="space-y-4">
                                    <label class="flex items-center gap-3">
                                        <input wire:model.live="settings.enable_recaptcha" type="checkbox" class="rounded">
                                        <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ __('messages.admin_settings.enable_recaptcha') }}</span>
                                    </label>
                                    @if($settings['enable_recaptcha'])
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Site Key</label>
                                            <input wire:model.live="settings.recaptcha_site_key"
                                                   type="text"
                                                   class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Secret Key</label>
                                            <input wire:model.live="settings.recaptcha_secret_key"
                                                   type="password"
                                                   class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    @break
                    @case('security')
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_settings.security_settings') }}</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_settings.session_lifetime') }}</label>
                                        <input wire:model.live="settings.session_lifetime"
                                               type="number"
                                               min="60"
                                               max="10080"
                                               class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_settings.max_login_attempts') }}</label>
                                        <input wire:model.live="settings.max_login_attempts"
                                               type="number"
                                               min="1"
                                               max="10"
                                               class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_settings.lockout_duration') }}</label>
                                        <input wire:model.live="settings.lockout_duration"
                                               type="number"
                                               min="1"
                                               max="60"
                                               class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_settings.min_password_length') }}</label>
                                        <input wire:model.live="settings.password_min_length"
                                               type="number"
                                               min="6"
                                               max="50"
                                               class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-zinc-200 dark:border-zinc-700 pt-6">
                                <h4 class="text-md font-semibold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_settings.authentication') }}</h4>
                                <div class="space-y-3">
                                    <label class="flex items-center gap-3">
                                        <input wire:model.live="settings.enable_two_factor" type="checkbox" class="rounded">
                                        <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ __('messages.admin_settings.enable_2fa') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                    @break
                    @case('system')
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_settings.system_config') }}</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Cache Driver</label>
                                        <select wire:model.live="settings.cache_driver" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                            <option value="file">File</option>
                                            <option value="redis">Redis</option>
                                            <option value="memcached">Memcached</option>
                                            <option value="database">Database</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Queue Driver</label>
                                        <select wire:model.live="settings.queue_driver" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                            <option value="sync">Sync</option>
                                            <option value="database">Database</option>
                                            <option value="redis">Redis</option>
                                            <option value="beanstalkd">Beanstalkd</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Log Level</label>
                                        <select wire:model.live="settings.log_level" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                            <option value="debug">Debug</option>
                                            <option value="info">Info</option>
                                            <option value="warning">Warning</option>
                                            <option value="error">Error</option>
                                            <option value="critical">Critical</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-zinc-200 dark:border-zinc-700 pt-6">
                                <h4 class="text-md font-semibold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_settings.monitoring_backup') }}</h4>
                                <div class="space-y-3">
                                    <label class="flex items-center gap-3">
                                        <input wire:model.live="settings.enable_performance_monitoring" type="checkbox" class="rounded">
                                        <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ __('messages.admin_settings.enable_perf_monitoring') }}</span>
                                    </label>
                                    <label class="flex items-center gap-3">
                                        <input wire:model.live="settings.enable_error_tracking" type="checkbox" class="rounded">
                                        <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ __('messages.admin_settings.enable_error_tracking') }}</span>
                                    </label>
                                    <label class="flex items-center gap-3">
                                        <input wire:model.live="settings.backup_enabled" type="checkbox" class="rounded">
                                        <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ __('messages.admin_settings.enable_auto_backups') }}</span>
                                    </label>
                                </div>
                                @if($settings['backup_enabled'])
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_settings.backup_frequency') }}</label>
                                        <select wire:model.live="settings.backup_frequency" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                            <option value="daily">Daily</option>
                                            <option value="weekly">Weekly</option>
                                            <option value="monthly">Monthly</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_settings.backup_retention') }}</label>
                                        <input wire:model.live="settings.backup_retention_days"
                                               type="number"
                                               min="1"
                                               max="365"
                                               class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                    @break
                    @case('notifications')
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_settings.notification_settings') }}</h3>
                                <div class="space-y-4">
                                    <label class="flex items-center gap-3">
                                        <input wire:model.live="settings.enable_notifications" type="checkbox" class="rounded">
                                        <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ __('messages.admin_settings.enable_notifications') }}</span>
                                    </label>
                                    <label class="flex items-center gap-3">
                                        <input wire:model.live="settings.enable_sms_notifications" type="checkbox" class="rounded">
                                        <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ __('messages.admin_settings.enable_sms') }}</span>
                                    </label>
                                </div>
                            </div>

                            <div class="border-t border-zinc-200 dark:border-zinc-700 pt-6">
                                <h4 class="text-md font-semibold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_settings.sms_provider') }}</h4>
                                <div class="space-y-4">
                                    <select wire:model.live="settings.sms_provider" class="w-full max-w-xs px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                        <option value="twilio">Twilio</option>
                                        <option value="africastalking">AfricasTalking</option>
                                        <option value="infobip">Infobip</option>
                                    </select>
                                </div>

                                @switch($settings['sms_provider'])
                                    @case('twilio')
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Account SID</label>
                                                <input wire:model.live="settings.twilio_account_sid"
                                                       type="text"
                                                       class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Auth Token</label>
                                                <input wire:model.live="settings.twilio_auth_token"
                                                       type="password"
                                                       class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Phone Number</label>
                                                <input wire:model.live="settings.twilio_phone_number"
                                                       type="tel"
                                                       class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                            </div>
                                        </div>
                                    @break
                                    @endswitch
                            </div>

                            <div class="mt-4">
                                <button type="button" wire:click="testSmsConfiguration"
                                        class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm font-medium transition">
                                    📱 {{ __('messages.admin_settings.send_test_sms') }}
                                </button>
                            </div>
                        </div>

                    @break
                    @case('support')
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_settings.customer_support') }}</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Support Email</label>
                                        <input wire:model.live="settings.support_email"
                                               type="email"
                                               class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Support Phone</label>
                                        <input wire:model.live="settings.support_phone"
                                               type="tel"
                                               class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Help Center URL</label>
                                        <input wire:model.live="settings.help_center_url"
                                               type="url"
                                               class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Ticket Email</label>
                                        <input wire:model.live="settings.ticket_email"
                                               type="email"
                                               class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-zinc-200 dark:border-zinc-700 pt-6">
                                <h4 class="text-md font-semibold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_settings.support_features') }}</h4>
                                <div class="space-y-3">
                                    <label class="flex items-center gap-3">
                                        <input wire:model.live="settings.enable_chat_support" type="checkbox" class="rounded">
                                        <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ __('messages.admin_settings.enable_chat_support') }}</span>
                                    </label>
                                    <label class="flex items-center gap-3">
                                        <input wire:model.live="settings.enable_live_chat" type="checkbox" class="rounded">
                                        <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ __('messages.admin_settings.enable_live_chat') }}</span>
                                    </label>
                                    <label class="flex items-center gap-3">
                                        <input wire:model.live="settings.enable_help_center" type="checkbox" class="rounded">
                                        <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ __('messages.admin_settings.enable_help_center') }}</span>
                                    </label>
                                    <label class="flex items-center gap-3">
                                        <input wire:model.live="settings.enable_faq" type="checkbox" class="rounded">
                                        <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ __('messages.admin_settings.enable_faq') }}</span>
                                    </label>
                                    <label class="flex items-center gap-3">
                                        <input wire:model.live="settings.enable_ticket_system" type="checkbox" class="rounded">
                                        <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ __('messages.admin_settings.enable_ticket_system') }}</span>
                                    </label>
                                </div>
                                @if($settings['enable_live_chat'])
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Chat Widget Position</label>
                                    <select wire:model.live="settings.chat_widget_position" class="w-full max-w-xs px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                        <option value="bottom-right">Bottom Right</option>
                                        <option value="bottom-left">Bottom Left</option>
                                        <option value="top-right">Top Right</option>
                                        <option value="top-left">Top Left</option>
                                    </select>
                                </div>
                                @endif
                            </div>
                        </div>
                    @break
                @endswitch

                <div class="flex justify-end gap-3 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                    <button type="button" wire:click="switchCategory('general')"
                            class="px-4 py-2 bg-zinc-200 hover:bg-zinc-300 text-zinc-700 rounded-lg text-sm font-medium transition">
                        {{ __('messages.admin_settings.cancel') }}
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                        💾 {{ __('messages.admin_settings.save_settings') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- System Information --}}
    @if($activeCategory === 'system')
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6 mt-6">
        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_settings.system_info') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($systemInfo as $key => $value)
            <div class="flex justify-between items-center py-2 border-b border-zinc-100 dark:border-zinc-800">
                <span class="text-sm text-zinc-600 dark:text-zinc-400">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $value }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
