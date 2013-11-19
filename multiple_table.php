<form action="" method="get">
    <p>Width <input type="number" name="width" /></p>
    <p>Height <input type="number" name="height" /></p>
    <p><input type="submit" /></p>
</form>

<?php

    if((isset($_GET['width']) && isset($_GET['height']))){
        if($_GET['width'] > 0 && $_GET['width'] < 1000 && $_GET['height'] > 0 && $_GET['height'] < 1000){
            $width = $_GET['width'];
            $height = $_GET['height'];
        }
    }
    else{
        $width = 10;
        $height = 10;
    }

    echo "<table>";
    for($i = 1; $i <= $width; $i++){
        echo "<tr>";
        for($j = 1; $j <=$height; $j++){

            if(($i == 1) || ($j == 1)){
                echo '<td>'.($i * $j).'</td>';
            }
            else if($j % 2){
                echo '<td bgcolor="grey">'.($i * $j).'</td>';
            }
            else
                echo '<td>'.($i * $j).'</td>';
        }
        echo "</tr>";
    }
    echo "</table>";
?>