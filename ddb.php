<?php
//session_start();
/* Connect Database */
require_once 'config.php';

$host = DB_HOST;
$user = DB_USER;
$passwd = DB_PASSWORD;
$dbn = DB_NAME;

$pdo = NULL;
$mdb = 'mysql:host=' . $host . ';dbname=' . $dbn;

$pdo = null;

try
{
    $pdo = new PDO($mdb, $user, $passwd);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    /* If there is an error, an exception is thrown. */
    echo 'Database connection failed.' . $e;
    die();
}

function getUser($b, $pdo) {
    $query = 'SELECT * FROM users WHERE (user_email = :umail)';
    $values = [':umail' => $b];
    try {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }catch (PDOException $e) {
        /* Query error. */
        echo 'error' . $e;
        die();
    }

    return $res->fetch(PDO::FETCH_ASSOC);
}

function gettempUser($b, $pdo) {
    $query = 'SELECT * FROM tempaccounts WHERE (user_email = :umail)';
    $values = [':umail' => $b];
    try {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }catch (PDOException $e) {
        /* Query error. */
        echo 'error' . $e;
        die();
    }
    return $res->fetch(PDO::FETCH_ASSOC);
}


function checkMail($e, $pdo){
    $row = getUser($e, $pdo);
    if(!empty($row)){
        define('USERPASS', $row['user_pass']);
        define('USEREMAIL', $row['user_email']);
        define('USERLEVEL', $row['user_level']);
        define('USERDISPLAYNAME', $row['display_name']);
        define('USERID', $row['id']);
        define('USERFNAME', $row['firstname']);
        define('USERLNAME', $row['lastname']);
        return '2';
    }
    return '0';
}

function authUser($e, $p, $pdo){
    if (checkMail($e,$pdo)=='2') {
        if (password_verify($p, USERPASS)) {
            $_SESSION['userid'] = USERID;
            $_SESSION['username'] = USERDISPLAYNAME;
            $_SESSION['userlevel'] = USERLEVEL;
            $_SESSION['userfname'] = USERFNAME;
            $_SESSION['useremail'] = USEREMAIL;
            $_SESSION['userlname'] = USERLNAME;
            return true;
        }        
    }
    return false;
}

function getUserById($b, $pdo) {
    $query = 'SELECT * FROM users WHERE (ID = :umail)';
    $values = [':umail' => $b];
    try {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }catch (PDOException $e) {
        /* Query error. */
        echo 'error' . $e;
        die();
    }

    return $res->fetch(PDO::FETCH_ASSOC);
}

function putAdmin($fn, $ln, $dsp, $em, $pwd, $pdo) {
    $dsp = ($dsp==NULL) ? $fn : $dsp;
    $pwd = password_hash($pwd, PASSWORD_DEFAULT);
    $level = 2;
    $query = 'INSERT INTO users (firstname, lastname,  display_name, user_email, user_pass, user_level) VALUES (:fn, :ln, :dsp, :email, :passwd, :level)';
    $values = [
        ':level' => $level,
        ':passwd' => $pwd,
        ':fn' => $fn,
        ':dsp' => $dsp,
        ':ln' => $ln,
        ':tel' => $tel,
        ':email' => $em
    ];
    try
    {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }
    catch (PDOException $e) {
        /* Query error. */
        echo 'Error, User May Already Exist' . $e;
        die();
    }


}

function putUser($fn, $ln, $dsp, $em, $pwd, $level, $pdo) {
    $dsp = ($dsp==NULL) ? $fn : $dsp;
    $pwd = password_hash($pwd, PASSWORD_DEFAULT);
    $query = 'INSERT INTO users (firstname, lastname,  display_name, user_email, user_pass, user_level) VALUES (:fn, :ln, :dsp, :email, :passwd, :level)';
    $values = [
        ':level' => $level,
        ':passwd' => $pwd,
        ':fn' => $fn,
        ':dsp' => $dsp,
        ':ln' => $ln,
        ':email' => $em
    ];
    try
    {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }
    catch (PDOException $e) {
        /* Query error. */
        echo 'Error, User May Already Exist' . $e;
        die();
    }


}


function puttempUser($fn, $mn, $ln, $em, $tel, $pwd, $code, $pdo) {
    $query = 'INSERT INTO tempaccounts (firstname,  middlename, lastname, email, phone, pwd, code) VALUES ( :fn, :mn, :ln, :email, :tel, :pwd, :code)';
    $values = [
        ':code' => $code,
        ':fn' => $fn, ':mn' => $mn, ':ln' => $ln, ':tel' => $tel,
        ':email' => $em, ':pwd'=> $pwd];
    try
    {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }
    catch (PDOException $e) {
    
    $query = 'UPDATE tempaccounts SET firstname = :fn, middlename = :mn, lastname = :ln, phone = :tel, pwd = :pwd, code = :code WHERE  email = :email';
    $values = [
        ':code' => $code,
        ':fn' => $fn, ':mn' => $mn,
':ln' => $ln, ':tel' => $tel,
        ':email' => $em, ':pwd' => $pwd];
        
        try
    {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }
    catch (PDOException $e) {
        echo 'Error Occurred, Please Return to homepage and try again ' ;
    die();
    }
    
    }


}

function updateUserDetails($id, $fn, $ln, $dsp, $em, $pdo) {
    
    if (!LOGDIN || (!ISADMIN && $id!=USERID)) {
        echo 'Operation Denied for this user';
        die();
    }

    $query = 'UPDATE users SET firstname = :fn, lastname = :ln, display_name = :dsp, user_email = :email WHERE id = :id';

    $values = [
        ':fn' => $fn,
        ':dsp' => $dsp,
        ':ln' => $ln,
        ':email' => $em,
        ':id' => $id
    ];
    try
    {
        $res = $pdo->prepare($query);
        $res->execute($values);
        checkMail(USEREMAIL, $pdo);
        $_SESSION['userid'] = USERID;
        $_SESSION['username'] = USERDISPLAYNAME;
        $_SESSION['userlevel'] = USERLEVEL;
        $_SESSION['userfname'] = USERFNAME;
        $_SESSION['useremail'] = USEREMAIL;
        $_SESSION['userlname'] = USERLNAME;
    }
    catch (PDOException $e) {
        /* Query error. */
        echo 'Query error.' . $e;
        die();
    }
}

function updatePassword($id, $pwd, $pdo) {
    if (!LOGDIN || (!ISADMIN && $id!=USERID)) {
        echo 'Operation Denied for this user';
        die();
    }
    $pwd = password_hash($pwd, PASSWORD_DEFAULT);
    $query = 'UPDATE users SET user_pass = :passwd WHERE id = :uid';

    $values = [':uid' => $id,
        ':passwd' => $pwd];
    try
    {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }
    catch (PDOException $e) {
        /* Query error. */
        echo 'Query error.' . $e;
        die();
    }


}


function putListing($name, $category, $full_address, $tel_1, $tel_2, $longitude, $latitude, $description, $products, $openhours, $image, $status, $owner, $pdo){

    $query = 'INSERT INTO listings (owner, name, category, full_address, tel_1, tel_2, longitude, latitude, description, products, open_hours, image_id, status ) VALUES (:owner, :name, :category, :full_address, :tel_1, :tel_2, :longitude, :latitude, :description, :products, :open_hours, :image_id, :status)';
    
    $status = $status == null ? 'P': $status;
    $values = [
        ':owner' => $owner,
        ':name' => $name,
        ':category' => $category,
        ':full_address' => $full_address,
        ':tel_1' => $tel_1,
        ':tel_2' => $tel_2,
        ':longitude' => $longitude,
        ':latitude' => $latitude,
        ':description' => $description,
        ':products' => $products,
        ':open_hours' => $openhours,
        ':image_id' => $image,
        ':status' => $status,
        ];
    try
    {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }
    catch (PDOException $e) {
        /* Query error. */
        echo 'Query error.' . $e;
        die();
    }

}

function updateListing($id, $name, $category, $full_address, $tel_1, $tel_2, $longitude, $latitude, $description, $products, $openhours, $status, $owner, $pdo) {
    
    if (!LOGDIN || (USERLEVEL!=2 && $owner!=USERID)) {
        echo 'Operation Denied for this user';
        die();
    }
    $query = 'UPDATE listings SET name = :name, category = :category, full_address = :full_address, tel_1 = :tel_1, tel_2 = :tel_2, longitude = :longitude, latitude = :latitude, description = :description, products = :products, open_hours = :open_hours, status = :status, owner = :owner WHERE id = :id';

    $values = [
        ':owner' => $owner,
        ':name' => $name,
        ':category' => $category,
        ':full_address' => $full_address,
        ':tel_1' => $tel_1,
        ':tel_2' => $tel_2,
        ':longitude' => $longitude,
        ':latitude' => $latitude,
        ':description' => $description,
        ':products' => $products,
        ':open_hours' => $openhours,
        ':status' => $status,
        ':id' => $id,
        ];
    try
    {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }
    catch (PDOException $e) {
        /* Query error. */
        echo 'Query error.' . $e;
        die();
    }

}

function removeListing($id, $pdo) {
    $owner = getListing($id, $pdo)['owner'];
    if (!LOGDIN || (USERLEVEL!=2 && $owner!=USERID)) {
        echo 'Operation Denied for this user';
        die();
    }

    $query = 'DELETE FROM listings WHERE id = :id';

    $values = [
        ':id' => $id
        ];
    try
    {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }
    catch (PDOException $e) {
        /* Query error. */
        echo 'Query error.' . $e;
        die();
    }

}

function approveListing($id, $pdo) {
    $status = 'A';
    $query = 'UPDATE listings SET status = :status WHERE id = :id';

    $values = [':tid' => $tid, ':status' => $status];
    try
    {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }
    catch (PDOException $e) {
        /* Query error. */
        echo 'Query error.' . $e;
        die();
    }

}

function getListing($id, $pdo) {
    $query = 'SELECT * FROM listings WHERE (id = :id)';
    $values = [':id' => $id];
    try {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }catch (PDOException $e) {
        /* Query error. */
        echo 'error' . $e;
        die();
    }

    return $res->fetch(PDO::FETCH_ASSOC);
}

function getUserListings($id, $index, $pdo) {
    $query = 'SELECT * FROM listings WHERE (owner = :id) ORDER BY date_added DESC LIMIT 20 OFFSET ' . $index*20 ;
    $values = [':id' => $id];
    try {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }catch (PDOException $e) {
        /* Query error. */
        echo 'error' . $e;
        die();
    }

    return $res->fetchAll(PDO::FETCH_ASSOC);
}


function getAllListings($pdo){
    $query = 'SELECT id, name, full_address, category, tel_1, tel_2, longitude, latitude FROM listings ORDER BY date_added DESC';
    try {
        $res = $pdo->prepare($query);
        $res->execute();
    }catch (PDOException $e) {
        /* Query error. */
        echo 'error' . $e;
        die();
    }

    return $res->fetchAll(PDO::FETCH_ASSOC);

}

function getFilterListings($filter, $index, $pdo){
    $filtertext = "";
    $filtertext .= (!is_null($filter['cat'])) ? 'category='.$filter['cat'] : '' ;
    
    if(!is_null($filter['location'])){ 
        $filtertext .= ($filtertext!="") ? ' AND full_address LIKE "%'.$filter['location']. '%"' : 'full_address LIKE "%'.$filter['location']. '%"' ; 
    }
    

    if (!empty($filtertext)) {
        $filtertext = 'WHERE '.$filtertext;
    }
    $query = 'SELECT * FROM listings ' . $filtertext . ' ORDER BY date_added DESC LIMIT 20 OFFSET ' . $index;
    
    try {
        $res = $pdo->prepare($query);
        $res->execute();
    }catch (PDOException $e) {
        /* Query error. */
        echo 'error' . $e;
        die();
    }

    return $res->fetchAll(PDO::FETCH_ASSOC);

}



function getCategoryName($id,$pdo){
    $query = 'SELECT * FROM categories WHERE id='. $id;
    try {
        $res = $pdo->prepare($query);
        $res->execute();
    }catch (PDOException $e) {
        /* Query error. */
        echo 'error' . $e;
        die();
    }

    return ($res->fetch(PDO::FETCH_ASSOC))['name'];

}

function getCategories($pdo){
    $query = 'SELECT * FROM categories';
    try {
        $res = $pdo->prepare($query);
        $res->execute();
    }catch (PDOException $e) {
        /* Query error. */
        echo 'error' . $e;
        die();
    }

    return $res->fetchAll(PDO::FETCH_ASSOC);

}

function getHomeCategories($pdo){
    $query = 'SELECT * FROM categories ORDER BY RAND() LIMIT 3';
    try {
        $res = $pdo->prepare($query);
        $res->execute();
    }catch (PDOException $e) {
        /* Query error. */
        echo 'error' . $e;
        die();
    }

    return $res->fetchAll(PDO::FETCH_ASSOC);

}

function getHomeListings($pdo){
    $query = 'SELECT * FROM listings ORDER BY RAND() LIMIT 3';
    try {
        $res = $pdo->prepare($query);
        $res->execute();
    }catch (PDOException $e) {
        /* Query error. */
        echo 'error' . $e;
        die();
    }

    return $res->fetchAll(PDO::FETCH_ASSOC);

}



/*
function createtables($pdo){
    $query = 'CREATE TABLE account` ( `userID` VARCHAR(65) NOT NULL , `firstname` VARCHAR(65) NOT NULL , `middlename` VARCHAR(65) NOT NULL , `lastname` VARCHAR(65) NOT NULL , `email` VARCHAR(65) NOT NULL , `phone` VARCHAR(65) NOT NULL , `pwd` VARCHAR(65) NOT NULL , `null1` INT NULL , `null2` INT NULL , `null3` INT NULL ) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_bin;

}
*/


?>