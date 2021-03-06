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

function insertTaskLog($currentID, $data, $db)
{

    $obj = $db->query(sprintf('SELECT * FROM tasks WHERE id = %d', $currentID));
    $oldData = $obj->fetch(PDO::FETCH_ASSOC);

    if( $oldData['taskname'] != $data['taskname'] ) {
        $logMessage = sprintf("Поле taskname было изменено с %s на %s", $oldData['taskname'], $data['taskname']);
        $stt = $db->prepare('INSERT INTO task_log( task_id, modifiedDate, logMessage ) VALUES(:currentID, NOW(), :logMessage )');
        $stt->execute(array(':currentID' => $currentID, ':logMessage' => $logMessage));
    }

    if( $oldData['description'] != $data['description'] ) {
        $logMessage = sprintf('Поле description было изменено с %s на %s', $oldData['description'], $data['description']);
        $stt = $db->prepare('INSERT INTO task_log( task_id, modifiedDate, logMessage ) VALUES(:currentID, NOW(), :logMessage )');
        $stt->execute(array(':currentID' => $currentID, ':logMessage' => $logMessage));
    }

    if( $oldData['tasktype'] != $data['tasktype'] ) {
        $logMessage = sprintf('Поле tasktype было изменено с %s на %s', $oldData['taskytpe'], $data['tasktypel']);
        $stt = $db->prepare('INSERT INTO task_log( task_id, modifiedDate, logMessage ) VALUES(:currentID, NOW(), :logMessage )');
        $stt->execute(array(':currentID' => $currentID, ':logMessage' => $logMessage));
    }

    $st = $db->prepare('UPDATE tasks SET creationdate=NOW(), taskname = :taskname, description = :description, tasktype = :tasktype WHERE id = :currentID');
    $st->execute(array(':taskname' => $data['taskname'], ':description' => $data['description'], ':tasktype' => $data['tasktype'], ':currentID' => $currentID));

    return;
}

function checkID($id, $db)
{

    $idMessagesError = array('success' => true, 'text' => array());

    if( (empty($id)) || ((int)$id < 1) ){
        $idMessagesError['success'] = false;
        $idMessagesError['text'] = "Не правильный id";
        return $idMessagesError;
    }

        $tmp = $db->prepare(sprintf('SELECT COUNT(*) FROM tasks WHERE id=%d', $id));
        $tmp->execute();
        $row_count = $tmp->fetch(PDO::FETCH_ASSOC);

        if( $row_count['COUNT(*)'] == 0 ) {
            //echo 'Задания с указанным id не существует';
            $idMessagesError['text'][] = "Задания с указанным id не существует";
            //return false;
            return $idMessagesError;
        }

        return $idMessagesError;

}