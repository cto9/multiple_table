<html>

<head>
    <meta charset="utf-8">
    <title>Tasks Project</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<?php

include 'dbConnect.php';
include 'myFunctions.php';

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
        <tr>
            <td><label for="taskname">Название задания</label></td>
            <td><input id="taskname" type="text" name="taskname"/></td>
        </tr>

        <tr>
            <td><label for="description">Описание</label></td>
            <td><textarea id="description" name="description" cols=60 rows=10></textarea></td>
        </tr>

        <tr>
            <td><label for="tasktype">Тип задания</label></td>
            <td><select id="tasktype" name="tasktype">
                    <option value="development">Development
                    <option value="planning">Planning
                    <option value="debugging">Debugging
                </select></td>
        </tr>
        <tr>
            <td></td>
            <td align="right"><input type="submit"/></td>
        </tr>
    </form>

</table>

<?php

$messagesPerPage = 10;

if( !(empty($_GET['page'])) && ((int)$_GET['page'] > 0) ) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$tmp = $db->prepare('SELECT COUNT(*) FROM tasks');
$tmp->execute();
$row_count = $tmp->fetch(PDO::FETCH_ASSOC);

$totalPages = (int)(($row_count['COUNT(*)'] - 1) / $messagesPerPage) + 1;

if( $page > $totalPages ) $page = $totalPages;

$start = $page * $messagesPerPage - $messagesPerPage;

?>

<table border="1">
    <tr>
        <td>Дата создания</td>
        <td>Название задания</td>
        <td>Описание</td>
        <td>Тип задания</td>
    </tr>

    <?php

    $stmt = $db->query(sprintf('SELECT * FROM tasks ORDER BY creationdate DESC LIMIT %d, %d', $start, $messagesPerPage));
    while ($postrow = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<tr>';
        echo '<td>' . $postrow['creationdate'] . '</td>';
        echo '<td>' . $postrow['taskname'] . '</td>';
        echo '<td>' . $postrow['description'] . '</td>';
        echo '<td>' . $postrow['tasktype'] . '</td>';
        echo '<td> <a href="dataEdit.php?id='.$postrow['id'].'"> Edit </td> ';
        echo '</tr>';
    }

    ?>

</table>

<?php

$lastIteration = ($page + 5 > $totalPages) ? $totalPages : $page + 5;

echo '<a href=?page=1>FirstPage</a>' . '<b>' . $page . '</b>';
for ($i = $page; $i < $lastIteration; $i++) {
    echo ' | <a href=?page=' . ($i + 1) . '>' . ($i + 1) . '</a>';
}
echo ($page != $totalPages) ? '<a href=?page=' . $totalPages . '>LastPage</a>' : '<a href=?page=' . $page . '>LastPage</a>';

?>

</body>

</html>