<?php

class LocalApi {

    public $db;
    public $stmt;
    public $sql;
    public $toexecute;
    public $error;
    public $ifcommand;
    public $console;
    public $method;

    public function __construct()
    {
        $this->toexecute = null;
        $this->error     = null;

        $this->ifcommand = false;
        // Set console command, if table=command and method is POST
        $this->console   = ".../iotLocalNetworkServer/run.sh";

        $this->db = new PDO('mysql:host=localhost;dbname=api', 'user', 'pass');
        // $this->db = new PDO('sqlite:iot.sqlite3');
        // Set errormode to exceptions
        $this->db->setAttribute(PDO::ATTR_ERRMODE,
                                PDO::ERRMODE_EXCEPTION);
    }

    public function createRequest()
    {
        try {
            // get the HTTP method, path and body of the request
            $this->method = $_SERVER['REQUEST_METHOD'];
            $request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
            $input = json_decode(file_get_contents('php://input'),true);

            // retrieve the table and key from the path
            $table = preg_replace('/[^a-z0-9_]+/i','',array_shift($request));
            $id = array_shift($request)+0;

            // Check if we run console command
            if ($this->method === 'POST' and $table === "command") {
                $this->ifcommand = true;
                error_log("ifcommand true", 0);
            }

            // retrieve dataforms for dbo sql preparement
            $keys   = array();
            $values = array();
            $marks  = array();
            $sets   = array();
            if ($input) {
                foreach($input as $key => $value) {
                    $keys[]   = $key;
                    $marks[]  = "?";
                    $values[] = $value;
                    $sets[]   = "$key = ?";
                }
            }

            // create SQL based on HTTP method
            switch ($this->method) {
              case 'GET':
                if ($id > 0) {
                    $this->sql = "SELECT * FROM $table WHERE id = ?";
                    $this->toexecute = [$id];
                } else {
                    $this->sql = "SELECT * FROM $table";
                }
                break;
              case 'PUT':
                $this->sql = "UPDATE $table SET ".implode(', ', $sets)." WHERE id = ?";
                $values[] = $id;
                $this->toexecute = $values;
                break;
              case 'POST':
                $this->sql = "INSERT INTO $table (".implode(', ', $keys).") VALUES (".implode(', ', $marks).")";
                $this->toexecute = $values;
                break;
              case 'DELETE':
                $this->sql = "DELETE FROM $table WHERE id = ?";
                $this->toexecute = [$id];
                break;
            }
            return true;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function run()
    {
        if ($this->createRequest()) {
            $this->executeRequest();
        }
        echo $this->getRespond();
        $this->close();
        $this->runTerminal();
    }

    public function setSql($s)
    {
        $this->sql = $s;
    }

    public function executeRequest()
    {
        try {
            $this->stmt = $this->db->prepare($this->sql);
            return $this->stmt->execute($this->toexecute);

        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function runTerminal()
    {
        if ($this->ifcommand and $this->error == null) {
            error_log("runTerminal: " . $this->console, 0);
            exec($this->console);
        }
    }

    public function getRespond()
    {
        if ($this->error != null) {
            return $this->getErrorRespond();
        }

        try {
            if ($this->method != "GET") {
                $result = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
                return json_encode($result);
            }
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return $this->getErrorRespond();
        }
    }

    public function getErrorRespond()
    {
        return '{"error":"'.$this->error.'"}';
    }

    public function close()
    {
        $this->db = null;
    }
}

?>
