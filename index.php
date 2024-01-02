<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SQL Query Builder</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>SQL Query Builder</h1>
    
    <div id="table-list">
        <h2>Tables:</h2>
        <ul id="table-list-items">
            <!-- Populate table list using PHP -->
            <?php
            $config = json_decode(file_get_contents('config.json'), true);
            $allowedTables = $config['allowed_tables'];
            
            foreach ($allowedTables as $table) {
                echo "<li><a href='#' class='table-link' data-table='$table'>$table</a></li>";
            }
            ?>
        </ul>
    </div>

    <div id="field-list">
        <h2>Fields:</h2>
        <ul id="field-list-items">
            <!-- Field list will be populated using AJAX -->
        </ul>
    </div>

    <div id="query">
        <h2>Generated Query:</h2>
        <textarea id="query-text" rows="5" cols="50"></textarea>
    </div>

    <script>
        // JavaScript to handle AJAX requests when a table is clicked
        $(document).ready(function () {
            $(".table-link").click(function () {
                var table = $(this).data("table");
                $.ajax({
                    url: "get-fields.php",
                    method: "POST",
                    data: { table: table },
                    success: function (data) {
                        $("#field-list-items").html(data);
                    }
                });
            });
        });
    </script>
</body>
</html>
