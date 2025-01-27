<!DOCTYPE html>
<html lang="fr">
<head>
    <?php
    function isit($param) {
        return ($param != null && $param != "");
    }
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $carte['nomEntreprise'] ? $carte['nomEntreprise'] . ' - ' : '' }} - Wisikard</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700"
          rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-080RS8FYWX"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-080RS8FYWX');
    </script>
    <style>
        :root {
            --primary-color: <?php echo $site["couleur1"]; ?>;
            --secondary-color: <?php echo $site["couleur2"]; ?>;
            --background-color: #1a1a1a;
            --text-color: #ffffff;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            animation: fadeIn 1s ease-in-out;
        }
        
        .container {
            max-width: 1200px;
            width: 90%;
            margin: 2rem auto;
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .logo {
            max-width: 150px;
            margin-bottom: 2rem;
        }
        
        h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }
        
        h2 {
            font-size: 2rem;
            font-weight: 400;
            margin-bottom: 1rem;
        }
        
        p {
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .contacts {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .contacts a {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: var(--text-color);
            background-color: rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 10px;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }
        
        .contacts a:hover {
            transform: translateY(-5px);
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .socials {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .socials svg {
            width: 30px;
            height: 30px;
            fill: var(--primary-color);
        }
        
        #embedyoutube {
            width: 100%;
            max-width: 560px;
            aspect-ratio: 16 / 9;
            margin-bottom: 2rem;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .affiche {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        footer {
            margin-top: auto;
            padding: 1rem;
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            width: 100%;
        }
        
        footer a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
        
        /* Styles pour le modal QR code */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border-radius: 10px;
            max-width: 300px;
        }
        
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (isit($site["logo"])): ?>
            <img class="logo" src="/storage/logo/<?php echo $site["logo"]; ?>" alt="Logo">
        <?php endif; ?>

        <?php if (isit($site["titre"])): ?>
            <h1><?php echo htmlspecialchars($site["titre"]); ?></h1>
        <?php endif; ?>

        <?php if ($employe != null): ?>
            <h2><?php echo htmlspecialchars($employe["nom"] . " " . $employe["prenom"] . " - " . $employe["fonction"]); ?></h2>
            <?php if(isit($employe["email"]) || isit($employe["telephone"])): ?>
                <div class="contacts">
                    <?php if(isit($employe["email"])): ?>
                        <a href="mailto:<?php echo $employe["email"]; ?>"><?php echo $employe["email"]; ?></a>
                    <?php endif; ?>
                    <?php if(isit($employe["telephone"])): ?>
                        <a href="tel:<?php echo $employe["telephone"]; ?>"><?php echo $employe["telephone"]; ?></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (isit($site["descriptif"])): ?>
            <p><?php echo htmlspecialchars($site["descriptif"]); ?></p>
        <?php endif; ?>

        <div class="contacts">
            <!-- Insérez ici les boutons de contact avec les icônes Lordicon -->
    <a onclick="showQr()">
        <lord-icon src="https://cdn.lordicon.com/avcjklpr.json"
            trigger="loop"
            delay="1000"
            colors="primary:#F5F5F5,secondary:<?php echo $site['couleur1']; ?>">
        </lord-icon>
        QRCode
    </a>

    <?php if (isit($site["nomEntreprise"]) && isit($site["ville"])): ?>
        <a href="https://www.google.com/maps/search/?api=1&query=<?php echo htmlspecialchars($site['nomEntreprise'], ENT_QUOTES) . '+' . htmlspecialchars($site['ville'], ENT_QUOTES); ?>">
            <lord-icon src="https://cdn.lordicon.com/surcxhka.json"
                trigger="loop"
                delay="1000"
                colors="primary:#F5F5F5,secondary:<?php echo $site['couleur1']; ?>">
            </lord-icon>
            Maps
        </a>
    <?php endif; ?>

    <?php if (isit($site["lienSite"])): ?>
        <a href="<?php echo $site['lienSite']; ?>">
            <lord-icon src="https://cdn.lordicon.com/pbbsmkso.json"
                trigger="loop"
                delay="1000"
                colors="primary:#F5F5F5,secondary:<?php echo $site['couleur1']; ?>">
            </lord-icon>
            Site
        </a>
    <?php endif; ?>

    <?php if (isit($site["tel"])): ?>
        <a href="tel:<?php echo htmlspecialchars($site['tel'], ENT_QUOTES); ?>">
            <lord-icon src="https://cdn.lordicon.com/qtykvslf.json"
                trigger="loop"
                delay="1000"
                colors="primary:#F5F5F5,secondary:<?php echo $site['couleur1']; ?>">
            </lord-icon>
            Téléphone
        </a>
    <?php endif; ?>

    <?php if (isit($site["mailContact"])): ?>
        <a href="mailto:<?php echo htmlspecialchars($site['mailContact'], ENT_QUOTES); ?>">
            <lord-icon src="https://cdn.lordicon.com/aycieyht.json"
                trigger="loop"
                delay="1000"
                colors="primary:#F5F5F5,secondary:<?php echo $site['couleur1']; ?>">
            </lord-icon>
            Mail
        </a>
    <?php endif; ?>

        </div>

        <div class="socials">
            <?php foreach ($socials as $so): ?>
                <a href="<?php echo $so["lien"]; ?>"><?php echo $so["lienLogo"]; ?></a>
            <?php endforeach; ?>
        </div>

        <?php if(isit($site["image"])): ?>
            <img class="affiche" src="/storage/image/<?php echo $site["image"]; ?>" alt="Image">
        <?php endif; ?>

        <?php
        $embedyoutube = null;
        foreach ($fonctions as $f) {
            if ($f["nom"] == "embedyoutube") {
                $embedyoutube = $f;
                break;
            }
        }
        if($embedyoutube != null && $embedyoutube["option"] != ""): 
            $video = "";
            if (str_contains($embedyoutube["option"], "youtu.be")) {
                $x = explode("/", $embedyoutube["option"]);
                $video = explode("?", end($x))[0];
            } else {
                $video = explode("&", (explode("?v=", $embedyoutube["option"]))[1])[0];
            }
        ?>
            <iframe id="embedyoutube" src="https://www.youtube.com/embed/<?php echo $video; ?>" frameborder="0" allowfullscreen></iframe>
        <?php endif; ?>
    </div>

    <div id="qrModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="hideQr()">&times;</span>
            <h2>QR code de <?php echo $site['nomEntreprise']; ?></h2>
            <img src="/storage/qr/<?php echo ($employe != null) ? $site['idSite']."-".$employe['idEmploye'].".png" : $site['idSite'].".png"; ?>" alt="QR Code" style="width:100%">
        </div>
    </div>

    <footer>
        Un service proposé par <a href="https://sendix.fr">SENDIX</a> - <a href="https://wisikard.fr">WisiKard</a>
    </footer>

    <script src="/assets/js/allTemplates.js"></script>
</body>
</html>