<?php

class Save
{

    public function __construct($storage)
    {
        $this->name = $storage["name"];
        $this->phoneNum = $storage["phoneNum"];
        $this->message = $storage["message"];
        if ($storage["storage"] == "file") {
            $this->storage = "file";
            $this->saveToFile();

        } else {
            $this->storage = "db";
            $this->saveToDB();
            $this->response = "Сохранено в базу данных";
        }

        echo json_encode($this, JSON_UNESCAPED_UNICODE);
    }
    private function saveToDB()
    {
        $db = new Database;
        $db->execute("CREATE TABLE IF NOT EXISTS `pdo_request` (
            `id` int(10) NOT NULL,
            `Name` text NOT NULL,
            `PhoneNum` bigint(20) NOT NULL,
            `Message` text NOT NULL,
            `Timerequest` int(15) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
          
        $db->execute("INSERT INTO `pdo_request`(`Name`, `PhoneNum`, `Message`, `Timerequest`) VALUES ('" . $this->name . "'," . $this->phoneNum . ",'" . $this->message . "', " . time() . ")");

    }
    private function saveToFile()
    {
        $filedir = '';
        $filename = 'requests.txt';
        $f = fopen($filedir.$filename, "a");
        $datastring = [
            "name" => $this->name,
            "phoneNum" => $this->phoneNum,
            "message" => $this->message,
            "uts" => time()
        ];
        $datastring = json_encode($datastring, JSON_UNESCAPED_UNICODE);
        
        fwrite($f, $datastring);

        fclose($f);

        $this->response = 'Cохранено в файле '.$filedir.'/'.$filename; 
    }
}

class Database
{
    private $link;
    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        $config  = require_once 'config.php';
        $dsn = 'mysql:host=' . $config['host'] . ';dbname=' . $config['db_name'] . ';charset=' . $config['charset'];
        $this->link = new PDO($dsn, $config['username'], $config['password']);
        return $this;
    }
    public function execute($sql)
    {
       /*  echo 'сохранено в базе данных'; */
        $sth = $this->link->prepare($sql);
        return $sth->execute();
    }
    public function query($sql)
    {
        $exe = $this->execute($sql);
        $result = $exe->fetchAll(PDO::FETCH_ASSOC);
        if ($result === false) {
            return [];
        }
        return $result;
    }
}

$run = new Save($_GET);
?>
