<html>

<head>
    <meta charset="utf-8">
    <title>Tasks Project</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<?php

function getDataFromRequest($datapost)
{
    $taskname = empty($datapost['taskname']) ? "" : trim($datapost['taskname']);
    $description = empty($datapost['taskname']) ? "" : trim($datapost['description']);
    $tasktype = $datapost['tasktype'];
    return array('taskname' => $taskname, 'description' => $description, 'tasktype' => $tasktype);
}

function validateData($data, $db)
{

    $validationRes = array('success' => true, 'messages' => array());
    $taskTypeVariants = array('development', 'planning', 'debugging');

    if( empty($data['taskname']) ) {
        addErrorMessage($validationRes, 'Название задачи не может быть пустым');
    }

    $nameValid = $db->prepare('SELECT COUNT(*) FROM tasks WHERE taskname = :taskname');
    $nameValid->execute(array('taskname' => $data['taskname']));
    $checkUniqName = $nameValid->fetch(PDO::FETCH_ASSOC);

    if( $checkUniqName['COUNT(*)'] == 1 ) {
        addErrorMessage($validationRes, sprintf('Задание с именем %s уже существует', $data['taskname']));
    }

    if( !in_array($data['tasktype'], $taskTypeVariants) ) {
        addErrorMessage($validationRes, 'Неверный тип задания');
    }

    return $validationRes;
}

function addErrorMessage(&$validationResult, $message)
{
    $validationResult['success'] = false;
    $validationResult['messages'][] = array('type' => 'Error', 'text' => $message);
}

function insertTask($data, $db)
{
    $st = $db->prepare('INSERT INTO tasks( creationdate, taskname, description, tasktype ) VALUES( NOW(), :taskname, :description, :tasktype )');
    $st->execute(array(':taskname' => $data['taskname'], ':description' => $data['description'], ':tasktype' => $data['tasktype']));

    $insertId = $db->lastInsertId();
    return array('type' => 'success', 'message' => sprintf('Задание с id = %s вставлено в БД', $insertId));
}

$host = "localhost";
$user = "root";
$password = "root";
$database = "tasksproject";

try {
    $db = new PDO(sprintf('mysql:host=%s;dbname=%s', $host, $database), $user, $password);
} catch (PDOException $e) {
    echo '<span class="error_message">Error!: ' . $e->getMessage() . "<br/>";
    die();
}

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

    $data = getDataFromRequest($_POST);
    $validationResult = validateData($data, $db);

    $messageOut = array();

    if( $validationResult['success'] == true ) {
        $messageOut[] = insertTask($data, $db);
    } else {
        $messageOut = $validationResult['messages'];
    }

    foreach ($messageOut as $messages) {
        echo '<span class = "error_message">', $messages['type'], ': ', $messages['text'], '</span>';
    }


}

?>


<table>

    <form action="" method="post">
    <tr><td><label for="taskname">Название задания</label></td> <td><input id="taskname" type="text" name="taskname"/></td></tr>

    <tr><td><label for="description">Описание</label></td> <td><textarea id="description" name="description" cols=60 rows=10></textarea></td></tr>

    <tr><td><label for="tasktype">Тип задания</label></td> <td><select id="tasktype" name="tasktype">
            <option value="development">Development
            <option value="planning">Planning
            <option value="debugging">Debugging
        </select></td>
     </tr>
    <tr><td></td><td align="right"><input type="submit"/></td></tr>
</form>

</table>

<table border="1">
    <tr>
        <td>Дата создания</td>
        <td>Название задания</td>
        <td>Описание</td>
        <td>Тип задания</td>
    </tr>
    <?php

    $messagesPerPage = 10;

    if(!(empty($_GET['page'])) && ((int)$_GET['page'] > 0)){
        $page = $_GET['page'];
    }
    else{
        $page = 1;
    }

    $tmp = $db->prepare('SELECT COUNT(*) FROM tasks');
    $tmp->execute();
    $row_count = $tmp->fetch(PDO::FETCH_ASSOC);

    $totalPages = intval(($row_count['COUNT(*)'] - 1) / $messagesPerPage) + 1;

    if($page > $totalPages) $page = $totalPages;

    $start = $page * $messagesPerPage - $messagesPerPage;

    $stmt = $db->query(sprintf('SELECT * FROM tasks ORDER BY creationdate DESC LIMIT %d, %d', $start, $messagesPerPage));
    while($postrow = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo '<tr>';
        echo '<td>' . $postrow['creationdate'] . '</td>';
        echo '<td>' . $postrow['taskname'] . '</td>';
        echo '<td>' . $postrow['description'] . '</td>';
        echo '<td>' . $postrow['tasktype'] . '</td>';
        echo '</tr>';
    }

    ?>

</table>

<?php

    $firstPage = '<a href=?page=1>FirstPage</a>';
    if($page != $totalPages) $lastPage = '<a href=?page='.$totalPages.'>LastPage</a>';

    if($page+5 > $totalPages){

        echo $firstPage.'<b>'.$page.'</b>';
        for($i = $page; $i < $totalPages; $i++){
            echo ' | <a href=?page='.($i+1).'>'.($i+1).'</a>';
        }
        echo $lastPage;
    }
    else{
        echo $firstPage.'<b>'.$page.'</b>';
        for($i=$page; $i < $page+5; $i++){
            echo ' | <a href=?page='.($i+1).'>'.($i+1).'</a>';
        }
        echo $lastPage;
    }
?>

</body>

</html>