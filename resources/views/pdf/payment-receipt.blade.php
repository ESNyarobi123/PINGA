<!DOCTYPE html>
<html lang="sw">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Winga Receipt #{{ $payment->id }}</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, Arial, sans-serif; background: #fff; color: #1a1a2e; font-size: 12px; }
    .page { padding: 40px; max-width: 750px; margin: 0 auto; }

    /* Header */
    .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 3px solid #0d9488; padding-bottom: 20px; margin-bottom: 30px; }
    .brand { }
    .brand-name { font-size: 28px; font-weight: 900; color: #0d9488; letter-spacing: -1px; }
    .brand-sub { color: #64748b; font-size: 11px; margin-top: 2px; }
    .receipt-label { text-align: right; }
    .receipt-label h2 { font-size: 18px; font-weight: 700; color: #1a1a2e; }
    .receipt-label p { color: #64748b; font-size: 11px; margin-top: 3px; }

    /* Status Badge */
    .status-badge { display: inline-block; padding: 4px 14px; background: #dcfce7; color: #16a34a; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }

    /* Parties */
    .parties { display: flex; gap: 20px; margin-bottom: 25px; }
    .party-box { flex: 1; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 15px; }
    .party-box h4 { font-size: 10px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
    .party-box .name { font-size: 14px; font-weight: 700; color: #0f172a; }
    .party-box .detail { color: #64748b; font-size: 11px; margin-top: 2px; }

    /* Job Section */
    .section { margin-bottom: 20px; }
    .section-title { font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; margin-bottom: 10px; font-weight: 600; }
    .job-box { background: #f0fdfa; border: 1px solid #99f6e4; border-radius: 10px; padding: 15px; }
    .job-box .job-title { font-size: 14px; font-weight: 700; color: #0f172a; }
    .job-box .job-detail { color: #64748b; font-size: 11px; margin-top: 4px; }

    /* Payment Table */
    .payment-table { width: 100%; border-collapse: collapse; }
    .payment-table tr { border-bottom: 1px solid #f1f5f9; }
    .payment-table td { padding: 10px 5px; font-size: 12px; }
    .payment-table td:last-child { text-align: right; font-weight: 600; }
    .payment-table .total-row td { font-size: 14px; font-weight: 800; color: #0d9488; border-top: 2px solid #0d9488; border-bottom: none; padding-top: 12px; }
    .payment-table .fee-row td { color: #ef4444; font-size: 11px; }

    /* Footer */
    .footer { text-align: center; border-top: 1px solid #e2e8f0; padding-top: 20px; margin-top: 30px; color: #94a3b8; font-size: 10px; }
    .footer .website { color: #0d9488; font-weight: 700; }

    /* Watermark */
    .verified-stamp { text-align: center; margin: 20px 0; }
    .verified-stamp .stamp { display: inline-block; border: 3px solid #0d9488; color: #0d9488; padding: 6px 20px; border-radius: 6px; font-size: 16px; font-weight: 900; transform: rotate(-5deg); letter-spacing: 3px; opacity: 0.8; }
</style>
</head>
<body>
<div class="page">
    {{-- Header --}}
    <div class="header">
        <div class="brand">
            <div class="brand-name">WINGA</div>
            <div class="brand-sub">Jukwaa la Kazi Tanzania</div>
        </div>
        <div class="receipt-label">
            <h2>RISITI YA MALIPO</h2>
            <p>Nambari: #WNG-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</p>
            <p>Tarehe: {{ $payment->escrow_released_at?->format('d M Y, H:i') ?? now()->format('d M Y') }}</p>
            <br>
            <span class="status-badge">✓ IMETHIBITISHWA</span>
        </div>
    </div>

    {{-- Parties --}}
    <div class="parties">
        <div class="party-box">
            <h4>Muajili (Aliyetoa Malipo)</h4>
            <div class="name">{{ $payment->employer?->name ?? '—' }}</div>
            <div class="detail">📧 {{ $payment->employer?->email ?? '—' }}</div>
            <div class="detail">📞 {{ $payment->employer?->phone ?? '—' }}</div>
        </div>
        <div class="party-box">
            <h4>Mfanyakazi (Aliyepokea)</h4>
            <div class="name">{{ $worker->name }}</div>
            <div class="detail">📧 {{ $worker->email }}</div>
            <div class="detail">📞 {{ $worker->phone ?? '—' }}</div>
        </div>
    </div>

    {{-- Job Info --}}
    <div class="section">
        <div class="section-title">Maelezo ya Kazi</div>
        <div class="job-box">
            <div class="job-title">{{ $payment->job?->title ?? 'Kazi ya Winga' }}</div>
            <div class="job-detail">📍 Eneo: {{ $payment->job?->location ?? '—' }}</div>
            <div class="job-detail">📊 Hali: Imekamilika</div>
            @if($payment->payment_reference)
            <div class="job-detail">🔗 Kumbukumbu: {{ $payment->payment_reference }}</div>
            @endif
        </div>
    </div>

    {{-- Payment Breakdown --}}
    <div class="section">
        <div class="section-title">Muhtasari wa Malipo</div>
        <table class="payment-table">
            <tr>
                <td>Jumla ya Malipo</td>
                <td>TZS {{ number_format($payment->amount, 2) }}</td>
            </tr>
            <tr class="fee-row">
                <td>Makato ya Jukwaa (12% - Winga)</td>
                <td>- TZS {{ number_format($payment->platform_fee, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>MFANYAKAZI AMEPOKEA</td>
                <td>TZS {{ number_format($payment->worker_amount, 2) }}</td>
            </tr>
        </table>
    </div>

    {{-- Verified Stamp --}}
    <div class="verified-stamp">
        <div class="stamp">IMEIDHINISHWA</div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>Risiti hii ni ya kisheria na imetolewa kiotomatiaka na mfumo wa Winga.</p>
        <p>Maswali: <span class="website">support@winga.co.tz</span> | <span class="website">www.winga.co.tz</span></p>
        <p style="margin-top:6px">© {{ date('Y') }} Winga Tanzania Ltd. Haki zote zimehifadhiwa.</p>
    </div>
</div>
</body>
</html>
