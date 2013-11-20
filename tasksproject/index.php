<html>

<head>
    <meta charset="utf-8">
    <title>Tasks Project</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<form action="" method="post">
    <p><label for="taskname">Название задания</label> <input id="taskname" type="text" name="taskname" /></p>
    <p><label for="description">Описание</label> <textarea id="description" name="description" cols=60 rows=10></textarea></p>
    <p><label for="tasktype">Тип задания</label> <select id="tasktype" name="tasktype">
            <option value="development">Development
            <option value="planning">Planning
            <option value="debugging">Debugging
            </select></p>
    <p><input type="submit" /></p>
</form>

<?php

    $host = "localhost";
    $user = "root";
    $password = "root";
//    $db = "tasksproject";

    try {
        $db = new PDO('mysql:host=localhost;dbname=tasksproject', $user, $password);
//        $dbh = null;
    } catch (PDOException $e) {
        echo '<span class="error_message">Error!: ' . $e->getMessage() . "<br/>";
        die();
    }

    if(isset($_POST['taskname']))
        $taskname = $_POST['taskname'];
    if(isset($_POST['description']))
        $description = $_POST['description'];
    if(isset($_POST['tasktype']))
        $tasktype = $_POST['tasktype'];

//    $querry = "INSERT INTO tasks VALUES (NOW(), $taskname, $description, $tasktype)";

$st = $db->prepare('INSERT INTO tasks( creationdate, taskname, description, tasktype ) VALUES( NOW(), :taskname, :description, :tasktype )');
$st->execute(array( ':taskname' => $taskname, ':description' => $description, ':tasktype' => $tasktype ));


?>


</body>

</html>