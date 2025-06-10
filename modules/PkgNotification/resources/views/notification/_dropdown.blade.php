<li class="nav-item dropdown">
    <a id="notificationDropdown" class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge" id="notification-count">
            {{ $unreadNotificationCount ?? 0 }}
        </span>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-header">
            {{ $unreadNotificationCount ?? 0 }} Notifications
        </span>
        <div class="dropdown-divider"></div>

        @forelse ($notifications ?? [] as $notification)
        <a href="{{ $notification->data['lien'] ?? '#' }}" class="dropdown-item">
            <div>
                <div class="mb-1" style="white-space: normal;">
                    <i class="fas fa-envelope mr-2"></i> 
                    {{ $notification->title }}
                </div>
                <div class="text-muted text-sm text-right">
                    {{ $notification->created_at->diffForHumans() }}
                </div>
            </div>
        </a>
        <div class="dropdown-divider"></div>
    @empty
        <a href="#" class="dropdown-item text-center text-muted">
            Aucune notification
        </a>
    @endforelse


        <div class="dropdown-divider"></div>
        <a href="{{ route('notifications.index') }}" class="dropdown-item dropdown-footer">Voir toutes les notifications</a>
    </div>
</li>
