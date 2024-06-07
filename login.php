<?php
$title = "Login";
include('./includes/header.php');
include('./config/dbConfig.php');
    //session starting
session_start();
if(isset($_SESSION['username'])){
    header("location:index.php");
}
?>

<?php
$error_message = "";
$login_status = false;

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $res = $result->fetch_assoc();
        $dbPassword = $res['password'];
        
        if($dbPassword == $password){
            $error_message = 'Login Success';
            $login_status = true;
            $_SESSION['username'] = $username;
            $_SESSION['name'] = $res['name'];
            header('location: index.php');
            exit();
        } else {
            $error_message = 'Invalid Credentials';
        }
    } else {
        $error_message = "User Not Found";
    }
}
?>

<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-5 col-lg-9 col-md-9 ">
            <div class="card o-hidden border-0 shadow-lg mt-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row d-flex align-items-center justify-content-center">
                        <div class="col-lg-12">
                            <div class="px-5 py-4">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                </div>
                                <form class="user" action="login.php" method="post">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user"
                                        id="exampleInputEmail" aria-describedby="emailHelp"
                                        placeholder="Enter Username"
                                        name="username" 
                                        >
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user"
                                        id="exampleInputPassword" placeholder="Password" name="password">
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" class="custom-control-input" id="customCheck">
                                            <label class="custom-control-label" for="customCheck">Remember
                                            Me</label>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary btn-user btn-block" name="login">
                                        Login
                                    </button>
                                </form>
                                    <!-- <div class="text-center">
                                        <a class="small" href="forgot-password.html">Forgot Password?</a>
                                    </div>-->  
                                    <div class="text-center mt-3 mb-0">
                                        <p class="text-danger fw-bold"><?php echo($error_message) ?></p>
                                    </div>                            
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
    <?php
    include('./includes/footer.php');
?>