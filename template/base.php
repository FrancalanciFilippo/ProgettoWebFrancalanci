<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description"
        content="<?php echo isset($templateParams['descrizione']) ? $templateParams['descrizione'] : 'SchoolTogether - La conoscenza cresce solo se condivisa. Partecipa a gruppi di studio o creane uno tu.'; ?>" />
    
    <title><?php echo isset($templateParams["titolo"]) ? $templateParams["titolo"] : "SchoolTogether"; ?></title>

    <?php $basePath = isset($templateParams["basePath"]) ? $templateParams["basePath"] : ""; ?>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    
    <link rel="stylesheet" type="text/css" href="<?php echo $basePath; ?>css/style.css"/>
    <?php
    if (isset($templateParams["css"])) {
        foreach ($templateParams["css"] as $cssFile) {
            echo '<link rel="stylesheet" type="text/css" href="' . $cssFile . '"/>';
        }
    }
    ?> 
</head>

<body>
    <a class="skip-link" href="#main-content">Vai al contenuto principale</a>
    <?php if (!isset($templateParams["hideNav"]) || !$templateParams["hideNav"]): ?>
    <header>
        <nav class="navbar navbar-expand-md navbar-light bg-white border-bottom navbar-custom fixed-top" aria-label="Navigazione principale">
            <div class="container">
                <a class="navbar-brand fw-bold" href="<?php echo $basePath; ?>index.php">
                    <img src="<?php echo $basePath; ?>img/logo.png" alt="SchoolTogether logo" height="28" class="me-2" />
                    SchoolTogether
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
                    aria-controls="navbarMain" aria-expanded="false" aria-label="Apri il menu di navigazione">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarMain">
                    <ul class="navbar-nav ms-auto align-items-center gap-1">
                        <?php if (isAdmin()): ?>
                            <li class="nav-item">
                                <a class="nav-link fw-bold text-danger" href="<?php echo $basePath; ?>pages/admin.php">Admin</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $basePath; ?>pages/posts.php">Posts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $basePath; ?>pages/create_post.php">Crea post</a>
                        </li>
                        <li class="nav-item">
                            <?php if (isUserLoggedIn()): ?>
                                <a class="nav-link" href="<?php echo $basePath; ?>pages/profile.php" aria-label="Profilo utente">
                                    <em class="bi bi-person-circle fs-5" aria-hidden="true"></em>
                                    <span class="d-md-none ms-1">Profilo</span>
                                </a>
                            <?php else: ?>
                                <a class="nav-link" href="<?php echo $basePath; ?>pages/login.php" aria-label="Accedi al tuo profilo">
                                    <em class="bi bi-person-circle fs-5" aria-hidden="true"></em>
                                    <span class="d-md-none ms-1">Profilo</span>
                                </a>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <?php endif; ?>
    <div class="container">
        <?php if (!isset($templateParams["hideBreadcrumb"]) || !$templateParams["hideBreadcrumb"]): ?>
        <nav aria-label="Breadcrumb">
            <ol class="breadcrumb py-2 mb-0 small">
                <?php if (isset($templateParams["breadcrumb"])): ?>
                    <?php foreach ($templateParams["breadcrumb"] as $item): ?>
                        <?php if (isset($item["active"]) && $item["active"]): ?>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $item["label"]; ?></li>
                        <?php else: ?>
                            <li class="breadcrumb-item"><a href="<?php echo $item['url']; ?>"><?php echo $item["label"]; ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="breadcrumb-item active" aria-current="page">Home</li>
                <?php endif; ?>
            </ol>
        </nav>
        <?php endif; ?>
    </div>

    <main id="main-content">
        <?php if (isset($templateParams["hasSidebar"]) && $templateParams["hasSidebar"]): ?>
            <div class="container-fluid">
                <div class="row min-vh-100">
                    <?php require(__DIR__ . "/sidebar.php"); ?>
                    <div class="col-12 col-md-9 col-lg-10 pt-2 px-4 pb-4 pt-md-4">
                        <?php
                        if(isset($templateParams["main"])){
                            require(__DIR__ . "/" . $templateParams["main"]);
                        }
                        ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <?php
            if(isset($templateParams["main"])){
                require(__DIR__ . "/" . $templateParams["main"]);
            }
            ?>
        <?php endif; ?>
    </main>
    <?php if (!isset($templateParams["hideNav"]) || !$templateParams["hideNav"]): ?>
    <footer class="border-top bg-light py-4 mt-4">
        <div class="container">
            <div class="row text-center text-md-start">
                <div class="col-12 col-md-4 mb-3 mb-md-0">
                    <h2 class="h6 fw-bold">Navigazione</h2>
                    <ul class="list-unstyled small">
                        <li><a href="<?php echo $basePath; ?>index.php" class="text-body text-decoration-none">Home</a></li>
                        <li><a href="<?php echo $basePath; ?>pages/about_us.php" class="text-body text-decoration-none">Chi siamo</a></li>
                        <li><a href="<?php echo $basePath; ?>pages/contacts.php" class="text-body text-decoration-none">Contatti</a></li>
                    </ul>
                </div>
                <div class="col-12 col-md-4 mb-3 mb-md-0 text-md-center">
                    <h2 class="h6 fw-bold">Link utili</h2>
                    <ul class="list-unstyled small">
                        <li><a href="https://www.instagram.com" target="_blank" rel="noopener noreferrer"
                                class="text-body text-decoration-none">Instagram</a></li>
                        <li><a href="https://www.linkedin.com" target="_blank" rel="noopener noreferrer"
                                class="text-body text-decoration-none">LinkedIn</a></li>
                        <li><a href="https://www.facebook.com" target="_blank" rel="noopener noreferrer"
                                class="text-body text-decoration-none">Facebook</a></li>
                    </ul>
                </div>
                <div class="col-12 col-md-4 text-md-end">
                    <h2 class="h6 fw-bold">Contatti</h2>
                    <ul class="list-unstyled small">
                        <li>
                            <a href="mailto:filippo.francalanci@studio.unibo.it"
                                class="text-body text-decoration-none">filippo.francalanci@studio.unibo.it</a>
                        </li>
                    </ul>
                </div>
            </div>
            <hr />
            <p class="text-center text-secondary small mb-0">
                &copy; 2026 SchoolTogether &ndash; Francalanci Filippo
            </p>
        </div>
    </footer>
    <?php endif; ?>
    <?php
    // Inizializza le variabili globali di cui hanno bisogno i file JS
    if (isset($templateParams["filters"])) {
        echo '<script>';
        echo 'window.currentFilters = ' . json_encode($templateParams["filters"]) . ';';
        echo '</script>';
    }
    ?>
    <?php
    if (isset($templateParams["js"])) {
        foreach ($templateParams["js"] as $jsFile) {
            echo '<script src="' . $jsFile . '"></script>';
        }
    }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>