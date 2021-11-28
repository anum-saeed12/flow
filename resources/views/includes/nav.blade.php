<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                @if(alerts()->count()>0)<span class="badge badge-warning navbar-badge">{{ alerts()->count() }}</span>@endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                @forelse(alerts()->fetch() as $item)
                <a href="{{ route(auth()->user()->user_role . ".alerts.show", $item->id) }}" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                @if($item->action == 'added')
                                    You have been added to the {{ $item->type }}
                                @elseif($item->action == 'updated')
                                    {{ ucfirst($item->type) }} has been updated
                                @elseif($item->action == 'assigned')
                                    You have been assigned a {{ $item->type }}
                                @elseif($item->action == 'removed')
                                    You have been removed from a {{ $item->type }}
                                @endif
                            </h3>
                            <p class="text-sm">{{ crop($item->message) }}</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> {{ $item->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                @empty
                    <a href="#" class="dropdown-item">
                        <!-- Message Start -->
                        <div class="media">
                            <div class="media-body">
                                <h3 class="dropdown-item-title text-center py-3">
                                    No notifications
                                </h3>
                            </div>
                        </div>
                        <!-- Message End -->
                    </a>
                    <div class="dropdown-divider"></div>
                @endforelse
                <a href="{{ route(auth()->user()->user_role . ".alerts.index") }}" class="dropdown-item dropdown-footer">See All Alerts</a>
            </div>
        </li>
    </ul>
</nav>
