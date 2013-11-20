<html>

<head>
    <meta charset="utf-8">
    <title>Tasks Project</title>
</head>

<body>

<form action="" method="get">
    <p><label for="taskname">Название задания</label> <input id="taskname" type="text" name="taskname" /></p>
    <p><label for="description">Описание</label> <textarea id="description" name="description" cols=60 rows=10></textarea></p>
    <p><label for="tasktype">Тип задания</label> <select id="tasktype" name="tasktype">
            <option value="development">Development
            <option value="planning">Planning
            <option value="debugging">Debugging
            </select></p>
    <p><input type="submit" /></p>
</form>

</body>

</html>