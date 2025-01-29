<?php
function createFolders($string){
    if(!file_exists($string)){
        mkdir($string);
    }else{
        try{
            rmdir($string);
        }catch(Exception $e){
            echo $e->getMessage();
        }finally{
            
            mkdir($string);
        }
    }
}
function CreateFile($location, $type, $text){
    $file = fopen($location, $type);
    fwrite($file, $text);
    fclose($file);
}
function ReadFiles($location){
    $text = file_get_contents($location);
    return $text;
}
function CreatePages($pageName, $table, $addButton, $colnames, $folder){
    $file = fopen($folder."/pages/$pageName.php", "w+");
 $text = "
 <style>
    table img{
        object-fit: contain;
        height:100px;
        aspect-ratio: 1/1;
    }
</style>
<h3>$pageName</h3>
<!--<button class='btn btn-success float-end mb-2' onclick='window.location = `./pages/Add$table.php`'>Add</button>-->
    <table id='table' class='Table'>
        <thead>
        <tr>\n";
        $i = 0;
        if($colnames[$i] != null || $colnames != ""){
        while($i < count($colnames)){
                $text .= "\t\t\t\t<th>\n\t\t\t\t\t$colnames[$i]\n\t\t\t\t</th>\n";
                $i++;
            }
        }
        
        $text .= "
                <th>
                    Actions
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
         //  \$$pageName = new $pageName();
         //   \$items = \$$pageName"."->"."get$table();
         include './../include/validator.php';
         \$items = get$table(\$db);
            foreach(\$items as \$item){\n";
                $i = 0;
                if($colnames[$i] != null || $colnames != ""){
                while($i < count($colnames)){
                        $text .= "\t\t\t\t\$".$colnames[$i]."= \$item['".$colnames[$i]."'];\n";
                        $i++;
                    }
                }
                $text .= "\t\t\t\techo \"
                <tr>\n";
                $i = 0;
                if($colnames[$i] != null || $colnames != ""){
                while($i < count($colnames)){
                        $text .= "\t\t\t\t<td>\$".$colnames[$i]."</td>\n";
                        $i++;
                    }
                }
                $text .= "\t\t\t\t<td>
                    <!--<button class='btn btn-primary'><i class='bi bi-pencil-square'></i></button>-->
                    <button class='btn btn-danger' onclick='drop$table(`$".$colnames[0]."`, `$".$colnames[1]."`)'><i class='bi bi-x'></i></button>
                    </td>
                </tr>
                \";
            }
            ?>
        </tbody>
    </table>
    <script>
        const drop$table = (id, title)=>{
            Swal.fire({
                title: `Do you want to Delete \${title}?`,
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: 'Delete',
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    fetch(`./include/validator.php?Delete$table=\${id}`).then(res=>res.text()).then(res=>{});
                    Swal.fire(`\${title} is deleted.`, '', 'success');
                    setTimeout(() => {
                      window.location.reload();                     
                    }, 1000);
                }
                })
        }
    </script>
 ";
fwrite($file, $text);
fclose($file);

// Adding Validator Functions
$validator = fopen($folder."/include/validator.php", "a+");
$func = "
    function get$table(\$db){       
        \$sql = \$db->query('SELECT * FROM $table');
        \$list = [];
        \$i = 0;
        foreach(\$sql as \$data){
            \$list[\$i++] = \$data;
        }
        return \$list;
    }
    if(isset(\$_REQUEST['Delete$table'])){
    \$id = \$_REQUEST['Delete$table'];
    \$db->query('DELETE FROM $table WHERE ".$colnames[0]." = ? limit 1', [\"\$id\"]);
    }
    if(isset(\$_REQUEST['post$table'])){\n";
    $i = 0;
    if($colnames[$i] != null || $colnames != ""){
        while($i < count($colnames)){
            $func .= "\t$".$colnames[$i]." = \$_REQUEST['".$colnames[$i]."'];\n";
            $i++;
        }
    }
    $func .= "
    \$db->query(\"INSERT INTO $table (";
    $i = 0;
    if($colnames[$i] != null || $colnames != ""){
        while($i < count($colnames)){
            isset($colnames[$i + 1]) ? $func .= "$colnames[$i], " : $func .= "$colnames[$i]";      
            $i++;
        }
    }
    $func .= ") VALUES (
    ";
    $i = 0;
    if($colnames[$i] != null || $colnames != ""){
        while($i < count($colnames)){
            isset($colnames[$i + 1]) ? $func .= "'\$$colnames[$i]', " : $func .= "'\$$colnames[$i]'";      
            $i++;
        }
    }
    $func .= ")\");
    setcookie('".$table."Upload', 'success', time()+15, '/');
    header('Location: ./../pages/Add$table.php');
    }
    ";
fwrite($validator, $func);
fclose($validator);

// Create Insert Pages
//$insertPage = fopen($folder."/pages/Add$table.php", "w+");
$insertText = "<!DOCTYPE html>
<html lang='en'>
<head>
    <?php include './Head.php';?>
    <title>Add $table</title>
    <style>
        main{
            height:100vh;
        }
        form{
            width:30vw;
        }
        input {
            margin-bottom:1em;
        }
    </style>
</head>
<body>
    <main class='d-flex justify-content-center align-items-center'>
        <form action='./../include/validator.php' method='POST' enctype='multipart/form-data'>
            <h4>Adding a New $table</h4>
            ";
            $i = 0;
                if($colnames[$i] != null || $colnames != ""){
                while($i < count($colnames)){
                        $insertText .= "\t\t\t\t<section class='form-floating'>
                        <input type='text' class='form-control' name='".$colnames[$i]."' id'".$colnames[$i]."' placeholder='i' required/>
                        <label for='".$colnames[$i]."'>".$colnames[$i]."</label>
                        </section>\n";
                        $i++;
                    }
                }
            $insertText .= "<section class='form-floating'>
                <button class='btn btn-success' name='post$table'>Add</button>
                <section class='btn btn-primary' onclick='window.location = `./../index.php`'>Home</section>
            </section>
            <?php 
            if(isset(\$_COOKIE['".$table."Upload']) && \$_COOKIE['".$table."Upload'] == 'success'){
                echo '
                <section class="."text-success".">Uploaded Successfully!!</section>
                ';
            }
            ?>
        </form>
    </main>
</body>
</html>";
//fwrite($insertPage, $insertText);
//fclose($insertPage);

// Create Edit Pages || NOT FINISHED
//$editPage = fopen($folder."/pages/Edit$table.php", "w+");
$editText = "
<!DOCTYPE html>
<html lang='en'>
<head>
    <?php include './Head.php';?>
    <title>Edit $table</title>
    <style>
        main{
            height:100vh;
        }
        form{
            width:30vw;
        }
        input {
            margin-bottom:1em;
        }
    </style>
</head>
<body>
    <main class='d-flex justify-content-center align-items-center'>
        <form action='./../include/validator.php' method='POST' enctype='multipart/form-data'>
                <h4>Edit <?php echo \$_REQUEST['item']?></h4>
                <input type='hidden' name='id' value='<?php echo \$_REQUEST['id']; ?>'>
                <section class='form-floating'>
                    <input type='text' class='form-control' name='name' id='name' placeholder='i' required value='<?php echo \$_REQUEST['title'] ?>'/>
                    <label for='name'>Title</label>
                </section>           
                <section class='form-floating'>
                    <button class='btn btn-success' name='update$table'>Update</button>
                    <section class='btn btn-primary' onclick='window.location = `./../index.php`'>Home</section>
                </section>
                <?php 
                if(isset(\$_COOKIE['".$table."Update']) && \$_COOKIE['".$table."Update'] == 'success'){
                    echo '
                    <section class='text-success'>Updated Successfully!!</section>
                    ';
                }
                ?>
            </form>
    </main>
</body>
</html>
";
//fwrite($editPage, $editText);
//fclose($editPage);
}
?>