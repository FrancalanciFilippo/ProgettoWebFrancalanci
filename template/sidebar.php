<?php
$active = isset($templateParams["sidebarActive"]) ? $templateParams["sidebarActive"] : "profile";
$basePath = isset($templateParams["basePath"]) ? $templateParams["basePath"] : "";
?>
<!-- Sidebar desktop (md+) -->
<div class="col-md-3 col-lg-2 d-none d-md-flex flex-column border-end bg-body-tertiary p-3">
    <nav aria-label="Menu profilo">
        <ul class="nav nav-pills flex-column gap-1">
            <li class="nav-item">
                <a href="<?php echo $basePath; ?>pages/profile.php" class="nav-link <?php echo $active === 'profile' ? 'active' : ''; ?>" <?php echo $active === 'profile' ? 'aria-current="page"' : ''; ?> id="sidebar-modifica">
                    <em class="bi bi-person-circle me-2" aria-hidden="true"></em>
                    Profilo
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $basePath; ?>pages/my_posts.php" class="nav-link <?php echo $active === 'my_posts' ? 'active' : ''; ?>" <?php echo $active === 'my_posts' ? 'aria-current="page"' : ''; ?> id="sidebar-miei-post">
                    <em class="bi bi-file-text me-2" aria-hidden="true"></em>
                    I miei post
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $basePath; ?>pages/joined_posts.php" class="nav-link <?php echo $active === 'joined_posts' ? 'active' : ''; ?>" <?php echo $active === 'joined_posts' ? 'aria-current="page"' : ''; ?> id="sidebar-partecipati">
                    <em class="bi bi-people me-2" aria-hidden="true"></em>
                    Post a cui partecipi
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $basePath; ?>pages/create_post.php" class="nav-link <?php echo $active === 'create_post' ? 'active' : ''; ?>" id="sidebar-crea-post">
                    <em class="bi bi-plus-circle me-2" aria-hidden="true"></em>
                    Crea Nuovo Post
                </a>
            </li>
        </ul>
    </nav>
</div>

<!-- Trigger offcanvas su mobile -->
<div class="col-12 d-md-none border-bottom p-2">
    <button class="btn btn-sm btn-outline-secondary w-100" type="button"
        data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas"
        aria-controls="sidebarOffcanvas">
        <em class="bi bi-layout-sidebar me-1" aria-hidden="true"></em>
        Menu Profilo
    </button>
</div>

<!-- Offcanvas mobile -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarOffcanvas">
    <div class="offcanvas-header border-bottom">
        <h2 class="offcanvas-title fs-6 fw-bold" id="sidebarOffcanvasLabel">Menu Profilo</h2>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
            aria-label="Chiudi menu"></button>
    </div>
    <div class="offcanvas-body">
        <nav aria-label="Menu profilo mobile">
            <ul class="nav nav-pills flex-column gap-1">
                <li class="nav-item">
                    <a href="<?php echo $basePath; ?>pages/profile.php" class="nav-link <?php echo $active === 'profile' ? 'active' : ''; ?>" <?php echo $active === 'profile' ? 'aria-current="page"' : ''; ?>>
                        <em class="bi bi-person-circle me-2" aria-hidden="true"></em>
                        Profilo
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $basePath; ?>pages/my_posts.php" class="nav-link <?php echo $active === 'my_posts' ? 'active' : ''; ?>" <?php echo $active === 'my_posts' ? 'aria-current="page"' : ''; ?>>
                        <em class="bi bi-file-text me-2" aria-hidden="true"></em>
                        I miei post
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $basePath; ?>pages/joined_posts.php" class="nav-link <?php echo $active === 'joined_posts' ? 'active' : ''; ?>" <?php echo $active === 'joined_posts' ? 'aria-current="page"' : ''; ?>>
                        <em class="bi bi-people me-2" aria-hidden="true"></em>
                        Post a cui partecipi
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $basePath; ?>pages/create_post.php" class="nav-link <?php echo $active === 'create_post' ? 'active' : ''; ?>">
                        <em class="bi bi-plus-circle me-2" aria-hidden="true"></em>
                        Crea Nuovo Post
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
