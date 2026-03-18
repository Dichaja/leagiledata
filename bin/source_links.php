
<?php

require_once __DIR__ . '/../bin/functions.php';

?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($page_title) ? $page_title : 'Leagile Data Research Center'; ?></title>
<meta name="description" content="<?php echo isset($page_description) ? $page_description : 'Leagile Data Research Center provides research, data analysis, and expert consultation services.'; ?>">
<link rel="canonical" href="<?php echo isset($page_canonical) ? $page_canonical : 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" />
<meta name="robots" content="index, follow">
<meta property="og:title" content="<?php echo isset($page_title) ? $page_title : 'Leagile Data Research Center'; ?>" />
<meta property="og:description" content="<?php echo isset($page_description) ? $page_description : 'Leagile Data Research Center provides research, data analysis, and expert consultation services.'; ?>" />
<meta property="og:type" content="<?php echo isset($page_og_type) ? $page_og_type : 'website'; ?>" />
<meta property="og:url" content="<?php echo isset($page_og_url) ? $page_og_url : 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" />
<meta property="og:image" content="<?php echo isset($page_og_image) ? $page_og_image : BASE_URL . '/img_data/logo_fav.png'; ?>" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="<?php echo isset($page_title) ? $page_title : 'Leagile Data Research Center'; ?>" />
<meta name="twitter:description" content="<?php echo isset($page_description) ? $page_description : 'Leagile Data Research Center provides research, data analysis, and expert consultation services.'; ?>" />
<meta name="twitter:image" content="<?php echo isset($page_og_image) ? $page_og_image : BASE_URL . '/img_data/logo_fav.png'; ?>" />
<link rel="stylesheet" href="<?php echo BASE_URL ?>/bin/style.css">
<link rel="stylesheet" href="<?php echo BASE_URL ?>/bin/menu.css">
<link rel="stylesheet" href="<?php echo BASE_URL ?>/bin/tail.css">
<link rel="icon" type="image/png" href="<?php echo BASE_URL ?>/img_data/logo_fav.png" />
<script src="https://cdn.tailwindcss.com"></script>
<!--<link href="https://unpkg.com/aos@next/dist/aos.css" rel="stylesheet">-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/> 