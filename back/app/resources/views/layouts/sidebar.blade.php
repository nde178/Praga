<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Панель управления</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('categories.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Категории</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tags.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-tag"></i>
                        <p>Теги</p>
                    </a>
                </li>

                <!-- Продукты с выпадающим списком категорий -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tshirt"></i>
                        <p>
                            Продукты
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @foreach($categories as $category)
                            <li class="nav-item">
                                <a href="{{ route('products.byCategory', $category->id) }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ $category->name }}</p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                <!-- Доступ только для администратора -->
                @if(auth()->check() && auth()->user()->hasAllPermissions())
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Пользователи</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('roles.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-user-tag"></i>
                            <p>Роли</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('invoices.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-file-invoice"></i>
                            <p>Инвойсы</p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
