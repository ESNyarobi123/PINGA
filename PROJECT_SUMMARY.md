# WINGA - MFUMO WA AJIRA/FREELANCE MARKETPLACE

## 📋 Muktasari wa Mfumo

**Winga** ni jukwaa la kidigitali (marketplace) linalounganisha **Wafanyakazi** (freelancers/wafundi) na **Waajiri** (wateja) nchini Tanzania. Mfumo huu unafanya kazi kama Upwork/Fiverr lakini umeboreshwa kwa mahitaji ya Tanzania, ikiwa na malipo ya Mobile Money (M-Pesa, TigoPesa, Airtel Money).

---

## 👥 MAJUKUMU (ROLES) YA MFUMO

### 1. MUJILi (Employer/Client)
Mteja anayehitaji kazi kufanywa.

**Kazi Zake:**
- Kusajili akaunti na kukamilisha onboarding
- Kutuma kazi mpya (Post Job)
- Kuangalia maombi ya wafanyakazi
- Kuchagua mfanyakazi kwa kazi
- Kulipa kupitia wallet au mobile money
- Kuweka code ya kukamilisha kazi
- Kuandika reviews kwa wafanyakazi

**Ukurasa Muhimu:**
- `/muajili/dashboard` - Dashibodi
- `/muajili/post-kazi` - Tuma Kazi
- `/muajili/kazi-zangu` - Kazi Zangu
- `/muajili/maombi` - Maombi ya Wafanyakazi
- `/muajili/wallet` - Wallet yangu
- `/muajili/smart-match` - Smart Match (Kupata wafanyakazi sahihi)

---

### 2. MFANYAKAZI (Worker/Freelancer)
Mfanyakazi au mfundi anayetafuta kazi.

**Kazi Zake:**
- Kusajili akaunti na kukamilisha onboarding
- Kutafuta kazi karibu au kwa category
- Kuomba kazi (Apply)
- Kuweka code ya kukamilisha kazi
- Kupokea malipo kupitia wallet
- Kuandika reviews kwa waajiri
- Kuupdate portfolio

**Ukurasa Muhimu:**
- `/mfanyakazi/dashboard` - Dashibodi
- `/mfanyakazi/kazi-karibu` - Kazi Karibu (kwa ramani)
- `/mfanyakazi/maombi-yangu` - Maombi Yangu
- `/mfanyakazi/portfolio` - Portfolio
- `/mfanyakazi/mapato` - Mapato
- `/mfanyakazi/weka-code` - Weka Code ya Kumaliza

---

### 3. ADMIN (System Administrator)
Msimamizi wa mfumo.

**Kazi Zake:**
- Kuona dashibodi ya takwimu
- Kusimamia watumiaji (wafanyakazi & waajiri)
- Kusimamia kazi zote
- Kusimamia malipo na escrow
- Kutatua migogoro (disputes)
- Kusimamia kategoria na skills

**Ukurasa Muhimu:**
- `/admin/dashboard` - Dashibodi ya Admin
- `/admin/watumiaji` - Watumiaji Wote
- `/admin/kazi` - Kazi Zote
- `/admin/malipo` - Malipo Yote
- `/admin/migogoro` - Migogoro
- `/admin/kategoria` - Kategoria

---

## 🔄 MFUMO WA KAZI (WORKFLOW)

### Flow 1: Usajili na Onboarding

```
1. Mtumiaji anasajili (Register)
   └── Anachagua Role: Muajili AU Mfanyakazi
       
2. Email Verification (Laravel Fortify)
   
3. Phone Verification (OTP)
   └── SMS ya 6-digit code inatumwa
       
4. Onboarding (kulingana na role)
   ├── Muajili: Anajaza taarifa za biashara/personal
   └── Mfanyakazi: Anajaza skills, location, bei, uzoefu
       
5. Akaunti iko tayari kutumika!
```

### Flow 2: Kutuma Kazi (Employer)

```
1. Muajili anaingia /muajili/post-kazi
   
2. Anajaza fomu:
   ├── Title (Jina la kazi)
   ├── Description (Maelezo)
   ├── Category (Kategoria)
   ├── Location (Eneo)
   ├── Budget Type: fixed/hourly
   ├── Budget Min/Max
   ├── Urgency: normal/urgent/very_urgent
   └── Skills zinazohitajika
   
3. Kazi ina-hifadhiwa na kuwa "open"
   
4. System ina-match na wafanyakazi sahihi (Smart Match)
   
5. Wafanyakazi wanaona kazi na kuomba
```

### Flow 3: Kuomba Kazi (Worker)

```
1. Mfanyakazi anaingia /mfanyakazi/kazi-karibu
   
2. Anaona kazi zilizopo karibu naye (kwa ramani au list)
   
3. Ana-chagua kazi na kuomba (Apply)
   ├── Anajaza cover letter
   ├── Anaweka proposed budget
   └── Anaweka proposed duration
   
4. Muajili anaona maombi yake /muajili/maombi
   
5. Muajili anaweza:
   ├── Kukubali (Accept) → Kazi inakuwa "in_progress"
   ├── Kukataa (Reject)
   └── Kuuliza maswali (via Messages)
```

### Flow 4: Malipo na Escrow

```
1. Muajili anakubali mfanyakazi
   
2. System inaomba malipo (Payment)
   ├── Muajili anachagua payment method:
   │   ├── Mobile Money (M-Pesa, TigoPesa, Airtel Money)
   │   └── Card Payment (Visa/Mastercard)
   │
   └── Kiasi kinacholipiwa = Budget + Platform Fee
   
3. Pesa inaenda kwenye ESCROW (kama "kumbi" ya mfumo)
   └── Status: "escrowed"
   
4. Mfanyakazi anaanza kazi
   
5. Kazi inapomalizika:
   ├── Mfanyakazi anaingia /mfanyakazi/weka-code
   ├── Anaomba code kutoka kwa Muajili
   ├── Anaingiza 6-digit code
   └── Code ina-verify → Kazi "completed"
   
6. Pesa inatoka Escrow inaenda kwa Mfanyakazi
   └── Platform fee inakatwa automatically
   
7. Wote wanaandika reviews
```

### Flow 5: Wallet na Kutoa Pesa

```
1. Mfanyakazi ana-balance yake kwenye wallet
   
2. Anaweza:
   ├── Kuona transaction history /mfanyakazi/mapato
   └── Ku-request withdrawal (kwa admin)
   
3. Muajili ana-weka pesa kwenye wallet:
   └── /muajili/wallet → Deposit
       
4. Admin anasimamia withdrawals /admin/malipo
```

---

## 💰 MFUMO WA MALIPO

### Payment Gateway: Snippe API
- **Mobile Money**: M-Pesa, TigoPesa, Airtel Money (USSD Push)
- **Card Payments**: Visa/Mastercard (3D Secure)
- **Currency**: TZS (Tanzania Shillings)

### Aina za Malipo:

| Aina | Maelezo |
|------|---------|
| **Deposit** | Muajili anaweka pesa kwenye wallet |
| **Escrow** | Pesa inahifadhiwa na mfumo hadi kazi kukamilika |
| **Release** | Pesa inatoka escrow kwa mfanyakazi |
| **Withdrawal** | Mfanyakazi anatoa pesa kutoka wallet |
| **Refund** | Pesa inarudishwa kwa muajili kama kazi ikakatwa |

### Transaction Table Schema:
```
transactions
├── user_id
├── payment_id (optional)
├── type: credit|debit|withdrawal|deposit
├── amount
├── description
├── balance_after
└── reference
```

---

## 🧠 SMART MATCHING ALGORITHM

Algorithm ina-rank wafanyakazi kwa kazi fulani kulingana na:

### Scoring Criteria (100 points max):

| Kigezo | Alama | Maelezo |
|--------|-------|---------|
| **Skills Match** | 40 | Idadi ya skills zinazoaniana |
| **Rating** | 25 | Wastani wa stars (1-5) |
| **Location** | 20 | Umbali kati ya mfanyakazi na kazi |
| **Experience** | 10 | Miaka ya uzoefu |
| **Completed Jobs** | 5 | Idadi ya kazi zilizokamilika |

### Mfano wa Output:
```json
{
  "user": {...},
  "score": 87.5,
  "reasons": [
    "Skills 3/4 zinaoanisha",
    "Rating ya juu: ⭐ 4.8",
    "Karibu sana (~2.5km)",
    "Uzoefu wa miaka 5"
  ],
  "matched_skills": ["Ufundi", "Umeme", "Plumbing"],
  "distance_label": "2.5km"
}
```

---

## 📊 DATABASE SCHEMA (Jadwali Muhimu)

### 1. users
- Basic info (name, email, phone)
- Location (latitude, longitude, mkoa, wilaya, mtaa)
- Wallet balance
- Role (muajili/mfanyakazi/admin)
- Onboarding status
- OTP fields

### 2. job_listings (Kazi)
- employer_id
- title, description, slug
- category_id
- location, latitude, longitude
- budget_min, budget_max, budget_type
- status: draft|open|in_progress|completed|cancelled|disputed
- urgency: normal|urgent|very_urgent
- completion_code (hashed)
- hired_worker_id

### 3. applications (Maombi)
- job_id, worker_id
- cover_letter
- proposed_budget, proposed_duration
- status: pending|accepted|rejected|withdrawn

### 4. payments
- job_id, employer_id, worker_id
- amount, platform_fee, worker_amount
- status: pending|escrowed|released|refunded|disputed
- payment_method, payment_reference

### 5. conversations & messages
- Mfumo wa chat kati ya muajili na mfanyakazi

### 6. reviews
- job_id, reviewer_id, reviewee_id
- rating (1-5), comment

---

## 🔐 MFUMO WA USALAMA

### Authentication:
- **Laravel Fortify** - Authentication scaffolding
- **Two-Factor Auth** - 2FA support
- **OTP Verification** - 6-digit SMS code

### Authorization:
- **Spatie Laravel Permission** - Roles & permissions
- **Middleware** - Role-based access control

### Kazi Zilizolindwa:
- HTTPS only (TLS 1.2+)
- Password hashing (bcrypt)
- CSRF protection
- Rate limiting

---

## 📱 LIVEWIRE COMPONENTS

### Public (Hakuna Uthibitisho):
- `TafutaKazi` - Utafutaji wa kazi
- `TafutaWafanyakazi` - Utafutaji wa wafanyakazi
- `KaziDetail` - Maelezo ya kazi
- `WafanyakaziProfile` - Wasifu wa mfanyakazi
- `FeaturedWingas` - Wafanyakazi waliobora

### Auth (Inahitaji Uthibitisho):
- `Login`, `Register`, `VerifyOtp`

### Onboarding:
- `MuajiliOnboarding` - Usajili wa muajili
- `MfanyakaziOnboarding` - Usajili wa mfanyakazi

### Muajili (Employer):
- `Dashboard` - Muhtasari
- `PostKazi` - Tuma kazi
- `KaziZangu` - Orodha ya kazi
- `Maombi` - Maombi ya wafanyakazi
- `Wallet` - Wallet na malipo
- `SmartMatch` - Algorithm ya matching
- `Analytics` - Takwimu na ripoti

### Mfanyakazi (Worker):
- `Dashboard` - Muhtasari
- `KaziKaribu` - Kazi karibu na wewe
- `MaombiYangu` - Maombi yako
- `Portfolio` - Portfolio yako
- `Mapato` - Mapato na transaction history
- `WekaCode` - Weka code ya kukamilisha
- `Ramani` - Ramani ya kazi

### Admin:
- `Dashboard` - Takwimu za mfumo
- `Watumiaji` - Usimamizi wa watumiaji
- `Kazi` - Usimamizi wa kazi
- `Malipo` - Usimamizi wa malipo
- `Migogoro` - Kutatua migogoro
- `Kategoria` - Usimamizi wa kategoria

### Shared:
- `Messages` - Chat/meseji kati ya watumiaji

---

## 🔧 SERVICES (Business Logic)

### 1. SmartMatchingService
Algorithm ya kupanga wafanyakazi kwa kazi.

### 2. SnippePaymentService
Mchanganuo wa malipo kupitia Snippe API.

### 3. KaziListingService
Kudhibiti orodha na utafutaji wa kazi.

### 4. WafanyakaziListingService
Kudhibiti orodha na utafutaji wa wafanyakazi.

### 5. MuajiliDashboardService
Kudhibiti takwimu za dashibodi ya muajili.

### 6. MfanyakaziDashboardService
Kudhibiti takwimu za dashibodi ya mfanyakazi.

### 7. AdminDashboardService
Kudhibiti takwimu za dashibodi ya admin.

---

## 🚀 API INTEGRATIONS

### 1. Snippe Payment API
```
Base URL: https://api.snippe.sh/v1
Endpoints:
- POST /payments - Create payment
- Webhook: /api/webhooks/snippe
```

### 2. SMS Gateway (OTP)
- Inatumia Snippe au mtoa huduma mwingine wa SMS

---

## 🛠️ TECH STACK

| Technology | Version | Matumizi |
|------------|---------|----------|
| PHP | 8.5.3 | Backend language |
| Laravel | 12 | Web framework |
| Livewire | 4 | Reactive components |
| Flux UI | 2 | Component library |
| Tailwind CSS | 4 | Styling |
| Spatie Permission | 7 | Roles & permissions |
| SQLite | - | Database |
| Snippe API | - | Payments |

---

## 📁 MUUNDO WA PROJECT

```
winga/
├── app/
│   ├── Livewire/          # Components zote za UI
│   │   ├── Admin/
│   │   ├── Muajili/
│   │   ├── Mfanyakazi/
│   │   ├── Public/
│   │   └── Shared/
│   ├── Models/            # Eloquent models
│   ├── Services/          # Business logic
│   └── Http/              # Controllers & Requests
├── database/
│   └── migrations/        # Database schema
├── resources/
│   └── views/             # Blade templates
├── routes/
│   └── web.php            # Routes zote
└── config/
    └── services.php       # Snippe API config
```

---

## ✅ FEATURES ZILIZO IMPLEMENTED

### Core Features:
- [x] Usajili na login (Email + Password)
- [x] Phone verification (OTP)
- [x] Onboarding flow (waajiri & wafanyakazi)
- [x] Job posting (waajiri)
- [x] Job application (wafanyakazi)
- [x] Smart matching algorithm
- [x] Messaging system (chat) — gated behind accepted+paid application
- [x] Payment integration (Snippe)
- [x] Escrow system
- [x] Wallet management
- [x] Reviews & ratings
- [x] Location-based job search
- [x] Admin dashboard
- [x] Portfolio management
- [x] Category & skills management

### 🏠 Home Page:
- [x] Slideshow/carousel ya Winga waliochaguliwa (Featured Wingas)
- [x] Featured cards zinaonyesha tu Winga wenye subscription inayofanya kazi
- [x] Cards zinabadilika kila ukurasa unapopakiwa (random order)

### 🔐 Security & Business Rules:
- [x] Kuzuia namba za simu kwenye maelezo ya kazi (regex blocking)
- [x] Admin approval queue kwa kazi mpya zote
- [x] WhatsApp ya lazima kwa Winga wakati wa usajili
- [x] Mkoa/Wilaya dropdowns wakati wa usajili
- [x] Chat inaanza tu baada ya malipo kukubaliwa
- [x] 12-hour code hold kwa muajili asiyeridhika

### 💰 Mfumo wa Mapato (Withdrawal):
- [x] Mfanyakazi anaweza kuomba kutoa fedha (TombaOmbi)
- [x] Admin anaweza kukubali/kukataa/kulipa maombi ya kutoa fedha
- [x] Taarifa za barua pepe/simu kwa hali ya ombi

### 👤 Subscription Plans:
- [x] Mfumo wa subscription kwa Winga (Winga Bora badge)
- [x] Featured listing kwenye home page kwa wenye subscription

### 🛡️ Admin Features:
- [x] Admin approval queue kwa kazi (approve/reject)
- [x] Admin Watumiaji: tabs za Winga / Wateja na hesabu
- [x] Admin Mazungumzo: angalia mazungumzo yote
- [x] Admin MaombiKutoa: simamia maombi ya kutoa fedha

### Payment Methods:
- [x] M-Pesa (Tanzania)
- [x] TigoPesa (Tanzania)
- [x] Airtel Money (Tanzania)
- [x] Card payments (Visa/Mastercard)

---

## 🎯 KAZI ZILIZOBAKI (TODO)

- [ ] Notifications (real-time)
- [ ] Email notifications
- [ ] SMS notifications
- [ ] Mobile App (Flutter/React Native)
- [ ] Advanced search filters
- [ ] API for third-party integrations
- [ ] Analytics & reporting za advanced
- [ ] Subscription payment flow (online payment to activate Winga Bora badge)
- [ ] Multi-language support (Swahili & English)

---

## 📞 MAWASILIANO YA MFUMO

- **Production URL**: https://winga.ericksky.online
- **Admin Email**: admin@winga.com
- **Support**: support@winga.com

---

*Document Imetengenezwa: March 2026*
*Mwandishi: System Analysis*
*Lugha: Kiswahili & English*
