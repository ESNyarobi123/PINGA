# Winga System - Complete Architecture & Folder Structure
**Tech Stack**: Laravel 11 + Livewire 3 + Flux UI + Tailwind CSS + Spatie Permissions  
**Lugha**: Kiswahili 100% (default)  
**Malipo**: M-Pesa, Tigo Pesa, Airtel Money (via Selcom/ClickPesa)  
**Version**: 1.0 (MVP)

---

## 1. Folder Structure (Laravel + Livewire)

```bash
winga/
├── app/
│   ├── Livewire/
│   │   ├── Auth/
│   │   │   ├── Login.php
│   │   │   ├── Register.php
│   │   │   └── RoleSelection.php
│   │   ├── Onboarding/
│   │   │   ├── MuajiliOnboarding.php
│   │   │   └── MfanyakaziOnboarding.php
│   │   ├── Public/
│   │   │   ├── WelcomePage.php
│   │   │   ├── TafutaKazi.php
│   │   │   ├── TafutaWafanyakazi.php
│   │   │   └── JobDetails.php
│   │   ├── Muajili/
│   │   │   ├── Dashboard.php
│   │   │   ├── PostKazi.php
│   │   │   ├── MaombiList.php
│   │   │   ├── KaziZangu.php
│   │   │   └── ToaCode.php
│   │   ├── Mfanyakazi/
│   │   │   ├── Dashboard.php
│   │   │   ├── KaziKaribuYangu.php
│   │   │   ├── MaombiYangu.php
│   │   │   ├── WekaCode.php
│   │   │   └── Portfolio.php
│   │   └── Admin/
│   │       ├── Dashboard.php
│   │       ├── Users.php
│   │       ├── KaziZote.php
│   │       ├── Disputes.php
│   │       └── Analytics.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Job.php
│   │   ├── Application.php
│   │   ├── Payment.php
│   │   ├── Category.php
│   │   ├── Skill.php
│   │   └── Transaction.php
│   ├── Enums/
│   │   └── UserRole.php          # Muajili, Mfanyakazi, Admin
│   └── Providers/
│       └── AppServiceProvider.php
│
├── resources/
│   ├── views/
│   │   ├── livewire/             # (Livewire views zitawekwa hapa)
│   │   ├── layouts/
│   │   │   ├── app.blade.php
│   │   │   └── guest.blade.php
│   │   └── components/           # Flux components
│   ├── css/
│   │   └── app.css               # (Tailwind + Winga palette)
│   └── js/
│       └── app.js
│
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── create_jobs_table.php
│   │   ├── create_applications_table.php
│   │   ├── create_payments_table.php
│   │   └── create_transactions_table.php
│   └── seeders/
│       └── RoleSeeder.php
│
├── routes/
│   ├── web.php
│   └── livewire.php              # (optional)
│
├── config/
│   └── flux.php
│
├── composer.json
├── package.json
└── tailwind.config.js