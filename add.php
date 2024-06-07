<?php
session_start();
$title = "Add Users";
include('./includes/header.php');
?>
<?php
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $type = $_GET['p'] ?? null;
}
?>
<?php
if($_SERVER['REQUEST_METHOD']=='POST' && $_GET['p'] == 'users'){
    if(isset($_POST['submit'])){
        $username =  escapeshellarg($_POST['username']);
        $password = escapeshellarg($_POST['password']);
        $role = escapeshellarg($_POST['role']);
        $shell = escapeshellarg($_POST['shell']);
        $forceLogin = isset($_POST['force-login']) ? 1 : 0;
    }
        //2>&1 -> gives stderror
    $command = "sudo useradd -s $shell $username 2>&1";
    $output = shell_exec($command);
    if($output == null){
            //user added successfully
        $passwordFile = tempnam(sys_get_temp_dir(),'passwd');
        file_put_contents($passwordFile, "$username:$password");

        $passwordCommand = "sudo chpasswd < $passwordFile 2>&1";

        $passwordOutput = shell_exec($passwordCommand);
        if($passwordOutput == null){
                //password succesfully set
            if($forceLogin){
                $forceLoginCommand = "sudo chage -d 0 $username 2>&1";
                $forceLoginOutput = shell_exec($forceLoginCommand);
                if($forceLoginOutput != null){
                    echo "Error ".$forceLoginOutput;
                }
            }
        }else
        {
            echo "Error ".$passwordOutput;
        }
        unlink($passwordFile);
    }else
    {
        echo "Error while creating ".$output;
    }

}



?>
<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <?php include('./includes/sidebar.php')?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <?php include('./includes/navbar.php')?>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <?php

            if($_GET['p'] == 'users'){
                ?>
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Add Users</h1>

                    <div class="row">

                        <div class="col-lg-8">

                            <!-- Student card -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Enter Details to Add New User</h6>
                                </div>
                                <div class="card-body">
                                 <form action="" method="post" id="user-form">
                                     <div class="col-lg-10">
                                         <div class="mb-2">
                                          <label for="exampleFormControlInput1" class="form-label">Username</label>
                                          <input type="text" class="form-control" id="exampleFormControlInput1" name="username">
                                      </div>
                                      <div class="mb-2">
                                          <label for="exampleFormControlInput1" class="form-label">Password</label>
                                          <input type="password" class="form-control" id="exampleFormControlInput1" name="password">
                                      </div>
                                      <div class="mb-3 d-flex flex-column">
                                        <label class="form-label">Role</label>
                                        <select name="role" class="form-select p-1">
                                            <option selected>Select Role</option>
                                            <option value="admin">Admin</option>
                                            <option value="user">User</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 d-flex flex-column">
                                        <label class="form-label">Shell</label>
                                        <select name="shell" class="form-select p-1">
                                            <option selected>Select shell</option>
                                            <option value="/bin/bash">Bash</option>
                                            <option value="/bin/sh">Sh</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <input type="checkbox" name="force-login" id="force-login">&nbsp;<label for="force-login">Force Login on Next Login</label>
                                    </div>
                                </div>
                                <div>
                                    <button class="btn btn-primary" name="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php
        }
        elseif($_GET['p'] == 'groups'){
            ?>
            <div class="container-fluid">

                <!-- Page Heading -->
                <h1 class="h3 mb-4 text-gray-800">Add Group</h1>

                <div class="row">

                    <div class="col-lg-8">

                        <!-- Student card -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Enter Details to Add New Group</h6>
                            </div>
                            <div class="card-body">
                             <form action="" method="post" id="group-form">
                                 <div class="col-lg-10">
                                     <div class="mb-2">
                                      <label for="exampleFormControlInput1" class="form-label">Group Name</label>
                                      <input type="text" class="form-control" id="exampleFormControlInput1" name="Group Name">
                                  </div>
                                  <div class="mb-3 d-flex flex-column">
                                    <label class="form-label">Role</label>
                                    <select name="role" class="form-select p-1">
                                        <option selected>Select Role</option>
                                        <option value="admin">Admin</option>
                                        <option value="user">User</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <button class="btn btn-primary" name="submit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }


    ?>
    <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById("user-form").addEventListener("submit", function(event) {
            var formData = new FormData(this);
            if (
                formData.get("username").trim() === "" ||
                formData.get("password").trim() === "" ||
                formData.get("role") === "Select Role" ||
                formData.get("shell") === "Select Shell"
                ) {
                alert("Please fill in all required fields.");
            event.preventDefault(); // Prevent form submission
        }
    });
    });
</script>


<!-- Logout Modal-->
<?php include('./includes/logoutModal.php')?>
<?php include('./includes/footer.php')?>