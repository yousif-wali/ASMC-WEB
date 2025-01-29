<?php
session_start();
try{
$connect = mysqli_connect("localhost", "user" , "password", "database");
}catch(Exception $e){
    echo $e->getMessage();
}
if(isset($_POST['login'])){
    $name = mysqli_real_escape_string($connect, $_POST['name']);
    $pwd = mysqli_real_escape_string($connect, $_POST['pwd']);
    
    $userPassword = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM users WHERE Username = '$name'"))['Password'];
    if(password_verify($pwd, $userPassword)){
        $_SESSION['username'] = $name;
        header("Location: ./default.php");
    }else{
        echo "Wrong Password or Username";
    }
}
if(isset($_POST['signup'])){
    $name = mysqli_real_escape_string($connect, $_POST['name']);
    $pwd = mysqli_real_escape_string($connect, $_POST['pwd']);
    $cpwd = mysqli_real_escape_string($connect, $_POST['cpwd']);
    
    try{
        if(strlen($pwd) < 8){
            throw new Exception("Short Password");
        }elseif($pwd !== $cpwd){
            throw new Exception("Password Do not match");
        }else{
            $opts03 = [ "cost" => 15 ];
            $hash = password_hash($pwd, PASSWORD_BCRYPT, $opts03);
            mysqli_query($connect, "INSERT INTO users (Username, Password) values ('$name', '$hash')");
            $_SESSION['username'] = $name;
            header("Location: ./default.php");
        }
    }catch(Exception $e){
        echo $e->getMessage();
    }
}
if(isset($_REQUEST["logout"])){
    session_unset();
    session_destroy();
    echo "session reset";
}
include "./functions.php";
$user = $_SESSION['username'];
$folder = "./projects/$user";
createFolders("./projects");
if(file_exists($folder)){
    rmdir($folder);
}
createFolders($folder);
if(isset($_POST['generate'])){
    // Database Portion
    $DatabaseHost = $_POST['DatabaseHost'];
    $DatabaseUser = $_POST['DatabaseUser'];
    $DatabasePassword = $_POST['DatabasePassword'];
    $DatabaseName = $_POST['DatabaseName'];
    if($DatabasePassword == " "){
        $DatabasePassword = "";
    }
    try{
        // Create Folders
        createFolders($folder."/include");
        createFolders($folder."/pages");
        createFolders($folder."/src");
        createFolders($folder."/src/styles");
        createFolders($folder."/src/images");
        createFolders($folder."/src/scripts");
        
        // Essentials
        require "./include/validatorPage.php";
        require "./include/databasePage.php";
        createFile($folder.'/index.php', "w+", ReadFiles("./txt/index.txt"));

        // Setting up pages for index
        $pagenames = $_REQUEST['pagename'];
        $pagetable = $_REQUEST['pagetable'];
        foreach($pagenames as $pagename){
            $file = fopen($folder."/index.php", "a+");
            $text = "<li class='list-group-item list-group-item-action'>$pagename</li>";
            fwrite($file, $text);
            fclose($file);
        }
        $file = fopen($folder."/index.php", "a+");
        $text = " </ol>
        </aside>
        <main class='col-9' data-type='selection'>";
        fwrite($file, $text);
        fclose($file);
        $pagerCounter = 0;
        foreach($pagenames as $pagename){
            $file = fopen($folder."/index.php", "a+");
            $text = "<section>
            <?php include './pages/$pagename.php';?>
        </section>";
            fwrite($file, $text);
            fclose($file);
            CreatePages($pagename, $pagetable[$pagerCounter], true, $_REQUEST["PT".$pagerCounter."COL"], $folder);
            $pagerCounter++;
        }

        // Create .htaccess
        createFile($folder."/.htaccess", "w+", 
        "RewriteEngine on
        RewriteRule ^Home ./index.php
        RewriteRule ^Login ./pages/Login.php
        RewriteRule ^SecuredLogin ./include/validator.php");
        // Pages
        createFile($folder."/pages/Head.php", "w+", ReadFiles("./txt/Head.txt"));
        // Sources
        copy("./copy/style.css", $folder."/src/styles/style.css");
        copy("./copy/script.js", $folder."/src/scripts/script.js");

        // Login Page
        $loginPage = fopen($folder."/pages/Login.php", "w+");
        $loginText = "
        <!DOCTYPE html>
<html lang='en'>
<head>
    <?php include './Head.php';?>
    <title>Login</title>
    <style>
        main{
            height:100vh;
        }
        form{
            width:30vw;
        }
    </style>
</head>
<body>
    <main class='d-flex justify-content-center align-items-center'>
        <form action='./include/validator.php' method='POST'>
            <h4>Login</h4>
            <section class='form-floating'>
                <input id='username' class='form-control mt-3' placeholder='' name='name' required/>
                <label for='username'>Username</label>
            </section>
            <section class='form-floating mt-3'>
                <input id='pwd' class='form-control' placeholder='' name='pwd' required/>
                <label>Password</label>
            </section>
            <section class='mt-3'>
                <button class='btn btn-success' name='login'>Log in</button>
            </section>
            <?php 
            if(isset(\$_COOKIE['loginerr'])){
                echo \"<p class='text-danger'>Username OR Email is incorrect!</p>\";
            }
            ?>
        </form>
    </main>
</body>
</html>
        ";
        fwrite($loginPage, $loginText);
        fclose($loginPage);
        // Login Password Generator
        if(isset($_REQUEST['staticUsername'])){
            $username = $_REQUEST['staticUsername'];
            $pwd      = $_REQUEST['staticPassword'];
            $validator = fopen($folder."/include/validator.php", "a+");
            $validatorText = "
            if(!isset(\$_SESSION['Admin'])){session_start();}
            if(isset(\$_REQUEST['login'])){
            if(\$_REQUEST['name'] == '$username' && \$_REQUEST['pwd'] == '$pwd'){
                \$_SESSION['Admin'] = '$username';
                header('Location: ./../index.php');
            }
        }
        ";
            fwrite($validator, $validatorText);
            fclose($validator);
        }else if(isset($_REQUEST['dynamicTableName'])){
            $table  = $_REQUEST['dynamicTableName'];
            $col    = $_REQUEST['dynamicColumnName'];
            $cond   = $_REQUEST['dynamicCondition'];
            $username = $_REQUEST['dynamicUserName'];
            $pwd      = $_REQUEST['dynamicPassword'];
            $eval       = $_REQUEST['dynamicConditionEval'];
            
            $sign = '=';
            switch($eval){
                case 'equal':
                    $sign = '=';
                break;
                case 'greater':
                    $sign = '>';
                break;
                case 'lesser':
                    $sign = '<';
                break;
            }
            CreateFile($folder."/include/validator.php", "a+",
            "if(!isset(\$_SESSION['Admin'])){session_start();}
            if(isset(\$_REQUEST['login'])){
                \$name = \$_REQUEST['name'];
                \$pwd  = \$_REQUEST['pwd'];
                \$Exists = \$db->query(\"SELECT * FROM $table WHERE $username = '\$name' and $col $sign '$cond'\");
                if(count(\$Exists[0]) > 0){
                    \$correctpwd = \$Exists[0]['$pwd'];
                    if(password_verify(\$pwd, \$correctpwd)){
                        \$_SESSION['Admin'] = '$username';
                        header('Location: ./../index.php');
                    }else{
                        setcookie('loginerr', 'err', time()+15, '/');
                        header('Location: ./../Login');
                    }
                }else{
                    setcookie('loginerr', 'err', time()+15, '/');
                    header('Location: ./../Login');
                }
            }");
        }
        CreateFile($folder."/include/validator.php", "a+", "if(isset(\$_REQUEST['logout'])){session_unset();session_destroy();header('Location: ./../Login');}");
        // Wrap up
        createFile($folder."/index.php", "a+", ReadFiles("./wrapup/index.php"));
    }catch(Exception $e){
        echo "Something went wrong!!";
    }finally{
        
        echo "The process is stopped";
        include "./zip.php";
        zip($folder);
        rmdir($folder);
        header("Location: ./download.php?folder=".$folder);
    }
}
?>