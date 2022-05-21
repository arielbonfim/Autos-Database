<?php

session_start();

require_once 'pdo.php';
$make=$year=$mileage='';
$err='';
if(empty($_SESSION['name'])){
  //the code below is better but i made that above to  complete the exercice
  //header('location: index.php');
  //exit;
   die("Name parameter missing");
}
if (isset($_POST['make'])&& isset ($_POST['year'])&& isset($_POST['mileage'])){
  $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
  $make=htmlentities(trim($_POST['make']));
  $year=htmlentities(trim($_POST['year']));
  $mileage=htmlentities(trim($_POST['mileage']));

    if(!is_numeric($mileage) OR !is_numeric($year)){
      $err="Mileage and year must be numeric";
    }
    if(empty($make)){
      $err='Make is required';
    }
    if($err===''){
      try {
        $stmt = $pdo->prepare("INSERT INTO autos (make, year, mileage) VALUES (:make, :year, :mileage)");
        $stmt->execute(array(
          ':make' => $make,
          ':year' => $year,
          ':mileage' => $mileage
        ));
        $err=("<mark class=#008000>".'Record inserted'."</mark>");
      }
      catch(PDOException $e){
        $err = $e->getMessage();
      }
    }
  }
//this delete system do not work
  elseif (isset($_POST['delete']) && isset($_POST['idauto'])){

    $sql = "DELETE FROM autos WHERE idauto =:id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':id' => $_POST['idauto']));
    $err = ($_POST['idauto']) . 'deleted!';


  }

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Ariel leon Socio Bonfim</title>
  </head>
  <body>
    <p> Dashboard <?php echo $_SESSION['email']; ?> </p>
    <p> welcome, <?php echo $_SESSION['name']; ?> </p>
    <table border="1">
      <tr>
        <th>Make</th>
        <th>Year</th>
        <th>Mileage</th>
        <th>Delete</th>
      </tr>
      <?php
      $stmt = $pdo->query("SELECT make, year, mileage, idauto FROM autos");
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo ("<tr><td>");
        echo($row['make']);
        echo("</td><td>");
        echo($row['year']);
        echo("</td><td>");
        echo($row['mileage']);
        echo("</td><td>");
        echo('<form method="post"><input type="hidden" name="idauto" value"'.$row['idauto'].'">'."\n");
        echo('<input type ="submit" value="Delete" name="delete">');
        echo("\n</form>\n");
        echo("</td></tr>\n");
      }
       ?>
    </table>
    <p>add a new auto</p>
    <form method="post">
      <p>make:<input type="text" name="make" size="40"></p>
      <p>year:<input type="number" name="year" size="40" min="1900" max="<?php echo date("Y"); ?>"></p>
      <p>mileage:<input type="number" name="mileage" size="40"></p>
      <p><input type="submit" value="Add New" /></p>
    </form>
    <p><?php echo $err ?></p>
    <p><a href="logout.php" class="btn btn-danger">Logout</a></p>
  </body>
</html>
