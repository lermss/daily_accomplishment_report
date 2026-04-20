@extends('admin.layouts.app')

@section('title', $title)
@section('body_class', 'admin-reminders-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v={{ filemtime(public_path('css/dashboard.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/shared-dashboard-theme.css') }}?v={{ filemtime(public_path('css/shared-dashboard-theme.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/shared-navbar.css') }}?v={{ filemtime(public_path('css/shared-navbar.css')) }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .admin-reminders-page {
            background: #f4f6f9;
        }

        .admin-reminders-page .dashboard-shell {
            background: #f4f6f9;
        }

        .admin-reminders-page .dashboard-content {
            display: grid;
            gap: 24px;
            padding: 30px 60px 38px;
        }

        .admin-reminders-page .page-intro {
            margin-bottom: 0;
        }

        .admin-reminders-page .page-pill {
            background: #ffffff;
            color: #1f4e79;
            border: 1px solid rgba(31, 78, 121, 0.08);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
        }

        .admin-reminders-page .summary-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            display: grid;
            gap: 22px;
            margin-bottom: 0;
        }

        .admin-reminders-page .summary-card {
            position: relative;
            padding: 22px;
            border-radius: 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            background: linear-gradient(135deg, #ffffff, #f8fafc);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            border: 0;
        }

        .admin-reminders-page .summary-card::after {
            content: "";
            position: absolute;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            top: -30px;
            right: -30px;
            opacity: 0.15;
        }

        .admin-reminders-page .summary-card-violet::after {
            background: #6366f1;
        }

        .admin-reminders-page .summary-card-green::after {
            background: #22c55e;
        }

        .admin-reminders-page .summary-card-orange::after {
            background: #f59e0b;
        }

        .admin-reminders-page .summary-label {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 6px;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .admin-reminders-page .summary-value-row {
            display: flex;
            align-items: baseline;
            gap: 10px;
            margin-bottom: 6px;
        }

        .admin-reminders-page .summary-value-row strong {
            font-size: 34px;
            line-height: 1.1;
            font-weight: 700;
            color: #111827;
        }

        .admin-reminders-page .summary-value-row span,
        .admin-reminders-page .summary-meta {
            font-size: 13px;
            color: #6b7280;
        }

        .admin-reminders-page .summary-card p {
            margin-top: 10px;
        }

        .admin-reminders-page .summary-icon {
            width: 52px;
            height: 52px;
            min-width: 52px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            position: relative;
            z-index: 1;
        }

        .admin-reminders-page .summary-icon svg {
            width: 22px;
            height: 22px;
            fill: currentColor;
            flex: none;
        }

        .admin-reminders-page .summary-icon-violet {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
        }

        .admin-reminders-page .summary-icon-green {
            background: linear-gradient(135deg, #22c55e, #16a34a);
        }

        .admin-reminders-page .summary-icon-orange {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .reminders-layout {
            display: grid;
            gap: 24px;
            grid-template-columns: minmax(0, 1.3fr) minmax(320px, 0.9fr);
            align-items: start;
        }

        .reminder-panel {
            background: #fff;
            border-radius: 24px;
            padding: 28px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
            border: 1px solid rgba(15, 23, 42, 0.05);
        }

        .reminder-panel h2 {
            margin: 0 0 8px;
            font-size: 24px;
            color: #102542;
        }

        .reminder-panel p {
            margin: 0 0 20px;
            color: #52606d;
            line-height: 1.6;
        }

        .reminder-form {
            display: grid;
            gap: 18px;
        }

        .reminder-field {
            display: grid;
            gap: 8px;
        }

        .reminder-field label,
        .reminder-toggle span {
            font-size: 14px;
            font-weight: 600;
            color: #102542;
        }

        .reminder-form textarea,
        .reminder-form input[type="time"] {
            width: 100%;
            border: 1px solid #d7dee8;
            border-radius: 16px;
            padding: 14px 16px;
            font: inherit;
            color: #102542;
            background: #fbfcfe;
        }

        .reminder-form textarea {
            min-height: 150px;
            resize: vertical;
        }

        .reminder-toggle {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            border: 1px solid #d7dee8;
            border-radius: 16px;
            background: #fbfcfe;
        }

        .reminder-toggle input[type="checkbox"] {
            width: 18px;
            height: 18px;
        }

        .reminder-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .reminder-button {
            border: none;
            border-radius: 999px;
            padding: 12px 20px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .reminder-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.12);
        }

        .reminder-button--primary {
            background: linear-gradient(135deg, #3c4fe0, #5b6cf6);
            color: #fff;
        }

        .reminder-button--secondary {
            background: linear-gradient(135deg, #f59e0b, #f97316);
            color: #fff;
        }

        .reminder-timeline {
            display: grid;
            gap: 14px;
        }

        .reminder-timeline-item {
            border: 1px solid #e5ebf2;
            border-radius: 18px;
            padding: 18px;
            background: linear-gradient(180deg, #ffffff, #f8fafc);
        }

        .reminder-timeline-item strong {
            display: block;
            margin-bottom: 8px;
            color: #102542;
        }

        .reminder-timeline-item p {
            margin: 0 0 10px;
            color: #52606d;
        }

        .reminder-timeline-item small {
            color: #667085;
        }

        .flash-stack {
            display: grid;
            gap: 12px;
        }

        @media (max-width: 1024px) {
            .admin-reminders-page .dashboard-content {
                padding: 30px;
            }

            .admin-reminders-page .summary-grid {
                grid-template-columns: 1fr;
            }

            .reminders-layout {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .admin-reminders-page .dashboard-content {
                padding: 22px 16px 30px;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $recentReminderCount = $recentReminders->count();
        $automationEnabled = (bool) ($schedule?->is_enabled ?? false);
        $scheduledTimeLabel = $schedule?->send_time
            ? \Illuminate\Support\Carbon::createFromFormat('H:i:s', strlen((string) $schedule->send_time) === 5 ? $schedule->send_time . ':00' : $schedule->send_time)->format('h:i A')
            : 'Not scheduled';
        $latestReminderAt = optional($recentReminders->first()?->triggered_at)->format('M d, Y h:i A') ?? 'No reminders sent yet';
    @endphp

    <div class="dashboard-page">
        <main class="dashboard-shell">
            <x-topbar active="reminders" :can-access-audit="$canAccessAudit" :user="$user" />

            <section class="dashboard-content">
                <div class="flash-stack">
                    @if (session('status'))
                        <p class="flash-message flash-success">{{ session('status') }}</p>
                    @endif

                    @if ($errors->any())
                        <p class="flash-message flash-error">{{ $errors->first() }}</p>
                    @endif
                </div>

                <div class="page-intro">
                    <div>
                        <h1>Office Reminder Dashboard</h1>
                        <p>Manage reminder automation and instant alerts for staff and interns assigned to <strong>{{ $user->office }}</strong> without changing any other office.</p>
                    </div>
                    <div class="page-pill">
                        <span>{{ $user->office }}</span>
                        <span>Provincial office scope</span>
                    </div>
                </div>

                <section class="summary-grid">
                    <article class="summary-card summary-card-violet is-active">
                        <div>
                            <span class="summary-label">Office Scope</span>
                            <div class="summary-value-row">
                                <strong>{{ $user->office }}</strong>
                                <span>Target Office</span>
                            </div>
                            <p class="summary-meta">Only staff and interns assigned to this office receive the reminders you send.</p>
                        </div>
                        <div class="summary-icon summary-icon-violet">
                            <svg viewBox="0 0 24 24"><path d="M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-9.5Zm2 1V19h2v-6h10v6h2v-7.5L12 5 5 11.5Z"/></svg>
                        </div>
                    </article>

                    <article class="summary-card summary-card-green {{ $automationEnabled ? 'is-active' : '' }}">
                        <div>
                            <span class="summary-label">Automation</span>
                            <div class="summary-value-row">
                                <strong>{{ $automationEnabled ? 'On' : 'Off' }}</strong>
                                <span>{{ $scheduledTimeLabel }}</span>
                            </div>
                            <p class="summary-meta">{{ $automationEnabled ? 'Daily reminder automation is enabled for your office.' : 'Daily reminder automation is currently disabled.' }}</p>
                        </div>
                        <div class="summary-icon summary-icon-green">
                            <svg viewBox="0 0 24 24"><path d="M12 1.75A10.25 10.25 0 1 0 22.25 12 10.26 10.26 0 0 0 12 1.75Zm0 2A8.25 8.25 0 1 1 3.75 12 8.26 8.26 0 0 1 12 3.75Zm1 3.5h-2v5.16l3.62 3.63 1.42-1.42L13 11.59Z"/></svg>
                        </div>
                    </article>

                    <article class="summary-card summary-card-orange">
                        <div>
                            <span class="summary-label">Recent Activity</span>
                            <div class="summary-value-row">
                                <strong>{{ $recentReminderCount }}</strong>
                                <span>{{ $recentReminderCount === 1 ? 'Reminder' : 'Reminders' }}</span>
                            </div>
                            <p class="summary-meta">Latest reminder activity: {{ $latestReminderAt }}</p>
                        </div>
                        <div class="summary-icon summary-icon-orange">
                            <svg viewBox="0 0 24 24"><path d="M18 8a6 6 0 1 0-12 0c0 2.44-.86 4.06-1.73 5.28A1 1 0 0 0 5.08 15h13.84a1 1 0 0 0 .81-1.72C18.86 12.06 18 10.44 18 8Zm-6 14a2.5 2.5 0 0 0 2.45-2h-4.9A2.5 2.5 0 0 0 12 22Z"/></svg>
                        </div>
                    </article>
                </section>

                <div class="reminders-layout">
                    <section class="reminder-panel">
                        <h2>Daily Reminder Automation</h2>
                        <p>Schedule one automatic reminder every day for everyone assigned to <strong>{{ $user->office }}</strong>. This behaves like an office-scoped alarm clock for report submission follow-ups.</p>

                        <form method="POST" action="{{ route('admin.dashboard.reminders.schedule') }}" class="reminder-form">
                            @csrf

                            <div class="reminder-field">
                                <label for="daily_reminder_message">Reminder Message</label>
                                <textarea id="daily_reminder_message" name="message" placeholder="Reminder: Please submit your accomplishment report.">{{ old('message', $schedule?->message) }}</textarea>
                            </div>

                            <div class="reminder-field">
                                <label for="daily_reminder_time">Reminder Time</label>
                                <input id="daily_reminder_time" type="time" name="send_time" value="{{ old('send_time', $schedule?->send_time ? substr((string) $schedule->send_time, 0, 5) : '16:00') }}" required>
                            </div>

                            <label class="reminder-toggle">
                                <input type="checkbox" name="is_enabled" value="1" {{ old('is_enabled', $schedule?->is_enabled ?? true) ? 'checked' : '' }}>
                                <span>Enable daily automatic reminder for {{ $user->office }}</span>
                            </label>

                            <div class="reminder-actions">
                                <button type="submit" class="reminder-button reminder-button--primary">Save Daily Schedule</button>
                            </div>
                        </form>
                    </section>

                    <aside class="reminder-panel">
                        <h2>Send Reminder Now</h2>
                        <p>Trigger an immediate office-wide reminder notification right now. This does not change the daily schedule.</p>

                        <form method="POST" action="{{ route('admin.dashboard.reminders.send-now') }}" class="reminder-form">
                            @csrf

                            <div class="reminder-field">
                                <label for="manual_reminder_message">Quick Reminder Message</label>
                                <textarea id="manual_reminder_message" name="message" placeholder="Reminder: Please submit your accomplishment report before end of day.">{{ old('message') }}</textarea>
                            </div>

                            <div class="reminder-actions">
                                <button type="submit" class="reminder-button reminder-button--secondary">Send Reminder Now</button>
                            </div>
                        </form>
                    </aside>
                </div>

                <section class="table-panel">
                    <div class="section-header">
                        <div>
                            <h1>Recent Reminder Activity</h1>
                            <div class="section-subcopy">
                                <span class="user-badge-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><path d="M12 2a1 1 0 0 1 1 1v1.07A7.002 7.002 0 0 1 19 11v3.59l1.7 1.7A1 1 0 0 1 20 18H4a1 1 0 0 1-.7-1.71L5 14.59V11a7 7 0 0 1 6-6.93V3a1 1 0 0 1 1-1Zm0 20a2.5 2.5 0 0 0 2.45-2h-4.9A2.5 2.5 0 0 0 12 22Z"/></svg>
                                </span>
                                <span>{{ $recentReminderCount }} {{ $recentReminderCount === 1 ? 'reminder sent' : 'reminders sent' }} for {{ $user->office }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="reminder-timeline">
                        @forelse ($recentReminders as $reminder)
                            <article class="reminder-timeline-item">
                                <strong>{{ ucfirst($reminder->type) }} reminder</strong>
                                <p>{{ $reminder->message }}</p>
                                <small>{{ $reminder->triggered_at?->format('M d, Y h:i A') ?? 'N/A' }}</small>
                            </article>
                        @empty
                            <article class="reminder-timeline-item">
                                <strong>No reminders sent yet.</strong>
                                <p>Your manual and automated reminder history for {{ $user->office }} will appear here once you send or schedule reminders.</p>
                            </article>
                        @endforelse
                    </div>
                </section>
            </section>
        </main>
    </div>
@endsection
