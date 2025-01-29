<?php
    session_start();
if(!isset($_SESSION['username'])){
    header("Location: ./login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASMC Tool</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        main{
            height:100vh;
        }
        form section{
            transition:all 0.5s linear;
            width:100%;
        }
        .accordion-body > section{
            border-bottom:1px solid black;
            padding:1em;
        }
        .border-bottom{
            border-bottom:2px solid black;
        }
        #copyright{
            position:fixed;
            bottom: 1em;
            left: 1em;
            font-family: sans;
        }
    </style>
    <script>
        const logout = ()=>{
            try{
                fetch("./validator.php?logout");
                window.location.reload();
            }catch(e){
                console.error(e.message)
            }
        }
    </script>
</head>
<body>
    <section id="copyright">Created By: Yousif R Wali</section>
    <button class="btn btn-danger" style="position:fixed; bottom:1em; right:1em;" onclick="logout()">Logout</button>
    <main class="container">
        <form action="./validator.php" method="POST">
            <section class="accordion accordion-flush" id="accordionFlushExample">
                <section class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                        Database
                    </button>
                    </h2>
                    <section id="flush-collapseOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                        <!-- Database Portion -->  
                        <section class="accordion-body">
                            <section class="row">
                                <h4 class="col-12">Database Credentials</h4>
                                <section class="form-floating col-3">
                                    <input class="form-control" id="DatabaseHost" placeholder="i" required name="DatabaseHost"/>
                                    <label for="DatabaseHost">Database Host</label>
                                </section>
                                <section class="form-floating col-3">
                                    <input class="form-control" id="DatabaseUser" placeholder="i" required name="DatabaseUser"/>
                                    <label for="DatabaseUser">Database User</label>
                                </section>
                                <section class="form-floating col-3">
                                    <input class="form-control" id="DatabasePwd" placeholder="i" required name="DatabasePassword"/>
                                    <label for="DatabasePwd">Database Password</label>
                                </section>
                                <section class="form-floating col-3">
                                    <input class="form-control" id="DatabaseName" placeholder="i" required name="DatabaseName"/>
                                    <label for="DatabaseName">Database Name</label>
                                </section>
                            </section>
                        </section>
                    </section>
                </section>
                <section class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                        Pages
                    </button>
                    </h2>
                    <section id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                        <!-- Pages Portion -->  
                        <section class="accordion-body">
                            <section class="row">
                                <h4 class="col-12">Pages</h4>
                                <h5 class="col-6">How many pages do you need?</h5>
                                <select class="form-control col-6" id="selectPages">
                                    <option>Select one</option>
                                    <option value="1">1 Page</option>
                                    <option value="2">2 Pages</option>
                                    <option value="3">3 Pages</option>
                                    <option value="4">4 Pages</option>
                                    <option value="5">5 Pages</option>
                                </select>   
                            </section>
                            <article id="formsForPages">
                                
                            </article>
                        
                        </section>
                    </section>
                </section>                
                <section class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                        Login
                    </button>
                    </h2>
                    <section id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                        <!-- Login Portion -->  
                        <section class="accordion-body">
                            <section class="row">
                                <h4 class="col-12">Login Credentials</h4>
                                <h5 class="col-6">What is preferred way for admins to login?</h5>
                                <select class="col-6" id="loginCredintials">
                                    <option>Select one</option>
                                    <option value="static">Static</option>
                                   
                                </select>
                                <section class="row pt-3" id="setCred">

                                </section>
                            </section>
                        </section>
                    </section>
                </section>
            </section>
            <button class="btn btn-success mt-3" name="generate">Generate Project</button>
            <span class="btn btn-warning mt-3 text-white" onclick='window.location = `https://donate.stripe.com/8wM3d84pX52n5R6eUU`'>Donate to us</span>
        </form>
    </main>
    <script data-type="pagesScript">
        let selectPages = document.getElementById("selectPages");
        selectPages.addEventListener("change", (e)=>{
            let selected = selectPages.value;
            document.getElementById("formsForPages").innerHTML = "";
            try{
                let i = 0;
                while(i < selected){
                    const xhttp = new XMLHttpRequest();
                    document.getElementById("formsForPages").innerHTML += `<h4 class='mt-3'>Page #${i + 1}</h4>`;
                    xhttp.onload = function(){                       
                        document.getElementById("formsForPages").innerHTML += this.responseText;
                    }
                    xhttp.open("GET", "PageFormLayout.html", false);
                    xhttp.send();
                    i++;
                }
                let c = 0;
                document.querySelectorAll('[data-type="tableInformation"]').forEach((elem)=>{
                    elem.setAttribute('data-information', `PT${c}`)
                    c++;
                })
                activateIncludeBtns();
            }catch(e){
                
            }finally{
                setTimeout(activateInputs(), 500);
            }
        })
        const activateIncludeBtns = ()=>{
            let c = 0;
        document.querySelectorAll('[data-group="including"]').forEach((elem)=>{
                let length = elem.children.length;
                for(i = 0; i < length; i++){
                    let sections = elem.children[i];
                    let checkbox = sections.children[0];
                    let label = sections.children[1];
                    checkbox.id = `includebtn${c}`;
                    label.setAttribute('for', `includebtn${c}`);
                    c++;                
                }  
        })
    }
        const ColumnsSelected = (e)=>{
            let parent = e.parentNode.parentNode.children;
            let targetElement = parent[parent.length - 1]
            targetElement.innerHTML = "";
            let i = 0;
            let parentTable = e.parentNode.parentNode.getAttribute("data-information");
            while(i < e.value){
                targetElement.innerHTML += `<input name='${parentTable}COL[]' placeholder='Column #${i + 1}' class='col-2 rounded pt-2 pb-2 m-2' requiredd/>`;
                i++;
            }
        }
        const activateInputs = ()=>{
        let inputs = document.querySelectorAll(`input[data-type="TableColumns"]`); 
        inputs.forEach((elem)=>{
            ColumnsSelected(elem)
            console.log(elem)
        })
    }
    </script>
    <script data-type="loginScript">
        let loginCredintials = document.getElementById("loginCredintials");
        loginCredintials.addEventListener("change", (e)=>{
            cred = document.getElementById("setCred");
            switch(loginCredintials.value){
                case "static":
                    cred.innerHTML = 
                    `<section class='form-floating col-6'>
                    <input class="form-control" name="staticUsername" placeholder="i" required/>
                    <label>Static Name</label>
                    </section>
                    <section class='form-floating col-6'>
                    <input class="form-control" name="staticPassword" placeholder="i" required/>
                    <label>Static Password</label>
                    </section>`;
                    break;
                    case "dynamic":
                        cred.innerHTML = 
                    `<section class='form-floating col-4'>
                    <input class="form-control" name="dynamicTableName" placeholder="i" required/>
                    <label>Dynamic Table Name</label>
                    </section>
                    <section class='form-floating col-4'>
                    <input class="form-control" name="dynamicUserName" placeholder="i" required/>
                    <label>Username Column Name</label>
                    </section>
                    <section class='form-floating col-4'>
                    <input class="form-control" name="dynamicPassword" placeholder="i" required/>
                    <label>Password Column Name</label>
                    </section>
                    <section class='form-floating col-4'>
                    <input class="form-control" name="dynamicColumnName" placeholder="i" required/>
                    <label>Dynamic Column Name</label>
                    </section>
                    <section class='form-floating col-2'>
                    <select class='form-control' name="dynamicConditionEval">
                        <option value='equal'>Equals</option>
                        <option value='greater'>Greater than</option>
                        <option value='lesser'>Less than</option>
                    </select>
                    </section>
                    <section class='form-floating col-2'>
                    <input class="form-control" name="dynamicCondition" placeholder="i" required/>
                    <label>Dynamic Condition</label>
                    </section>`;   
                        break;
                        default:
                        cred.innerHTML = "";
            }
        })
    </script>
</body>
</html>