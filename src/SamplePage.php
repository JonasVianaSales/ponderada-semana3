<?php include "../inc/dbinfo.inc"; ?>
<html>

<body>
    <h1>Sample page</h1>
    <?php

    /* Connect to MySQL and select the database. */
    $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

    if (mysqli_connect_errno())
        echo "Failed to connect to MySQL: " . mysqli_connect_error();

    $database = mysqli_select_db($connection, DB_DATABASE);

    /* Ensure that the EMPLOYEES table exists. */
    VerifyEmployeesTable($connection, DB_DATABASE);

    /* If input fields are populated, add a row to the EMPLOYEES table. */
    $employee_name = htmlentities($_POST['NAME']);
    $employee_address = htmlentities($_POST['ADDRESS']);

    if (strlen($employee_name) || strlen($employee_address)) {
        AddEmployee($connection, $employee_name, $employee_address);
    }
    ?>

    <!-- Input form -->
    <form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
        <table border="0">
            <tr>
                <td>NAME</td>
                <td>ADDRESS</td>
            </tr>
            <tr>
                <td>
                    <input type="text" name="NAME" maxlength="45" size="30" />
                </td>
                <td>
                    <input type="text" name="ADDRESS" maxlength="90" size="60" />
                </td>
                <td>
                    <input type="submit" value="Add Data" />
                </td>
            </tr>
        </table>
    </form>

    <form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
        <table border="0">
            <tr>
                <td>ID</td>
                <td>Nome</td>
                <td>Idade</td>
                <td>Dinheiro</td>
            </tr>
            <tr>
                <td>
                    <input type="number" name="id" maxlength="16" size="20" />
                </td>
                <td>
                    <input type="text" name="nome" maxlength="255" size="60" />
                </td>
                <td>
                    <input type="text" name="idade" maxlength="2" size="10" />
                </td>
                <td>
                    <input type="number" name="dinheiro" maxlength="16" size="20" />
                </td>
                <td>
                    <input type="submit" value="Enviar Dados" />
                </td>
            </tr>
        </table>
    </form>

    <!-- Display table data. -->
    <table border="1" cellpadding="2" cellspacing="2">
        <tr>
            <td>ID</td>
            <td>NAME</td>
            <td>ADDRESS</td>
        </tr>

        <?php

        $result = mysqli_query($connection, "SELECT * FROM EMPLOYEES");

        while ($query_data = mysqli_fetch_row($result)) {
            echo "<tr>";
            echo "<td>", $query_data[0], "</td>",
                "<td>", $query_data[1], "</td>",
                "<td>", $query_data[2], "</td>";
            echo "</tr>";
        }
        ?>

    </table>

    <table border="1" cellpadding="2" cellspacing="2">
        <tr>
            <td>ID</td>
            <td>Nome</td>
            <td>Idade</td>
            <td>Dinheiro</td>
        </tr>

        <?php

        $result = mysqli_query($connection, "SELECT * FROM ponderada");

        while ($query_data = mysqli_fetch_row($result)) {
            echo "<tr>";
            echo "<td>", $query_data[0], "</td>",
                "<td>", $query_data[1], "</td>",
                "<td>", $query_data[2], "</td>",
                "<td>", $query_data[3], "</td>";
            echo "</tr>";
        }
        ?>

    </table>

    <!-- Clean up. -->
    <?php

    mysqli_free_result($result);
    mysqli_close($connection);

    ?>

</body>

</html>


<?php

/* Add an employee to the table. */
function AddEmployee($connection, $name, $address)
{
    $n = mysqli_real_escape_string($connection, $name);
    $a = mysqli_real_escape_string($connection, $address);

    $query = "INSERT INTO EMPLOYEES (NAME, ADDRESS) VALUES ('$n', '$a');";

    if (!mysqli_query($connection, $query))
        echo ("<p>Error adding employee data.</p>");
}

/* Adiciona um campo para a ponderada. */
function AddPonderada($connection, $id, $name, $age, $money)
{
    $i = mysqli_real_escape_string($connection, $id);
    $n = mysqli_real_escape_string($connection, $name);
    $a = mysqli_real_escape_string($connection, $age);
    $m = mysqli_real_escape_string($connection, $money);

    $query = "INSERT INTO ponderada (id, nome , idade, dinheiro) VALUES ('$i', '$n', '$a', '$m');";

    if (!mysqli_query($connection, $query))
        echo ("<p>Erro ao adicionar uma pessoa a ponderada.</p>");
}

/* Check whether the table exists and, if not, create it. */
function VerifyEmployeesTable($connection, $dbName)
{
    if (!TableExists("EMPLOYEES", $connection, $dbName)) {
        $query = "CREATE TABLE EMPLOYEES (
         ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
         NAME VARCHAR(45),
         ADDRESS VARCHAR(90)
       )";

        if (!mysqli_query($connection, $query))
            echo ("<p>Error creating table.</p>");
    }
}

/* Check for the existence of a table. */
function TableExists($tableName, $connection, $dbName)
{
    $t = mysqli_real_escape_string($connection, $tableName);
    $d = mysqli_real_escape_string($connection, $dbName);

    $checktable = mysqli_query(
        $connection,
        "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'"
    );

    if (mysqli_num_rows($checktable) > 0)
        return true;

    return false;
}
?>