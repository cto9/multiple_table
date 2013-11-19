<form action="" method="get">
    <p>Width <input type="number" name="width" /><?php if(isset($_GET['width']) && !is_get_variable_valid('width',0,1000)) echo 'error number' ?></p>
    <p>Height <input type="number" name="height" /></p>
    <p><input type="submit" /></p>
</form>

<?php

function is_get_variable_valid($varName, $minValue, $maxValue){
    return isset($_GET[$varName]) && $_GET[$varName] > $minValue && $_GET[$varName] < $maxValue;
}


if(is_get_variable_valid('width',0,1000) && is_get_variable_valid('height',0,1000)){
    $width = $_GET['width'];
    $height = $_GET['height'];
}
else{
    $width = 10;
    $height = 10;
}

echo '<table>';

echo '<tr>';
for($i = 1; $i <= $height; $i++){
    echo '<tr>';
    for($j = 1; $j <=$width; $j++){ ?>
        <td <?php if($j % 2 == 0) echo 'bgcolor="grey"'?>> <?php echo ($i*$j) ?> </td>;
    <?php

    }
    echo "</tr>";
}
echo "</table>";