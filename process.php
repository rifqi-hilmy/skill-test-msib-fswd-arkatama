<?php
function cekUmur($data)
{
    $data = preg_replace('/[^a-zA-Z0-9]/', '', $data);
    $data = str_ireplace(['TAHUN', 'THN', 'TH'], '', $data);
    $age = intval($data);
    return $age;
}

function cekData($data)
{
    $sisaAngka = strcspn($data, '0123456789');
    $name = substr($data, 0, $sisaAngka);
    $sisaData = trim(substr($data, $sisaAngka));
    $sisaData = str_ireplace(['TAHUN', 'THN', 'TH'], '', $sisaData);

    if (preg_match('/\b(?:TAHUN|THN|TH)\b/i', $sisaData)) {
        $age = cekUmur($sisaData);
        $city = preg_replace('/\b(?:TAHUN|THN|TH)\b/i', '', $sisaData);
        $city = preg_replace('/[0-9]/', '', $city);
        $city = trim($city);
    } else {
        $age = cekUmur($sisaData);
        $city = preg_replace('/[0-9]/', '', $sisaData);
        $city = strtoupper(trim($city));
    }

    return [
        'name' => strtoupper($name),
        'age' => $age,
        'city' => $city
    ];
}

$conn = new mysqli('localhost', 'root', '', 'db_testing');
if ($conn->connect_error) {
    die('Koneksi gagal: ' . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["data"])) {
        $inputData = $_POST["data"];

        $userData = cekData($inputData);
        $name = $userData['name'];
        $age = $userData['age'];
        $city = $userData['city'];

        $name = strtoupper($name);
        $city = strtoupper($city);

        $sql = "INSERT INTO pengguna (NAME, AGE, CITY) VALUES ('$name', $age, '$city')";

        if ($conn->query($sql) === TRUE) {
            echo "Data berhasil disimpan ke database.";
        } else {
            echo "Terjadi kesalahan: " . $conn->error;
        }
    }
}

$conn->close();
?>
