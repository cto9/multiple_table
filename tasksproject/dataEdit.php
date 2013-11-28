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
$idValid = checkID($currentID, $db);

if($idValid['success'] == false){
    header("HTTP/1.1 404 Not Found");
    echo file_get_contents("404.html");
    foreach($idValid as $errorMes){
        echo '<span class = "error_message">', $errorMes, '</span>';
    }
    exit();
}

$stmt = $db->prepare(sprintf('SELECT * FROM tasks WHERE id=%d', $currentID));
$stmt->execute();
$taskEdit = $stmt->fetch(PDO::FETCH_ASSOC);

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

    $data = getDataFromRequest($_POST);
    $validationResult = validateData($data, $db);
    $messageOut = array();

    if( $validationResult['success'] == true ) {
        insertTaskLog($currentID, $data, $db);
        header("Location:index.php");
    } else {
        $messageOut = $validationResult['messages'];
        foreach ($messageOut as $messages) {
            echo '<span class = "error_message">', $messages['type'], ': ', $messages['text'], '</span>';
        }
    }
}

?>


<table>

    <form action="" method="post">
        <tr>
            <td><label for="taskname">Название задания</label></td>
            <td><input id="taskname" type="text" name="taskname" value="<?php echo $taskEdit['taskname'] ?>"/></td>
        </tr>

        <tr>
            <td><label for="description">Описание</label></td>
            <td><textarea id="description" name="description" cols=60
                          rows=10><?php echo $taskEdit['description']?></textarea></td>
        </tr>

        <tr>
            <td><label for="tasktype">Тип задания</label></td>
            <td><select id="tasktype" name="tasktype">

                    <?php
                    $taskTypeSelected = array('development', 'planning', 'debugging');
                    foreach ($taskTypeSelected as $typeSelect) {
                        echo '<option value="', $typeSelect, '"';
                        $tmpString = "";
                        if( $taskEdit['tasktype'] == $typeSelect )
                            $tmpString = $tmpString . "selected";
                        echo $tmpString . '>' . $typeSelect;

                    }

                    ?>

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