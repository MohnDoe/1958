<?php
/*

*/
$__START = microtime(true);
session_set_cookie_params(0, '/');
$oid = null;
if(isset($_COOKIE['PHPSESSID'])){
	$oid=$_COOKIE['PHPSESSID'];
}
session_start($oid);
header('Content-Type: text/html; charset=UTF-8');

DEFINE('START_TIME',microtime(true));
// mailChimp
DEFINE('MC_API_KEY', '30a308783a9b275034918218453a741a-us7');
DEFINE('MC_IDLIST_ASKBETA', 'b7d0f9e131');
DEFINE('MC_IDLIST_MEMBERS', 'e8055be1dc');

DEFINE('RATIO_POP_MULTIPLI', 0.99519804434435373165003884241728);

DEFINE('JOHTO_FOLDER', "/johto");

DEFINE('PART_ONE_LINK_GAME', 'game');
DEFINE('PART_ONE_LINK_PROFIL_USER', 'profil');
DEFINE('PART_ONE_LINK_CRT', 'review');
DEFINE('PART_ONE_LINK_MCCRT', 'micro-review');
DEFINE('PART_ONE_LINK_LIST', 'list');
DEFINE('PART_ONE_LINK_BADGE', 'badge');

DEFINE('TIMEOUT_REVOTE_GAMEMASH', 20); // en minutes
DEFINE('SCORE_FIRST_TIME_GAMEMASH', 1400);

// connexion db
if($_SERVER["REMOTE_ADDR"] != "127.0.0.1"){
	// online
	// DEFINE('PREFIX_URL_RELATIF', '.');
	DEFINE('PREFIX_URL_RELATIF', '.');
	DEFINE('DOMAINE_COOKIE', 'the1958.fr');
	DEFINE('DOMAINE_COOKIE_JOHTO', 'johto.the1958.fr');
	DEFINE('PROJECT_FOLDER', "");
	DEFINE('HOSTNAME', 'db488162606.db.1and1.com');
	DEFINE('DBNAME', 'db488162606');
	DEFINE('USER_DB', 'dbo488162606');
	DEFINE('PASS_DB','DEta7ruceyeDES6e6apasUHuCHafE@uf');
	DEFINE('ROOTDEV', '/kunden/homepages/45/d399347765/htdocs/dev1958/');
	DEFINE('ROOT', '/kunden/homepages/45/d399347765/htdocs/1958/');
	// DEFINE('ROOT', ROOTDEV);
	if($_SERVER['REQUEST_URI'] == "/" || $_SERVER['REQUEST_URI'] == ""){
		DEFINE('REDIRECT_BASIC_LOG', 'http://join.the1958.fr/');
	}else{
		DEFINE('REDIRECT_BASIC_LOG', 'http://login.the1958.fr/?redirect='.$_SERVER['REQUEST_URI']);
	}
	DEFINE('STATIC_URL', '/static');
	// DEFINE('STATIC_URL', '/new1958/static');

	DEFINE('PREFIX_URL', "http://s399347779.onlinehome.fr/1958");
}else{
	// local
	$root = $_SERVER["DOCUMENT_ROOT"];
	if($root[strlen($root)-1] == "/"){
		$root = substr($root, 0, strlen($root)-1);
	}
	DEFINE('DOMAINE_COOKIE', null);
	DEFINE('DOMAINE_COOKIE_JOHTO', null);
	DEFINE('ROOT', $root);
	DEFINE('PROJECT_FOLDER', "/new1958");
	DEFINE('HOSTNAME', 'localhost');
	DEFINE('DBNAME', 'db1958');
	DEFINE('USER_DB', 'root');
	DEFINE('PASS_DB','');
	DEFINE('REDIRECT_BASIC_LOG', PROJECT_FOLDER.'/login/?redirect='.$_SERVER['REQUEST_URI']);
	DEFINE('STATIC_URL', '');
	DEFINE('PREFIX_URL_RELATIF', '.');
	DEFINE('PREFIX_URL', PREFIX_URL_RELATIF);
}
/* FOLDERS */
DEFINE('WEBROOT', ROOT.PROJECT_FOLDER);
DEFINE('FOLDER_IMGS', STATIC_URL.'/img');
DEFINE('FOLDER_USERS_PICTURES', FOLDER_IMGS.'/u');
DEFINE('FOLDER_GAMES_COVERS', FOLDER_IMGS.'/j');
DEFINE('FOLDER_BADGES', FOLDER_IMGS.'/b');
DEFINE('FOLDER_SMALLBADGES', FOLDER_IMGS.'/smallbadges');

DEFINE('PATH_USERS_PICTURES_FOLDER', WEBROOT.FOLDER_USERS_PICTURES);

DEFINE('PATH_GAMES_COVERS_FOLDER', WEBROOT.FOLDER_GAMES_COVERS);
// DEFINE('PATH_GAMES_COVERS_FOLDER', 'http://the1958.fr/static/img/j');

DEFINE('PATH_BADGES_FOLDER', WEBROOT.FOLDER_BADGES);

date_default_timezone_set('Europe/Paris');

$__ALLOWED_VIEWS_GAME = Array("all", "critiques", "micros-critiques", "listes", "screenshots", "videos");
$__ALLOWED_TYPE_GAMESWALL = Array("pop", "soon", "random");

require "misc.func.php";

require "class.db.php";
require "class.game.php";
require "class.user.php";
require "class.developer.php";
require "class.editor.php";
require "class.platform.php";
require "class.microcritique.php";
require "class.listgames.php";
require "class.critique.php";
require "class.gameswall.php";
require "class.badge.php";
require "class.notification.php";
require "class.comment.php";
require "class.pyong.php";
?>