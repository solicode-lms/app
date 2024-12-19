<li class="nav-item has-treeview {{ Request::is('PkgBlog*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('PkgBlog*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-table"></i>
        <p>
            PkgBlog
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('articles.index') }}" class="nav-link {{ Request::is('PkgBlog/articles') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>Articles</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('categories.index') }}" class="nav-link {{ Request::is('PkgBlog/categories') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>Categories</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('comments.index') }}" class="nav-link {{ Request::is('PkgBlog/comments') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>Comments</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('tags.index') }}" class="nav-link {{ Request::is('PkgBlog/tags') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>Tags</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('users.index') }}" class="nav-link {{ Request::is('PkgBlog/users') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>Users</p>
            </a>
        </li>
    </ul>
</li>


