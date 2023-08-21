<?php include "../inc/dbinfo.inc"; ?>
<html>

<style>

body {font-family: Monaco, monospace; background-color: #F5F5DC}

</style>

<body>
    <h1>Ponderada Semana 3</h1>
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

    /* Fields de Input da Ponderada*/

    $ponderada_gradYear = htmlentities($_POST['gradYear']);
    $ponderada_name = htmlentities($_POST['name']);
    $ponderada_age = htmlentities($_POST['age']);
    $ponderada_balance = htmlentities($_POST['balance']);

    if (strlen($ponderada_gradYear) && strlen($ponderada_name) && strlen($ponderada_age) && strlen($ponderada_balance)) {
        AddPonderada($connection, $ponderada_gradYear, $ponderada_name, $ponderada_age, $ponderada_balance);
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
                <td>Dois últimos digitos do ano de graduação</td>
                <td>Nome</td>
                <td>Idade</td>
                <td>Saldo</td>
            </tr>
            <tr>
                <td>
                    <input type="number" name="gradYear" maxlength="4" size="5" />
                </td>
                <td>
                    <input type="text" name="name" maxlength="255" size="40" />
                </td>
                <td>
                    <input type="text" name="age" maxlength="2" size="5" />
                </td>
                <td>
                    <input type="number" step="0.01" name="balance" maxlength="16" size="10" />
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

    <br>
    <br>
    <br>

    <table border="3" cellpadding="2" cellspacing="2">
        <tr>
            <td>Ano de Graduação</td>
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
function AddPonderada($connection, $gradYear, $name, $age, $balance)
{
    $gy= mysqli_real_escape_string($connection, $gradYear);
    $n = mysqli_real_escape_string($connection, $name);
    $a = mysqli_real_escape_string($connection, $age);
    $b = mysqli_real_escape_string($connection, $balance);

    $query = "INSERT INTO ponderada (gradYear, name, age, balance) VALUES ('$gy', '$n', '$a', '$b');";

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