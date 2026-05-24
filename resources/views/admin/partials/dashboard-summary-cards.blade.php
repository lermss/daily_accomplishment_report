<section class="stats-grid" aria-label="Dashboard summary">
    @foreach ($stats as $stat)
        <a href="{{ $stat['route'] }}" class="stat-card stat-card-{{ $stat['tone'] }}">
            <div class="stat-copy">
                <span class="stat-label">{{ $stat['label'] }}</span>
                <div class="stat-number-row">
                    <strong>{{ $stat['count'] }}</strong>
                    <span>Users</span>
                </div>
                <span class="stat-meta">{{ $stat['meta'] }}</span>
            </div>
            <div class="stat-icon stat-icon-{{ $stat['tone'] }}" aria-hidden="true">
                <span class="stat-illustration stat-illustration-{{ $stat['tone'] }}">
                    @if ($stat['key'] === 'users')
                        <span class="ill-avatar"></span>
                        <span class="ill-shoulders"></span>
                    @elseif ($stat['key'] === 'archive')
                        <span class="ill-lock-body"></span>
                        <span class="ill-lock-shackle"></span>
                        <span class="ill-lock-dot"></span>
                    @else
                        <span class="ill-ring"></span>
                        <span class="ill-center"></span>
                    @endif
                </span>
            </div>
        </a>
    @endforeach
</section>
