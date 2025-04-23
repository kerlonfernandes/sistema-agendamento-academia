<?php

define("ADMIN_SITE", SITE."/admin");
define("URL", "https://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI'] . "?"));

// resources
define('SRC', SITE.'/public/src');
define('SRC_ADMIN', ADMIN_SITE.'/public/src');

define('JS', SRC.'/js');
define('JS_ADMIN', SRC_ADMIN.'/js');

define('CSS', SRC.'/css');
define('CSS_ADMIN', SRC_ADMIN.'/css');

define('ASSETS_ADMIN', SRC_ADMIN.'/assets');
define('ASSETS', SRC.'/assets');

define('VENDOR', SRC.'/vendor');

// intern urls
define('APP', SITE."/app");
define('UPLOAD', APP.'/uploads');
define('PDF', UPLOAD."/pdf");
define('DOCS', UPLOAD."/documentos");
define('PROCURACOES', UPLOAD."/procuracoes");
define('IMAGES', SRC."/images");