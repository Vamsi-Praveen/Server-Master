<?php
session_start();
$title = "Blank";
include('./includes/header.php');
include('./config/dbConfig.php');
?>

<?php

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['submit'])){
            $oldpasswd = $_POST['oldpasswd'];
            $newpasswd = $_POST['newpasswd'];
            $username = $_SESSION['username'];
            $query = "select password from users where username = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s',$username);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows>0){
                $res = $result->fetch_assoc();
                $passwd = $res['password'];
                if($passwd != $oldpasswd){
                    echo "<script>alert('Old Password didn\'t Match')</script>";
                }
              else {
                $query_new = "UPDATE users SET password = ? WHERE username = ?";
                $stmt_new = $conn->prepare($query_new);
                $stmt_new->bind_param('ss', $newpasswd, $username);
                $stmt_new->execute();

                if ($stmt_new->affected_rows > 0) {
                    echo "<script>alert('Password Changed Successfully');</script>";
                } else {
                    echo "<script>alert('Password Change Failed');</script>";
                }
                $stmt_new->close();
            }
        }
            $stmt->close();
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
            <div class="container-fluid">

                <!-- Page Heading -->
                <h1 class="h3 mb-4 text-gray-800">Change Password</h1>

                <div class="row">

                    <div class="col-lg-8">

                        <!-- Student card -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Enter Details to Change Password</h6>
                            </div>
                            <div class="card-body">
                               <form action="changepassword.php" method="post" id="user-form">
                                   <div class="col-lg-10">
                                       <div class="mb-2">
                                          <label for="exampleFormControlInput1" class="form-label">Old Password</label>
                                          <input type="password" class="form-control" id="oldpass" name="oldpasswd">
                                      </div>
                                      <div class="mb-2">
                                          <label for="exampleFormControlInput1" class="form-label">New Password</label>
                                          <input type="password" class="form-control" id="newpasswd" name="newpasswd">
                                      </div>
                                      <div class="mb-2">
                                          <label for="exampleFormControlInput1" class="form-label">Confirm Password</label>
                                          <input type="password" class="form-control" id="confpassword" name="confpasswd">
                                      </div>
                                      
                                      <p class="text-danger d-none" id="passerr">Password's Doesn't match.</p>

                                  </div>
                                  <div class="mt-3">
                                    <button class="btn btn-primary" name="submit">Change Password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
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


    <script src="vendor/jquery/jquery.min.js"></script>

    <script>
     $(document).ready(function(){
        $('#user-form').submit(function(e){
            let formData = new FormData(this);
            if (
                formData.get("oldpasswd").trim() === "" ||
                formData.get("newpasswd").trim() === "" ||
                formData.get("confpasswd") === "" 
                ) {
                alert("Please fill in all required fields.");
            event.preventDefault(); // Prevent form submission
        }
        else if(
            formData.get('newpasswd').trim() !== formData.get('confpasswd')){
            $('#passerr').removeClass('d-none').show();
        e.preventDefault();
    }
    else
    {
        $('#passerr').hide();
    }
})
    })
</script>   





<?php include('./includes/footer.php')?>