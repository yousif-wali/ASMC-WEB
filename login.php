<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" integrity="sha384-b6lVK+yci+bfDmaY1u0zE8YYJt0TZxLEAFyYSLHId4xoVvsrQu3INevFKo+Xir8e" crossorigin="anonymous">
<script src="https://www.w3schools.com/lib/w3.js"></script> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<link href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.css" rel="stylesheet" type="text/css">
<script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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
    <main class="d-flex justify-content-center align-items-center">
        <form action="./validator.php" method="POST">
            <h4>Login</h4>
            <section class="form-floating">
                <input id="username" class="form-control mt-3" placeholder="" name="name" required/>
                <label for="username">Username</label>
            </section>
            <section class="form-floating mt-3">
                <input type="password" id="pwd" class="form-control" placeholder="" name="pwd" required/>
                <label>Password</label>
            </section>
            <section class="mt-3">
                <button class="btn btn-success" name="login">Log in</button>
            </section>
            <section>
                <span>Do not have an account? <a href="./Signup.php">Create One</a></span>
            </section>
        </form>
    </main>
</body>
</html>