<?php
session_start();
$title = "View Details";
include('./includes/header.php');
include_once('./utils/functions.php');
?>

<?php
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $type = $_GET['p'] ?? null;
}
?>

<?php

function fetchUserAccounts(){
    $file = '/etc/passwd';
    $users = [];

    if(file_exists($file)){
        $content = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($content as $line) {
            $fields = explode(':', $line);
            if(count($fields)>6){
                $username = $fields[0];
                $uid = (int)$fields[2];
                $gid = (int)$fields[3];
                $comment = $fields[4];
                $home = $fields[5];
                $shell = $fields[6];

                if($uid>1000 and $uid!=65534){
                    $users[]=[
                        'username' => $username,
                        'uid' => $uid,
                        'gid' => $gid,
                        'comment' => $comment,
                        'home' => $home,
                        'shell' => $shell
                    ];
                }
            }
        }

    }
    return $users;
}

$users = fetchUserAccounts();

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
                <?php

                if($type == 'users'){
                    ?>

                    <h1 class="h3 mb-2 text-gray-800">View All Users</h1>
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Users Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>User Name</th>
                                            <th>UID</th>
                                            <th>GID</th>
                                            <th>Comment</th>
                                            <th>Home Directory</th>
                                            <th>Shell</th>
                                            <th class="no-export">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       <?php
                                       foreach($users as $user):
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($user['username'])?></td>
                                            <td><?php echo htmlspecialchars($user['uid'])?></td>
                                            <td><?php echo htmlspecialchars($user['gid'])?></td>
                                            <td align="center"><?php echo htmlspecialchars($user['comment'])!='' ? htmlspecialchars($user['comment']) : '-'?></td>
                                            <td><?php echo htmlspecialchars($user['home'])?></td>
                                            <td><?php echo htmlspecialchars($user['shell'])?></td>
                                            <td>
                                                <div class="d-flex justify-content-around">
                                                 <div style="cursor: pointer;" onclick="handleDisable('<?php echo encrypt_data($user['uid'])?>'')">
                                                     <i class="fas fa-ban text-warning"></i>
                                                 </div>
                                                 <div style="cursor: pointer;" onclick="handleDisable('<?php echo encrypt_data($user['uid'])?>'')">
                                                     <i class="fas fa-trash text-danger"></i>
                                                 </div>
                                             </div>
                                         </td>
                                     </tr>
                                     <?php endforeach;?>
                             </tbody>
                         </table>
                     </div>
                 </div>
             </div>

             <?php
         }
         elseif($type == 'groups'){

            ?>



            <?php
        }
        else{
            echo '<h3>Invalid Path</h3>';
        }
        ?>

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
<script type="text/javascript">
   
   function handleDisable(uid){

   }
   function handleDelete(uid){

   }

</script>

<?php include('./includes/footer.php')?>
