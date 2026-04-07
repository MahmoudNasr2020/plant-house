<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم') — Plant House Admin</title>

    @php $adminFavicon = \App\Models\Setting::get('store_favicon'); @endphp
    @if($adminFavicon)
        <link rel="icon" type="image/png" href="{{ $adminFavicon }}">
        <link rel="shortcut icon" href="{{ $adminFavicon }}">
    @endif

    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --gd: #1a3a2a;
            --gm: #2d6a4f;
            --gb: #40916c;
            --gl: #74c69d;
            --gp: #b7e4c7;
            --gf: #d8f3dc;
            --gold: #f4a261;
            --red: #e63946;
            --sh: 0 4px 24px rgba(26, 58, 42, .10);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Tajawal', sans-serif;
            background: #f8fdf9;
            color: #0d2318;
            direction: rtl;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* ───── CONTENT AREA ───── */
        .content {
            flex: 1;
            overflow-y: auto;
        }

        .cpanel {
            padding: 24px 26px;
        }

        /* ───── TOPBAR ───── */
        .ctopbar {
            background: #fff;
            padding: 14px 26px;
            border-bottom: 2px solid var(--gf);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .ctopbar h2 {
            font-size: 22px;
            font-weight: 900;
            color: var(--gd);
        }

        .ctopbar-actions {
            display: flex;
            gap: 9px;
        }

        /* ───── BUTTONS ───── */
        .btn-p {
            background: var(--gd);
            color: #fff;
            border: none;
            padding: 9px 18px;
            border-radius: 9px;
            font-family: inherit;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: .2s;
            text-decoration: none;
        }

        .btn-p:hover { background: var(--gb); }

        .btn-s {
            background: #fff;
            color: #2d4a3a;
            border: 2px solid var(--gf);
            padding: 9px 18px;
            border-radius: 9px;
            font-family: inherit;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: .2s;
            text-decoration: none;
        }

        .btn-s:hover { border-color: var(--gb); color: var(--gd); }

        /* ───── KPI CARDS ───── */
        .kpis {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 24px;
        }

        .kcard {
            background: #fff;
            border-radius: 14px;
            padding: 18px 20px;
            box-shadow: var(--sh);
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .kcdr { display: flex; align-items: center; justify-content: space-between; }

        .kico {
            width: 42px;
            height: 42px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 19px;
        }

        .kg { background: var(--gf); }
        .kb { background: #e0f0ff; }
        .ko { background: #fff0eb; }
        .kv { background: #f3f0ff; }

        .kchg {
            font-size: 11.5px;
            font-weight: 700;
            padding: 3px 7px;
            border-radius: 50px;
        }

        .kup { background: #e6f9ee; color: #1a7a45; }
        .kdn { background: #fff0f0; color: #c0392b; }
        .kval { font-size: 26px; font-weight: 900; color: #0d2318; }
        .klbl { font-size: 12.5px; color: #9aa89e; font-weight: 600; }

        /* ───── CHARTS ───── */
        .charts {
            display: grid;
            grid-template-columns: 1.6fr 1fr;
            gap: 14px;
            margin-bottom: 24px;
        }

        .ccard {
            background: #fff;
            border-radius: 14px;
            padding: 20px;
            box-shadow: var(--sh);
        }

        .ccard h4 {
            font-size: 14px;
            font-weight: 800;
            color: #0d2318;
            margin-bottom: 16px;
        }

        .barchart {
            display: flex;
            align-items: flex-end;
            gap: 7px;
            height: 130px;
        }

        .bw {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            height: 100%;
        }

        .bar {
            width: 100%;
            background: linear-gradient(180deg, var(--gb), var(--gd));
            border-radius: 5px 5px 0 0;
            transition: .5s;
            cursor: pointer;
            position: relative;
        }

        .bar:hover::after {
            content: attr(data-v);
            position: absolute;
            top: -26px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--gd);
            color: #fff;
            font-size: 10.5px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 5px;
            white-space: nowrap;
        }

        .blbl { font-size: 10.5px; color: #9aa89e; font-weight: 600; white-space: nowrap; }

        .donut-w { display: flex; justify-content: center; align-items: center; gap: 22px; }
        .dleg { display: flex; flex-direction: column; gap: 9px; }
        .di { display: flex; align-items: center; gap: 7px; font-size: 12px; font-weight: 600; color: #2d4a3a; }
        .dd { width: 11px; height: 11px; border-radius: 3px; flex-shrink: 0; }

        /* ───── TABLE CARD ───── */
        .tcard {
            background: #fff;
            border-radius: 14px;
            box-shadow: var(--sh);
            overflow: hidden;
            margin-bottom: 18px;
        }

        .thdr {
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid var(--gf);
        }

        .thdr h4 { font-size: 14px; font-weight: 800; color: #0d2318; }
        .thdr-acts { display: flex; gap: 7px; }

        .tsrch, .tfltr {
            border: 2px solid var(--gp);
            border-radius: 7px;
            padding: 7px 11px;
            font-family: inherit;
            font-size: 13px;
            outline: none;
            transition: .2s;
        }

        .tsrch:focus, .tfltr:focus { border-color: var(--gb); }
        .tfltr { background: #fff; cursor: pointer; }

        .dtbl { width: 100%; border-collapse: collapse; }

        .dtbl th {
            background: var(--gd);
            color: #fff;
            padding: 11px 14px;
            text-align: right;
            font-size: 12.5px;
            font-weight: 700;
            white-space: nowrap;
        }

        .dtbl td {
            padding: 11px 14px;
            border-bottom: 1px solid var(--gf);
            font-size: 13px;
            vertical-align: middle;
        }

        .dtbl tr:hover td { background: #f8fdf9; }
        .dtbl tr:last-child td { border-bottom: none; }

        /* ───── BADGES ───── */
        .sbadge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 9px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
        }

        .sd { background: #e6f9ee; color: #1a7a45; }
        .sp { background: #fff7e0; color: #996600; }
        .spr2 { background: #e0f0ff; color: #004aa8; }
        .sc { background: #fff0f0; color: #c0392b; }

        /* ───── ACTION ICONS ───── */
        .aico {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 15px;
            padding: 3px 5px;
            border-radius: 5px;
            transition: .14s;
        }

        .aico:hover { background: #f1f5f2; }

        /* ───── PRODUCT CELL ───── */
        .pcell { display: flex; align-items: center; gap: 9px; }
        .pcell img { width: 40px; height: 40px; border-radius: 7px; object-fit: contain; background: var(--gf); padding: 3px; }
        .pcell .pn { font-weight: 700; font-size: 12.5px; }
        .pcell .pb { font-size: 10.5px; color: #9aa89e; }

        /* ───── PAGINATION ───── */
        .pgn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 20px;
            border-top: 2px solid var(--gf);
        }

        .pgn-info { font-size: 12.5px; color: #9aa89e; }
        .pgn-btns { display: flex; gap: 5px; }

        .pb2 {
            width: 30px;
            height: 30px;
            border: 2px solid var(--gp);
            border-radius: 7px;
            background: #fff;
            cursor: pointer;
            font-family: inherit;
            font-size: 12.5px;
            font-weight: 700;
            color: #2d4a3a;
            transition: .2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pb2:hover, .pb2.on { background: var(--gd); color: #fff; border-color: var(--gd); }

        /* ───── FORM SECTION ───── */
        .fsec {
            background: #fff;
            border-radius: 16px;
            padding: 22px;
            box-shadow: var(--sh);
            margin-bottom: 16px;
        }

        .fsec h3 {
            font-size: 15px;
            font-weight: 800;
            color: var(--gd);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .fsec h3 i { color: var(--gb); }

        .frow {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 11px;
            margin-bottom: 11px;
        }

        .fg { display: flex; flex-direction: column; gap: 5px; margin-bottom: 11px; }
        .fg label { font-size: 12px; font-weight: 700; color: #2d4a3a; }

        .fg input, .fg select, .fg textarea {
            border: 2px solid var(--gp);
            border-radius: 8px;
            padding: 9px 12px;
            font-family: inherit;
            font-size: 13.5px;
            outline: none;
            transition: .2s;
            background: #fff;
        }

        .fg input:focus, .fg select:focus, .fg textarea:focus { border-color: var(--gb); }
        .fg textarea { resize: vertical; min-height: 70px; }

        /* ───── MODAL ───── */
        .mov {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .5);
            z-index: 3000;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .mov.on { display: flex; }

        .modal {
            background: #fff;
            border-radius: 22px;
            padding: 28px;
            width: 640px;
            max-height: 88vh;
            overflow-y: auto;
            position: relative;
        }

        .modal-title {
            font-size: 19px;
            font-weight: 900;
            color: var(--gd);
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .mcls {
            position: absolute;
            top: 18px;
            left: 18px;
            width: 34px;
            height: 34px;
            background: #f1f5f2;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            font-size: 17px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ───── RESPONSIVE ───── */
        @media (max-width: 900px) {
            .kpis { grid-template-columns: repeat(2, 1fr); }
            .charts { grid-template-columns: 1fr; }
        }
    </style>

    @stack('styles')
</head>
<body>
<div class="layout">

    {{-- SIDEBAR --}}
    @include('admin.layouts.partials.sidebar')

    {{-- MAIN CONTENT --}}
    <div class="content">

        {{-- TOPBAR --}}
        @include('admin.layouts.partials.topbar')

        {{-- PAGE CONTENT --}}
        <div class="cpanel">
            @yield('content')
        </div>

    </div>
</div>

{{-- TOAST --}}
@include('admin.layouts.partials.toast')

{{-- PRODUCT MODAL (shared across pages) --}}
@stack('modals')

@stack('scripts')
</body>
</html>
