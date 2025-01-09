<?php session_start(); 
if(!isset($_SESSION['id'])){
  header('Location: login_page.php');
}
?>

<?php include'connection.php';
if(isset($_GET['all_records'])){
    header("Location: index.php");
   }

if(isset(($_POST['add_users']))){
    header("Location: add_user.php");
}

if(isset($_POST['log_out'])){
    session_unset();
    session_destroy();
    header("Location: login_page.php");
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>display</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Heebo:wght@100..900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Manrope:wght@200..800&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
/* table, th, td {
  border-collapse: collapse;
  text-align: left;
  color: #757f89;
} */
/* table{
    margin-top: 30px;
    /* border-radius: 8px; */
    /* box-shadow: 0 6px 12px 0 rgba(214, 238, 238, 0.8); */
/* } */ 
/* 
th, td{
    height: 48px;
    padding: 8px;
    width: 10%;
    border: 1px solid lightgrey;
    text-align: left;
    padding: 8px;

}
th{
    color: #373c40;
    text-align: center;
    font-size: 18px;
    font-weight: 600;
    /* background-color:rgb(124, 148, 148); */
/* } */
/* tr:hover {background-color: #D6EEEE;}

tr:nth-child(odd) {
  background-color: #dddddd; */
/* } **/
/* th:nth-child(even),td:nth-child(even) {
  background-color: #dddddd;
} */

/* .add_user{
    height: 40px;
    width: 90px;
    background-color: #757f89;
    border: none;
    border-radius: 4px;
     
}
.add_user:hover{
    background-color: #0056b3; 
    transform: scale(1.05);
}

.add_user a{
    text-decoration: none;
    color: white;
    font-size: 13px;
    font-weight: bold;
}

body{
    padding: 20px 40px;
    font-family: "Heebo", serif;
    font-size: 17px;
}
.op_btn{
    height: 37px;
    width: 80px;
    background-color: #757f89;
    border: none;
    border-radius: 4px;
    
}
.op_btn a{
    color: white;
    text-decoration: none;
    font-family: "Heebo", serif;
    font-weight: bold;
}
.operations{
    display: flex !important;
    justify-content: space-evenly !important;
    border-radius: none;
    border: none;
    width: 100%;
    flex-wrap: wrap;
    /* height: 100%; */
    /* gap: 10px;
}
.update:hover {
    background-color: #45a049; 
    transform: scale(1.05); 
}
.del:hover{
    background-color: #d32f2f; 
    transform: scale(1.05); 
}
.paging_btn{
    height: 37px;
    width: 60px;
    background-color: #757f89;
    border: none;
    border-radius: 4px;
    margin-right: 10px;
    margin-top: 15px;
}

.active{
    background-color:rgb(81, 82, 83);
}

.paging_btn:hover {
    background-color: #0056b3; 
    transform: scale(1.05); 
    cursor: pointer; 
}
.paging_btn{
    color: white;
    text-decoration: none;
    font-family: "Heebo", serif;
    font-weight: bold;
    display: flex;
    justify-content: center;
    align-items: center;
}

.page_btn_row{
    display: flex;
    justify-content: center;
}

.search_container{
    display: flex;

}
.search_container .add_user{
    color: white;
    font-size: 13px;
    font-weight: bold;
}
.search_container div{
    justify-content: center;
    display: flex;
    flex-grow: 1;
    gap: 10px;
}
.search_input{
    width: 36%;
    font-size: 32px;
    font-size: 17px;
    color: #757f89;
}
.sr_no{
    width: 5%;
    text-align: center;
}
.asc-icon:before {
    content: '\25B2';
}
.desc-icon:before {
  content: '\25BC';
}  */

/* .table-wrapper {
    height:calc(100vh - 132px) ;  */
    /* overflow-y: auto; */
    /* margin-top: 20px; */
    /* min-height: 100px; */
/* } */
/* .table { */
    /* margin-bottom: 0; */
    /* width: 100%; */
/* }
.table thead {
    position: sticky;
    top: 0;
    z-index: 1;
    /* background-color: #343a40;  */
/* }
.table-wrapper::-webkit-scrollbar {
    display: none;
} */
        
/* .table-wrapper {
    scrollbar-width: 0; 
    -ms-overflow-style: none; 
} */
/* .form{
    position: sticky;
    top: 0;
    z-index: 1;
}  */
</style>
</head>
<body>
  <div class="wrapper" style="height:100vh;">
    <header class="">
    <nav class="navbar navbar-expand-lg navbar-light bg-dark" style="padding:10px 40px;">
  <a class="navbar-brand text-light" href="#">ARCS Infotech</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active text-light">
        <a class="nav-link text-light" href="#">Features</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-light" href="#">Pricing</a>
      </li>
      <li class="nav-item dropdown ">
        <a class="nav-link  text-light" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Services
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item text-light" href="#">Action</a>
          <a class="dropdown-item text-light" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item text-light" href="#">Something else here</a>
        </div>
      </li>
      <li class="nav-item text-light">
        <a class="nav-link disabled text-light" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
      </li>
    </ul>
  </div>
  <form action="" method="post" class="d-flex">
  <button class="btn btn-secondary d-flex btn-small" name="add_users"  style="margin-right:10px;">Add Users</button>
  <button class="btn btn-primary" name="log_out">Log out</button>
  </form>
</nav>
    </header>    


<main style="height:calc(100% - 132px);" class="d-flex justify-content-center pt-5">
  <div class="container" style="height:100%;">

  <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="get" class="mb-3 justify-content-between d-flex form">
    <div class="d-flex justify-content-between">
  
  <button type="button" class="btn btn-light d-flex btn-small" name="all_records" id="allRecordsBtn" >All Records</button>
  </div>
    <div class="form-group d-flex gap-2 justify-content-end">
        <input type="text" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" class="form-control" name="search" style="width:auto;" placeholder="Search records here...">
        <button type="submit" class="btn btn-primary text-white" name="submit">Search</button>
    </div>
    
    
</form>
  
   
     <table class="table">
       <thead class="table-dark ">
        <tr>
         <th scope="col" class="text-decoration-none text-light" style="width:64px;">Sr No.</th>                   
         <th scope="col"><a href="<?= getSortLink('name') ?>" class="text-decoration-none text-light">Client Name <i class="fa fa-sort" aria-hidden="true"></i>
         <span class="<?= $sortColumn == 'name' ? ($sortOrder == 'ASC' ? 'asc-icon' : 'desc-icon') : '' ?> sort-icon"></span></a></th>
         <th scope="col" ><a href="<?= getSortLink('company') ?>" class="text-decoration-none text-light">Company <i class="fa fa-sort" aria-hidden="true"></i>
         <span class="<?= $sortColumn == 'company' ? ($sortOrder == 'ASC' ? 'asc-icon' : 'desc-icon') : '' ?> sort-icon"></span></a></th>
         <th scope="col"><a href="<?= getSortLink('title') ?>" class="text-decoration-none text-light">Title <i class="fa fa-sort" aria-hidden="true"></i>
          <span class="<?= $sortColumn == 'title' ? ($sortOrder == 'ASC' ? 'asc-icon' : 'desc-icon') : '' ?> sort-icon"></span></a></th> 
         <th scope="col"><a href="<?= getSortLink('gender') ?>" class="text-decoration-none text-light">Gender <i class="fa fa-sort" aria-hidden="true"></i>
          <span class="<?= $sortColumn == 'gender' ? ($sortOrder == 'ASC' ? 'asc-icon' : 'desc-icon') : '' ?> sort-icon"></span></a></th> 
         <th scope="col"><a href="<?= getSortLink('email') ?>" class="text-decoration-none text-light">Email <i class="fa fa-sort" aria-hidden="true"></i>
          <span class="<?= $sortColumn == 'email' ? ($sortOrder == 'ASC' ? 'asc-icon' : 'desc-icon') : '' ?> sort-icon"></span></a></th> 
         <th scope="col"><a href="<?= getSortLink('phone') ?>" class="text-decoration-none text-light">Phone <i class="fa fa-sort" aria-hidden="true"></i>
          <span class="<?= $sortColumn == 'phone' ? ($sortOrder == 'ASC' ? 'asc-icon' : 'desc-icon') : '' ?> sort-icon"></span></a></th>  
         <th scope="col"><a href="<?= getSortLink('working_hours') ?>" class="text-decoration-none text-light">Working Hours <i class="fa fa-sort" aria-hidden="true"></i>
          <span class="<?= $sortColumn == 'working_hours' ? ($sortOrder == 'ASC' ? 'asc-icon' : 'desc-icon') : '' ?> sort-icon"></span></a></th>  
         <th scope="col" class="" style="width:16%;"><a href="<?= getSortLink('additional_info') ?>" class="text-decoration-none text-light">Additional Info <i class="fa fa-sort" aria-hidden="true"></i>
          <span class="<?= $sortColumn == 'additional_info' ? ($sortOrder == 'ASC' ? 'asc-icon' : 'desc-icon') : '' ?> sort-icon"></span></a></th>
         <th scope="col">Actions</th> 
         </tr>
       </thead>

       <?php 
       $sql = "select * from employee_info where is_deleted is null;";
       $result = mysqli_query($conn,$sql);
       $row_num = mysqli_num_rows($result);
       $limit = 4;
       $total_pages = ceil($row_num/$limit);
       $sortColumn = isset($_GET['sortColumn']) ? $_GET['sortColumn'] : 'name';
       $sortOrder = isset($_GET['sortOrder']) ? $_GET['sortOrder'] : 'ASC';

    //    if(isset($_GET['all_records'])){
    //     header("Location: display.php");
    //    }

       if(isset($_GET['page'])){
        $page = $_GET['page'];
       }else{
        $page = 1;
       }

       $starting_limit = ($page-1)*$limit;
       
       $search = isset($_GET['search']) ? "%" . mysqli_real_escape_string($conn, $_GET['search']) . "%" : null;

        if ($search) {
            $sql = "SELECT * FROM employee_info WHERE
                 is_deleted is null and  
                    (name LIKE ? OR 
                    company LIKE ? OR 
                    title LIKE ? OR 
                    gender LIKE ? OR 
                    email LIKE ? OR 
                    phone LIKE ? OR 
                    working_hours LIKE ? OR 
                    additional_info LIKE ?) 
                    ORDER BY $sortColumn $sortOrder LIMIT ?, ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssssii", $search, $search, $search, $search, $search, $search, $search, $search, $starting_limit, $limit);
        } else {
            $sql = "SELECT * FROM employee_info where is_deleted is null ORDER BY $sortColumn $sortOrder LIMIT ?, ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $starting_limit, $limit);
        }


        function getSortLink($column) {
            $sortColumn = isset($_GET['sortColumn']) ? $_GET['sortColumn'] : 'name';
            $sortOrder = isset($_GET['sortOrder']) && $_GET['sortOrder'] == 'ASC' ? 'DESC' : 'ASC';
            $queryParams = array_merge($_GET, ['sortColumn' => $column, 'sortOrder' => $sortOrder]);
            return '?' . http_build_query($queryParams);
        }

        $stmt->execute();
        $result = $stmt->get_result();
       
       if($result){ 
        $sr_no = 1;
        while($row = mysqli_fetch_assoc($result)){
            
            $id = $row['id'];
            $client_name = $row['name'];
            $company = $row['company'];
            $title = $row['title'];
            $email = $row['email'];
            $phone = $row['phone'];
            $work_hours = $row['working_hours'];
            $info = $row['additional_info'];
            $gender = $row['gender'] ?? 'not specified';
            $sNo= $sr_no + $starting_limit;

            echo "<tbody>
                  <tr >
                  <th scope='row'  class='p-2  align-middle'>$sNo</th>
                  <td class='p-2  align-middle'>$client_name</td>
                  <td  class='p-2  align-middle'>$company</td>
                  <td  class='p-2  align-middle'>$title</td>
                  <td  class='p-2  align-middle'>$gender</td>
                  <td  class='p-2  align-middle'>$email</td>
                  <td  class='p-2  align-middle'>$phone</td>
                  <td  class='p-2  align-middle'>$work_hours</td>
                  <td  class='p-2  align-middle'>$info</td>
                  <td  class='p-2  align-middle' >
                  <div class='operations d-flex gap-2 flex-wrap align-middle justify-content-end'>
                    <button class='btn btn-success'><a href='update.php?id=$id' class='text-decoration-none text-light'>Update</a></button>
                    <button class='btn btn-danger'><a href='delete.php?id=$id' class='text-decoration-none text-light'>Delete</a></button>
                    </div>
                  </td>
                  </tr>
                  </tbody>";

                  $sr_no++;

                 
        }
       }
       
       
       
       ?>
       </table>
    

       <?php
            echo "<div class='d-flex gap-2 justify-content-center'>";

            for ($i = 1; $i <= $total_pages; $i++) {
                $active = ($i == $page) ? "active" : "";
                $search_param = isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '';
                echo "<a href='index.php?page=$i$search_param' class='paging_btn $active text-light btn btn-primary'>$i</a>";
            }
            
            echo "</div>";
       ?>    
  </div>

  </main>

    <footer class="bg-dark text-white text-center py-3 ">
        <p>&copy; 2025 ARCS Infotech. All rights reserved.</p>
    </footer>

  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

  <script>
    document.getElementById('allRecordsBtn').addEventListener('click', function () {
      window.location.href = 'index.php';
    });
  </script>
</body>
</html>