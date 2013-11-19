<form action="" method="get">
    <p>Width <input type="number" name="width" /></p>
    <p>Height <input type="number" name="height" /></p>
    <p><input type="submit" /></p>
</form>

<?php
    $width = $_GET['width'];
    $height = $_GET['height'];

    echo "<table>";
    for($i = 1; $i <= $width; $i++){
        echo "<tr>";
        for($j = 1; $j <=$height; $j++){
            echo "<td>".($i * $j)."</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
?>