<?php session_start();
if (!isset($_SESSION['id'])) {
  header('Location: login_page.php');
}
?>

<?php include 'connection.php';
if (isset($_GET['all_records'])) {
  header("Location: index.php");
}

if (isset(($_GET['add_users']))) {
  header("Location: add_user.php");
}

if (isset($_POST['log_out'])) {
  session_unset();
  session_destroy();
  header("Location: login_page.php");
}

function getQueryParams($exclude = []) {
  $params = $_GET;
  foreach($exclude as $param) {
      unset($params[$param]);
  }
  return http_build_query($params);
}

$sql = "select * from employee_info where is_deleted is null;";
$result = mysqli_query($conn, $sql);
$row_num = mysqli_num_rows($result);
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$search = isset($_GET['search']) ? trim(mysqli_real_escape_string($conn, $_GET['search'])) : '';
$sortColumn = isset($_GET['sortColumn']) ? mysqli_real_escape_string($conn, $_GET['sortColumn']) : 'name';
$sortOrder = isset($_GET['sortOrder']) ? ($_GET['sortOrder'] === 'DESC' ? 'DESC' : 'ASC') : 'ASC';
$starting_limit = ($page - 1) * $limit;

// echo "limit " .$limit. "<br>";
// echo "offset " .$starting_limit. "<br>";
// echo "search " .$search. "<br>";
// echo "sorthing column " .$sortColumn. "<br>";
// echo "sorthing order " .$sortOrder. "<br>";

$allowedColumns = ['name', 'company', 'title', 'gender', 'email', 'phone', 'working_hours', 'additional_info'];
if (!in_array($sortColumn, $allowedColumns)) {
    $sortColumn = 'name';
}
$whereClause = "is_deleted IS NULL";
if ($search) {
  $whereClause .= " AND (
      name LIKE '%$search%' OR 
      company LIKE '%$search%' OR 
      title LIKE '%$search%' OR 
      gender LIKE '%$search%' OR 
      email LIKE '%$search%' OR 
      phone LIKE '%$search%' OR 
      working_hours LIKE '%$search%' OR 
      additional_info LIKE '%$search%'
  )";
}

$sql = "SELECT * FROM employee_info 
        WHERE $whereClause 
        ORDER BY $sortColumn $sortOrder 
        LIMIT $starting_limit, $limit";

$countSql = "SELECT COUNT(*) AS total FROM employee_info WHERE $whereClause";
$countResult = $conn->query($countSql);
$totalResult = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalResult / $limit);


function getSortLink($column) {
  $currentSortColumn = $_GET['sortColumn'] ?? 'name';
  $currentSortOrder = $_GET['sortOrder'] ?? 'ASC';
  
  $newSortOrder = ($currentSortColumn === $column && $currentSortOrder === 'ASC') ? 'DESC' : 'ASC';
  
  $params = $_GET;
  $params['sortColumn'] = $column;
  $params['sortOrder'] = $newSortOrder;
  
  return '?' . http_build_query($params);
}
$result = $conn->query($sql);
$countResult = $conn->query($countSql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>display</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="home.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Heebo:wght@100..900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Manrope:wght@200..800&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
  <div class="wrapper">
    <header class="">
      <nav class="navbar navbar-expand-lg navbar-light bg-dark" style="">
        <a class="navbar-brand text-light" href="index.php">ARCS Infotech</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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
              <a class="nav-link  text-light" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
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
          <div class="text-light d-flex justify-content-center align-items-center session_name">Hii <?= $_SESSION['name'];?> !!</div>
          <button class="btn btn-primary" name="log_out">Log out</button>
        </form>
      </nav>
    </header>


    <main style="" class="d-flex justify-content-center display_main flex-column">
      <div class="table_wrapper" style="">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="get" class="mb-3 justify-content-between d-flex form">
          <div class="form-group d-flex gap-2 justify-content-end">
              <input type="text" value="<?php echo htmlspecialchars($search); ?>" class="form-control search_input" name="search" placeholder="Search records...">
              <input type="hidden" name="sortColumn" value="<?php echo htmlspecialchars($sortColumn); ?>">
              <input type="hidden" name="sortOrder" value="<?php echo htmlspecialchars($sortOrder); ?>">
              <input type="hidden" name="limit" value="<?php echo $limit; ?>">
              <button type="submit" class="btn btn-primary text-white">Search</button>
          </div>
          <button class="btn btn-secondary d-flex btn-small justify-content-center align-items-center gap-2"
            name="add_users" style="">Add
            Users</i></button>
        </form>

        <div class="table_container">
          <table class="table">
            <thead class="table-dark ">
              <tr>
                <th scope="col" class="text-decoration-none text-light sr_no" style="">Sr No.</th>
                <th scope="col">
                    <a href="<?= getSortLink('name') ?>" class="text-decoration-none text-light sortable">
                        Client Name
                        <?php if($sortColumn == 'name'): ?>
                            <i class="fa-solid <?= $sortOrder == 'ASC' ? 'fa-caret-down' : 'fa-caret-up' ?>"></i>
                        <?php else: ?>
                          <i class="fa-solid fa-sort"></i>
                        <?php endif; ?>
                    </a>
                </th>
                <th scope="col">
                    <a href="<?= getSortLink('company') ?>" class="text-decoration-none text-light sortable">
                        Company
                        <?php if($sortColumn == 'company'): ?>
                            <i class="fa-solid <?= $sortOrder == 'ASC' ? 'fa-caret-down' : 'fa-caret-up' ?>"></i>
                        <?php else: ?>
                            <i class="fa-solid fa-sort"></i>
                        <?php endif; ?>
                    </a>
                </th>
                <th scope="col">
                    <a href="<?= getSortLink('title') ?>" class="text-decoration-none text-light sortable">
                        Title
                        <?php if($sortColumn == 'title'): ?>
                            <i class="fa-solid <?= $sortOrder == 'ASC' ? 'fa-caret-down' : 'fa-caret-up' ?>"></i>
                        <?php else: ?>
                            <i class="fa-solid fa-sort"></i>
                        <?php endif; ?>
                    </a>
                </th>
                <th scope="col">
                    <a href="<?= getSortLink('gender') ?>" class="text-decoration-none text-light sortable">
                        Gender
                        <?php if($sortColumn == 'gender'): ?>
                            <i class="fa-solid <?= $sortOrder == 'ASC' ? 'fa-caret-down' : 'fa-caret-up' ?>"></i>
                        <?php else: ?>
                            <i class="fa-solid fa-sort"></i>
                        <?php endif; ?>
                    </a>
                </th>
                <th scope="col">
                    <a href="<?= getSortLink('email') ?>" class="text-decoration-none text-light sortable">
                        Email
                        <?php if($sortColumn == 'email'): ?>
                            <i class="fa-solid <?= $sortOrder == 'ASC' ? 'fa-caret-down' : 'fa-caret-up' ?>"></i>
                        <?php else: ?>
                            <i class="fa-solid fa-sort"></i>
                        <?php endif; ?>
                    </a>
                </th>
                <th scope="col">
                    <a href="<?= getSortLink('phone') ?>" class="text-decoration-none text-light sortable">
                        Phone
                        <?php if($sortColumn == 'phone'): ?>
                            <i class="fa-solid <?= $sortOrder == 'ASC' ? 'fa-caret-down' : 'fa-caret-up' ?>"></i>
                        <?php else: ?>
                            <i class="fa-solid fa-sort"></i>
                        <?php endif; ?>
                    </a>
                </th>
                <th scope="col">
                    <a href="<?= getSortLink('working_hours') ?>" class="text-decoration-none text-light sortable">
                        Working hours
                        <?php if($sortColumn == 'working_hours'): ?>
                            <i class="fa-solid <?= $sortOrder == 'ASC' ? 'fa-caret-down' : 'fa-caret-up' ?>"></i>
                        <?php else: ?>
                            <i class="fa-solid fa-sort"></i>
                        <?php endif; ?>
                    </a>
                </th>
                <th scope="col">
                    <a href="<?= getSortLink('created_at') ?>" class="text-decoration-none text-light sortable">
                        Created at
                        <?php if($sortColumn === 'created_at'): ?>
                            <i class="fa-solid <?= $sortOrder == 'ASC' ? 'fa-caret-down' : 'fa-caret-up' ?>"></i>
                        <?php else: ?>
                            <i class="fa-solid fa-sort"></i>
                        <?php endif; ?>
                    </a>
                </th>
                <th scope="col">
                    <a href="<?= getSortLink('updated_at') ?>" class="text-decoration-none text-light sortable">
                        Updated at
                        <?php if($sortColumn === 'updated_at'): ?>
                            <i class="fa-solid <?= $sortOrder == 'ASC' ? 'fa-caret-down' : 'fa-caret-up' ?>"></i>
                        <?php else: ?>
                            <i class="fa-solid fa-sort"></i>
                        <?php endif; ?>
                    </a>
                </th>
                <th scope="col" class="additional_info">
                    <a href="<?= getSortLink('additional_info') ?>" class="text-decoration-none text-light sortable">
                        Additional info
                        <?php if($sortColumn == 'additional_info'): ?>
                            <i class="fa-solid <?= $sortOrder == 'ASC' ? 'fa-caret-down' : 'fa-caret-up' ?>"></i>
                        <?php else: ?>
                            <i class="fa-solid fa-sort"></i>
                        <?php endif; ?>
                    </a>
                </th>
                <th scope="col" class="text-center">Actions</th>
              </tr>
            </thead>
            <?php
            echo "<tbody>";
            if ($result) {
              $sr_no = 1;
              while ($row = mysqli_fetch_assoc($result)) {

                $id = $row['id'];
                $client_name = $row['name'];
                $company = $row['company'];
                $title = $row['title'];
                $email = $row['email'];
                $phone = $row['phone'];
                $work_hours = $row['working_hours'];
                $info = $row['additional_info'];
                $created_at = $row['created_at'];
                $updated_at = $row['updated_at'];
                $gender = $row['gender'] ?? 'not specified';
                $sNo = $sr_no + $starting_limit;


                echo "
                  <tr >
                  <th scope='row'  class='p-2  align-middle'>$sNo</th>
                  <td class='p-2  align-middle'>$client_name</td>
                  <td  class='p-2  align-middle'>$company</td>
                  <td  class='p-2  align-middle'>$title</td>
                  <td  class='p-2  align-middle'>$gender</td>
                  <td  class='p-2  align-middle'>$email</td>
                  <td  class='p-2  align-middle'>$phone</td>
                  <td  class='p-2  align-middle justify-content-end'>$work_hours</td>
                  <td  class='p-2  align-middle'>$created_at</td>
                  <td  class='p-2  align-middle'>$updated_at</td>
                  <td  class='p-2  align-middle'>$info</td>
                  <td  class='p-2  align-middle' >
                  <div class='operations d-flex gap-2 flex-wrap align-middle justify-content-center'>
                    <button class='btn btn-success action_btn'><a href='update.php?id=$id' class='text-decoration-none text-light'><i class='fa-solid fa-pen'></i></a></button>
                    <button class='btn btn-danger action_btn'><a href='delete.php?id=$id' class='text-decoration-none text-light' onclick='return confirmDelete()'><i class='fa-solid fa-trash'></i></a></button>
                    </div>
                  </td>
                  </tr>";




                $sr_no++;


              }
            } else {
              echo "<tr>No records found</tr>";
            }
            echo "</tbody>";



            ?>
          </table>
        </div>



      </div>
      <div class="d-flex gap-2 align-items-center pagination_sec">
        <div>Showing 1-<?= $limit?> of <?= $totalResult?> records</div>
      <div class="dropdown d-flex gap-1">Records per page
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET">
            <?php foreach (['search', 'sortColumn', 'sortOrder'] as $param): ?>
                <?php if (isset($_GET[$param])): ?>
                    <input type="hidden" name="<?php echo $param; ?>" 
                          value="<?php echo htmlspecialchars($_GET[$param]); ?>">
                <?php endif; ?>
            <?php endforeach; ?>
            <select name="limit" onchange="this.form.submit()">
                <?php foreach ([10, 20, 50, 100] as $val): ?>
                    <option value="<?php echo $val; ?>" <?php echo $limit == $val ? 'selected' : ''; ?>>
                        <?php echo $val; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
      </div>
     
      <div class="pagination_div d-flex gap-1">  
    <a href="<?php echo $page > 1 ? '?' . getQueryParams(['page']) . '&page=1' : 'javascript:void(0)'; ?>" 
       class="btn-secondary <?php echo $page <= 1 ? 'disabled' : ''; ?>"><<</a>
    
    <a href="<?php echo $page > 1 ? '?' . getQueryParams(['page']) . '&page=' . ($page - 1) : 'javascript:void(0)'; ?>" 
       class="btn-secondary pag_btn <?php echo $page <= 1 ? 'disabled' : ''; ?>"><</a>
    
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="<?php echo $i !== $page ? '?' . getQueryParams(['page']) . '&page=' . $i : 'javascript:void(0)'; ?>" 
           class="btn-<?php echo $i === $page ? 'primary active disabled' : 'secondary'; ?> btn-sm">
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>

    <a href="<?php echo $page < $totalPages ? '?' . getQueryParams(['page']) . '&page=' . ($page + 1) : 'javascript:void(0)'; ?>" 
       class="btn-secondary pag_btn <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">></a>
    
    <a href="<?php echo $page < $totalPages ? '?' . getQueryParams(['page']) . '&page=' . $totalPages : 'javascript:void(0)'; ?>" 
       class="btn-secondary <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">>></a>
</div>

        
    </div>

     
    </main>

    <footer class="bg-dark text-white text-center py-3 ">
      <p>&copy; 2025 ARCS Infotech. All rights reserved.</p>
    </footer>

  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"
    integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
    crossorigin="anonymous"></script>

  <script>
    
    document.getElementById('allRecordsBtn').addEventListener('click', function () {
      window.location.href = 'index.php';
    });

    function confirmDelete() {
      return confirm("Are you sure you want to delete this entry?");
    }

    // $(document).ready(function(){
    //   $("#limit_record").change(function(){
    //     alert(this.value);
    //   })
    // })
  </script>
</body>

</html>
















