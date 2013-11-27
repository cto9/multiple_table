<html>
<head>
    <meta charset="utf-8">
    <title>DateEdit</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<?php

include 'dbConnect.php';
include 'myFunctions.php';

$currentID = $_GET['id'];
echo $currentID;

$stmt = $db->query(sprintf('SELECT * FROM tasks WHERE id=%d',$currentID));
$taskEdit = $stmt->fetch(PDO::FETCH_ASSOC);

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $data = getDataFromRequest($_POST);
    $validationResult = validateData($data, $db);

    if( $validationResult['success'] == true ) {
        insertTaskLog($currentID, $data, $db);
        header("Location:index.php");
    } else {
        echo 'ERROR';
    }
}


?>


<table>

    <form action="" method="post">
        <tr>
            <td><label for="taskname">Название задания</label></td>
            <td><input id="taskname" type="text" name="taskname" value="<?php echo $taskEdit['taskname']?>"/></td>
        </tr>

        <tr>
            <td><label for="description">Описание</label></td>
            <td><textarea id="description" name="description" cols=60 rows=10><?php echo $taskEdit['description']?></textarea></td>
        </tr>

        <tr>
            <td><label for="tasktype">Тип задания</label></td>
            <td><select id="tasktype" name="tasktype">
                    <option value="development" <?php
                            if($taskEdit['tasktype'] == "development") echo "selected";
                        ?> >Development
                    <option value="planning" <?php
                            if($taskEdit['tasktype'] == "planning") echo "selected";
                        ?> >Planning
                    <option value="debugging" <?php
                            if($taskEdit['tasktype'] == "debugging") echo "selected";
                        ?>>Debugging
                </select></td>
        </tr>
        <tr>
            <td></td>
            <td align="right"><input type="submit" value="Save"/></td>
        </tr>
    </form>

</table>

</body>

</html>