<?php require_once 'modules/config.php';
	$SITE_VERSION = '1.15.0.9';

	// This prevents caching on local testing
	if (strpos($WEB_ROOT, 'src') !== false) {
		$SITE_VERSION = rand(1,1000) . '.' . rand(1,1000) . '.' . rand(1,1000);
	}

	// Initialize settings object
	if (isset($_COOKIE['settings'])) {
		$_SETTINGS = json_decode($_COOKIE['settings']);

		// Fill in missing settings with defaults
		if (!isset($_SETTINGS->matrixDirection)) {
			$_SETTINGS->matrixDirection = "row";
		}
	} else {
		$_SETTINGS = (object) [
			'defaultIVs' 			=> "gamemaster",
			'animateTimeline'	=> 1,
			'theme' 					=> 'default'
		];
	}

	$performGroupMigration = false;

	if (!isset($_COOKIE['migrate'])) {
		$performGroupMigration = true;

		setcookie('migrate', 'true', time() + (5 * 365 * 24 * 60 * 60), '/');
	}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php
		if (!isset($META_TITLE)) {
			$META_TITLE = 'PvPoke | Open-Source Battle Simulator, Rankings &amp; Team Building for Pokemon GO PvP';
		} else {
			$META_TITLE = $META_TITLE . ' | PvPoke';
		}

		if (!isset($META_DESCRIPTION)) {
			$META_DESCRIPTION = 'Looking for an edge in Pokemon GO Trainer Battles? Become a master with our open-source Pokemon battle simulator, explore the top Pokemon rankings, and get your team rated for PvP battles.';
		}

		if (!isset($OG_IMAGE)) {
			$OG_IMAGE = 'https://pvpoke.com/img/og.jpg';
		}
	?>

	<title><?php echo $META_TITLE; ?></title>
	<meta name="description" content="<?php echo $META_DESCRIPTION; ?>" />

	<!-- Prevents Google from indexing hundreds of different versions of the same page -->
	<?php if(isset($CANONICAL)): ?>
		<link rel="canonical" href="<?php echo $CANONICAL; ?>" />
	<?php endif; ?>

	<!-- OG tags for social -->
	<meta property="og:title" content="<?php echo $META_TITLE; ?>" />
	<meta property="og:description" content="<?php echo $META_DESCRIPTION; ?>" />
	<meta property="og:image" content="<?php echo $OG_IMAGE; ?>" />

	<meta name="apple-mobile-web-app-capable">
	<meta name="mobile-web-app-capable">

	<!-- Data Manafest -->
	<link rel="manifest" href="<?php echo $WEB_ROOT; ?>data/manifest.json?v=2">

	<!-- Favicon -->
	<link rel="icon" href="<?php echo $WEB_ROOT; ?>img/favicon.png">

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">

	<!-- Font Awesome -->
	<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css'/>

	<!-- Stylsheet -->
	<!-- Use cache busting to always give user most updated version -->
	<link rel="stylesheet" type="text/css" href="<?php echo $WEB_ROOT; ?>scss/main.min.css?ver=<?php echo rand(1, 1000) ?>">

	<!-- jQuery -->
	<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>

	<!-- Cache busting template -->
	<!-- ?ver=<?php echo rand(1, 1000) ?> -->

	<?php require_once('modules/analytics.php'); ?>

	<script>
		// Host for link reference
		var host = "<?php echo $WEB_HOST; ?>";
		var webRoot = "<?php echo $WEB_ROOT; ?>";
		var siteVersion = "<?php echo $SITE_VERSION; ?>";

		<?php if (isset($_COOKIE['settings'])) : ?>
			var settings = {
				defaultIVs: "<?php echo htmlspecialchars($_SETTINGS->defaultIVs); ?>",
				animateTimeline: <?php echo htmlspecialchars($_SETTINGS->animateTimeline); ?>,
				matrixDirection: "<?php echo htmlspecialchars($_SETTINGS->matrixDirection); ?>"
			};

		<?php else: ?>

			var settings = {
				defaultIVs: "gamemaster",
				animateTimeline: 1,
				matrixDirection: "row"
			};

		<?php endif; ?>

		// If $_GET request exists, output as JSON into Javascript
		<?php
			foreach($_GET as &$param) {
				$param = htmlspecialchars($param);
			}

			if ($_GET) {
				echo 'var get = ' . json_encode($_GET) . ';';
			} else {
				echo 'var get = false;';
			}
		?>
	</script>
</head>

<body>
	<!-- start of header -->
	<header class="header">
		<!-- start of nav -->
		<nav class="nav">
			<!-- start of nav list -->
			<ul class="nav__list">
				<li class="nav__item">
					<a class="nav__link nav__link--brand" href="/pvpoke/src">
						<img class="nav__logo" src="/pvpoke/src/img/logos/logo-192px.png" alt="PvPoke logo">
						<h1 class="nav__heading">PvPoke</h1>
					</a>
				</li>
				<li class="nav__item">
					<a class="nav__link" href="<?php echo $WEB_ROOT; ?>battle/">Battle</a>
				</li>
				<li class="nav__item">
					<a class="nav__link" href="<?php echo $WEB_ROOT; ?>train/">Train</a>
				</li>
				<li class="nav__item nav__item--dropdown">
					<a class="nav__link" href="<?php echo $WEB_ROOT; ?>rankings/">Rankings</a>
					<ul class="nav__dropdown">
						<li class="nav__dropdown__item">
							<a class="nav__dropdown__link" href="<?php echo $WEB_ROOT; ?>custom-rankings/">Custom Rankings</a>
						</li>
					</ul>
				</li>
				<li class="nav__item">
					<a class="nav__link" href="<?php echo $WEB_ROOT; ?>team-builder/">Team Builder</a>
				</li>
				<li class="nav__item">
					<a class="nav__link" href="<?php echo $WEB_ROOT; ?>moves/">Moves</a>
				</li>
				<li class="nav__item">
					<a class="nav__link" href="<?php echo $WEB_ROOT; ?>articles/">Articles</a>
				</li>
				<li class="nav__item">
					<a class="nav__link" href="<?php echo $WEB_ROOT; ?>contribute/">Contribute</a>
				</li>
			</ul> <!-- end of nav list -->
		</nav> <!-- end of nav -->
		
		<div class="hamburger">
			<div class="meat"></div>
			<div class="meat"></div>
			<div class="meat"></div>
		</div>
	</header> <!-- end of header -->

	<div class="main-wrap">
		<div id="main">