<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $config = json_decode(file_get_contents('config.json'), true);
    
    $table = $_POST['table'];
    
    $connection = mysqli_connect(
        $config['database']['host'],
        $config['database']['username'],
        $config['database']['password'],
        $config['database']['database_name']
    );
    
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    $query = "DESCRIBE " . $table;
    $result = mysqli_query($connection, $query);
    
    if ($result) {
        $fields = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $fields[] = $row['Field'];
            echo "<li><a href='#' class='field-link' data-fields='" . $row['Field'] . "'>" . $row['Field'] . "</a></li>";
        }
        echo "<li><a href='#' class='field-link' data-fields='" . implode(",", $fields) . "'>" . implode(", ", $fields) . "</a></li>";
    } else {
        echo "Error fetching fields: " . mysqli_error($connection);
    }
    
    mysqli_close($connection);
}
?>
