<?php

if(!session_start()) die();
require_once 'ddb.php';

if ( ends_with( $_SERVER['REQUEST_URI'], '/signout' ) ) {
 unset($_SESSION['logged_in']);
 session_destroy();
 header('location: /');
}

define( 'NOTHING', false );

$_SESSION['logged_in'] = isset($_SESSION['logged_in']) ? $_SESSION['logged_in'] : false ;
define( 'LOGDIN',  $_SESSION['logged_in']);
if (LOGDIN) {
	$userid = $_SESSION['userid'];
	$username = $_SESSION['username'];
	$userlevel = $_SESSION['userlevel'];
	$userfname = $_SESSION['userfname'];
	$userlname = $_SESSION['userlname'];
	$useremail = $_SESSION['useremail'];
	$is_admin = ($userlevel==2);
	define('ISADMIN', $is_admin);
    define('USEREMAIL', $useremail);
    define('USERLEVEL', $userlevel);
    define('USERNAME', $username);
    define('USERID', $userid);
    define('USERFNAME', $userfname);
    define('USERLNAME', $userlname);
}

#ensuring string ends_with compatibility for php versions that do not the funtion str_ends_with
function ends_with($h, $n){
 return substr_compare($h,$n,-strlen($n))===0;
}

#
function isp($p)
{
	return (isset($_POST[$p]) && !empty($_POST[$p]) && ($_POST[$p] != '' ));
}

require __DIR__ . '/header1.html';
if(LOGDIN){ ?>
	 
	<script>
		document.getElementById("dlink").innerHTML = '<a class="nav-item dlink" href="/dashboard">Dashboard</a>';
		</script>

<?php }

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$patharr = explode("/", $path);
$keypath = end($patharr);

switch ($keypath) {
	case 'directory':
		require __DIR__ . '/directory.php';
		break;
	
	case 'signin':
		if (isp('email')&&isp('password')) {
	 	
		 	if (authUser($_POST['email'], $_POST['password'], $pdo)) {
		 		$_SESSION['logged_in'] = true;
		 		if ($_POST['redirect']=='1') {
		 			//echo "<br><br><br>".$uri.'/addbusiness';
		 			header('Location: /addbusiness');
		 			die();
		 		}
		 		//echo "<br><br><br>".$uri.'/addbusiness';
		 		header('Location: /dashboard');
		 		die();
		 	}
		 	require 'signin.html';
		 	echo "<script>document.getElementById('alert').innerHTML='Authentication failed, please check your details.';
		 			document.getElementById('redirect').value='". $_POST['redirect'] ."';
		 		</script>";
		 }else{
		 	require __DIR__ . '/signin.html';
		 }
		break;
	
	case 'dashboard':
		$dashboard = true;
		require __DIR__ . '/dashboard.php';
		if (isset($_SESSION['message'])) {
		 	echo "<script>alert('".$_SESSION['message']."')</script>";
		 	unset($_SESSION['message']);
		}
		break;
	
	case 'profile':
		$profile = true;
		require __DIR__ . '/dashboard.php';
		break;
	
	case 'v':
		$id = explode('/',$_SERVER['REQUEST_URI'])[1];
		require __DIR__ . '/businessview.php';
		break;
	
	case 'remove':
		$id = explode('/',$_SERVER['REQUEST_URI'])[1];
		removeListing($id, $pdo);
	 	$_SESSION['message'] = "Business removed" . $image_message;
	 	header('Location: '.$uri.'/dashboard');
		break;
	
	case 'addbusiness':
		 if (!LOGDIN) {
		 	require 'signin.html';
		 	echo "<script>document.getElementById('alert').innerHTML='Sign in to add a business!';
		 			document.getElementById('redirect').value='1';
		 		</script>";
		 }
		 elseif (isp('business_name')) {
		 	if (isp('category')&&isp('address')&&isp('tel_1')&&isp('description')) {
		 		$image_message = '';
		 		$image = null;
		 		$hasimage = false;
		 		$imageerror = true;
		 		require 'image_upload.php';
		 		if (isset($_FILES['imag'])) {
		 			$image = $_FILES['imag'];
		 			$hasimage = true;
		 			$image = save_image($image, str_replace(' ', '', $_POST['business_name']));
			 		if ($image) {
			 			$imageerror = false;
			 		}
		 		}
		 		
		 		putListing($_POST['business_name'], $_POST['category'], $_POST['address'], $_POST['tel_1'], $_POST['tel_2'], $_POST['longitude'], $_POST['latitude'], $_POST['description'],  $_POST['products'], $_POST['openhours'], $image, null, $userid, $pdo);
		 		$_SESSION['message'] = "Business Added" . $image_message;
		 		header('Location: '.$uri.'/');
		 	} else {
		 		echo "<script>alert('Some required fields not filled')</script>";
		 		require __DIR__ . '/addbusiness.html';
		 		require __DIR__ . '/categories_span.php';
		 	}
		 }
		 else{
			$categories = getHomeCategories($pdo);
		 	require __DIR__ . '/addbusiness.html';
		 	require __DIR__ . '/categories_span.php';
		 }
		break;
	
	case 'adduser':
		if (isp('fname')&&isp('lname')&&isp('email')&&isp('password')) {
		 	putUser($_POST['fname'], $_POST['lname'], $_POST['username'], $_POST['email'], $_POST['password'], 1, $pdo);
		 	$_SESSION['message'] = "Success, proceed to sign in";
		 	header('Location: '.$uri.'/');
		 } else {
		 	require __DIR__ . '/signup.html';
		 }
		break;
	
	case 'updateprofile':
		 if (isp('fname')&&isp('lname')&&isp('email')) {
		 	
		 	updateUserDetails($userid, $_POST['fname'], $_POST['lname'], $_POST['username'], $_POST['email'], $pdo);
		 	$_SESSION['message'] = "Success";
		 	header('Location: /dashboard');
		 } else {
		 	
		 	?>
		 	<br>
		 	<br>
		 	<p> Some required fields not filled </p><br>
		 	<a href="/editprofile">Return &lt; </a>
		 	<?php
		 }
		break;
	
	case 'signup':
		require __DIR__ . '/signup.html';
		break;
	
	case 'password':
		$editpassword = true;
		require __DIR__ . '/dashboard.php';
		break;
	
	case 'editprofile':
		$editprofile = true;
		require __DIR__ . '/dashboard.php';
		break;
	
	case 'updatebusiness':
		if (isp('business_name')) {
		 	if (isp('category')&&isp('address')&&isp('tel_1')&&isp('description')&&isp('id')) {
		 		
		 		updateListing($_POST['id'], $_POST['business_name'], $_POST['category'], $_POST['address'], $_POST['tel_1'], $_POST['tel_2'], $_POST['longitude'], $_POST['latitude'], $_POST['description'], $_POST['products'], $_POST['openhours'], null, getListing($_POST['id'],$pdo)['owner'], $pdo);
		 		$_SESSION['message'] = "Success";
		 		header('Location: /dashboard');

		 	} else {
		 		echo "<script>alert('Some required fields not filled')</script>";
		 		require __DIR__ . '/addbusiness.html';
		 		require __DIR__ . '/editbusiness.php';
		 		require __DIR__ . '/categories_span.php';
		 	}
		 }
		break;
	
	case 'editbusiness':
		$id = explode('/', $path)[1];
		 require __DIR__ . '/addbusiness.html';
		 require __DIR__ . '/editbusiness.php';
		 require __DIR__ . '/categories_span.php';
		break;
	
	case 'home':
	case '':
		require __DIR__ . '/home.php';
		 if (isset($_SESSION['message'])) {
				 	echo "<script>alert('".$_SESSION['message']."')</script>";
				 	unset($_SESSION['message']);
				}
		break;
	
	default:
		# code...
		break;
}

require __DIR__ . '/footer1.html';