<?php

class Registry {
	private $db = null;
	private $insert;
	private $select;
	private $clearOldEntries;

	function __construct($name = '.registry') {
		$this->db = new PDO("sqlite:$name.sqlite");
    	$this->db->exec('CREATE TABLE IF NOT EXISTS registry (
    		id INTEGER PRIMARY KEY,
    		username TEXT,
            ip TEXT,
            port INTEGER,
    		corename TEXT,
    		coreversion TEXT,
    		gamename TEXT,
    		gamecrc TEXT,
    		created INTEGER
    	)');

    	$this->insert = $this->db->prepare('INSERT INTO
    		registry (
	    		username,
                ip,
                port,
	    		corename,
	    		coreversion,
	    		gamename,
	    		gamecrc,
	    		created
	    	)
	    	VALUES (
	    		:username,
                :ip,
                :port,
	    		:corename,
	    		:coreversion,
	    		:gamename,
	    		:gamecrc,
	    		:created
	    	)');
    	$this->select = $this->db->prepare('SELECT * FROM registry');
    	$this->clearOldEntries = $this->db->prepare('DELETE FROM registry where created <= :time');
        $this->clearOld();
    }

    function insert($newEntry, $throttle = true) {
        if (!isset($newEntry['ip'])) {
            $newEntry['ip'] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
        }
        if (!isset($newEntry['port'])) {
            $newEntry['port'] = 55435;
        }
        if (!isset($newEntry['created'])) {
            $newEntry['created'] = time();
        }

        $added = FALSE;
        $entries = $this->selectAll();
        foreach ($entries as $index => $entry) {
            if ($throttle) {
                if ($newEntry['ip'] == $entry['ip'] && $newEntry['created'] - $entry['created'] < 10) {
                    $added = TRUE;
                    break;
                }
            }

            // Update unique entries by username, IP and Port.
            if ($entry['username'] == $newEntry['username'] && $entry['ip'] == $newEntry['ip'] && $entry['port'] == $newEntry['port']) {
                // TODO: Update the entry?
                $added = TRUE;
                break;
            }
        }

        if (!$added) {
        	$this->insert->bindParam(':username', $newEntry['username'], PDO::PARAM_STR);
            $this->insert->bindParam(':ip', $newEntry['ip'], PDO::PARAM_STR);
            $this->insert->bindParam(':port', $newEntry['port'], PDO::PARAM_INT);
        	$this->insert->bindParam(':corename', $newEntry['corename'], PDO::PARAM_STR);
        	$this->insert->bindParam(':coreversion', $newEntry['coreversion'], PDO::PARAM_STR);
        	$this->insert->bindParam(':gamename', $newEntry['gamename'], PDO::PARAM_STR);
        	$this->insert->bindParam(':gamecrc', $newEntry['gamecrc'], PDO::PARAM_STR);
        	$this->insert->bindParam(':created', $newEntry['created'], PDO::PARAM_INT);
        	return $this->insert->execute();
        }
        return false;
    }

    function clearOld($age = 300) {
    	$time = time() - $age;
    	$this->clearOldEntries->bindParam(':time', $time, PDO::PARAM_INT);
    	$this->clearOldEntries->execute();
    }

    function selectAll() {
    	$this->select->execute();
    	return $this->select->fetchAll();
    }
}