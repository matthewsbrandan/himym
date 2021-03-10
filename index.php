<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width initial-scale=1"/>
    <title>HIMYM</title>
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/favicon-logo.ico"><!--ICONE-->
    <link rel="icon" type="image/png" href="assets/img/favicon-logo.ico"><!--ICONE-->
    <!-- ===== BOX ICONS ===== -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <!-- ===== CSS ===== -->
    <link rel="stylesheet" href="assets/css/styles.css"/>
</head>
<body id="body-pd">
    <header class="header" id="header">
        <div class="header__toggle">
            <i class="bx bx-menu" id="header-toggle"></i>
        </div>
        <div class="videoCurrent" id="videoCurrent">
            <a id="videoCurrentFavorite">
                <i class="bx bx-heart"></i>
            </a>
            <h4>1.3 | Piloto</h4>
            <a id="videoCurrentNext">
                <i class="bx bx-right-arrow-alt"></i>
            </a>
        </div>
        <div class="header__img">
            <img src="assets/img/profile.jpg" alt="Perfil">
        </div>
    </header>

    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div>
                <a href="#" class="nav__logo">
                    <i class="bx bx-layer nav__logo-icon"></i>
                    <span class="nav__logo-name">HIMYM</span>
                </a>
                <div class="nav__list">
                    <a href="#" class="nav__link active" onclick="progress()">
                        <i class="bx bxs-hourglass nav__icon"></i>
                        <span class="nav__name">Current</span>
                    </a>
                    <a href="#" class="nav__link nav__play" onclick="currentEpisode()">
                        <i class="bx bx-right-arrow nav__icon"></i>
                        <span class="nav__name">Play</span>
                    </a>
                    <a href="#" class="nav__link" onclick="randomEpisode()">
                        <i class="bx bx-shuffle nav__icon"></i>
                        <span class="nav__name">Random</span>
                    </a>
                    <a href="#" class="nav__link" onclick="episodes()">
                        <i class="bx bx-grid-alt nav__icon"></i>
                        <span class="nav__name">Episodes</span>
                    </a>
                    <a href="#" class="nav__link" onclick="favorites()">
                        <i class="bx bx-bookmark nav__icon"></i>
                        <span class="nav__name">Favorites</span>
                    </a>
                </div>
                <a href="#" class="nav__link">
                    <i class="bx bx-log-out nav__icon"></i>
                    <span class="nav__name">Log Out</span>
                </a>
            </div>
        </nav>
    </div>
    <!-- CURRENT -->
    <div class="container" id="currentContainer" style="display: none;">
        <h1>How I Met Your Mother</h1>
        <div id="progressContainer">
            <div id="progressBar"></div>
        </div>
        <span class="currentProgress"></span>
        <button type="button" onclick="$('#progressCard').toggle('slow');">
            Progresso
        </button>
        <div id="progressCard">
            <div>
                <label for="powerRange">Nenhum Episódio Assistido...</label>
                <input type="range" value="1" min="0" max="208" id="powerRange" name="powerRange" oninput="rangeLabel();">
                <div>
                    <button type="button" onclick="progressTo();">Alterar</button>
                    <button type="button" onclick="resetProgress();">Zerar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container" style="display: none;" id="playContainer">
        <section class="video-player">
            <video controls onended="$('#videoCurrentNext').click();" style="height: 100%; width: 100%; border: none;">
                <source src="../../himym/video/s1/ep1.mp4" type="video/mp4">
                Ainda não cadastrado
            </video>
        </section>
    </div>
    <div class="container" style="display: none;" id="episodesContainer">
        <h1>HIMYM | Episodes</h1>
        <div id="seasonContainer">
            <select name="selectSeason" id="selectSeason">
                <?php for($i=1; $i<=9; $i++){ ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?>ª Season</option>
                <?php } ?>
            </select>
            <button type="button" onclick="episodes($('#selectSeason').val());">Reload</button>
        </div>
        <div class="cardContainer">
        </div>
    </div>
    <div class="container" style="display: none;" id="favoritesContainer">
        <h1>HIMYM | Favorites</h1>
        <div class="cardContainer">
        </div>
    </div>
    <!-- ===== MAIN JS ===== -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/api.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>