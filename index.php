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
        $(document).ready(function () {
            var selectedTable = ""; // To store the selected table name
            var selectedFields = []; // To store the selected field names

            $(".table-link").click(function () {
                selectedTable = $(this).data("table");
                loadFields(selectedTable); // Load fields when a table is clicked
            });

            $("#field-list").on("click", ".field-link", function () {
                var field = $(this).data("fields");
                if (!selectedFields.includes(field)) {
                    selectedFields.push(field);
                } else {
                    // Remove the field if it's already selected
                    selectedFields = selectedFields.filter(function (value) {
                        return value !== field;
                    });
                }
                updateQueryText(); // Update the query when a field is selected/unselected
            });

            function loadFields(table) {
                $.ajax({
                    url: "get-fields.php",
                    method: "POST",
                    data: { table: table },
                    success: function (data) {
                        $("#field-list-items").html(data);
                        selectedFields = []; // Clear selected fields when a new table is selected
                        updateQueryText(); // Update the query
                    }
                });
            }

            function updateQueryText() {
                var query = "SELECT ";
                if (selectedFields.length > 0) {
                    query += "\n";
                    query += selectedFields.map(function (field) {
                        return "  " + field;
                    }).join(",\n");
                } else {
                    query += "*";
                }
                if (selectedTable) {
                    query += "\nFROM " + selectedTable;
                }
                $("#query-text").val(query);
            }
        });

    </script>
</body>
</html>
