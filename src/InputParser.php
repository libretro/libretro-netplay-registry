<?php

namespace RobLoach\LibretroNetplayRegistry;

/**
 * Class InputParser.
 */
class InputParser
{
    /**
     * @var array
     */
    private $input;

    /**
     * InputParser constructor.
     *
     * @param array $input
     */
    public function __construct($input = array())
    {
        $this->input = empty($input) ? $_GET : $input;
    }

    /**
     * Reads the GET parameters to load a new entry.
     */
    public function getEntry()
    {
        // Retrieve the required properties.
        $entryProperties = array('corename', 'coreversion', 'gamename', 'gamecrc');
        $addedEntry = array();
        // Fill in all the properties.
        foreach ($entryProperties as $property) {
            // Retrieve the given property.
            $value = isset($this->input[$property]) ? $this->cleanProperty($this->input[$property]) : '';
            // If it's valid, then add it to the entry.
            if (!empty($value) || $value === '0') {
                $addedEntry[$property] = $value;
            } else {
                // The properties and invalid, ignore the new entry.
                $addedEntry = array();
                break;
            }
        }

        // If any of the properties were not there, return null.
        if (empty($addedEntry)) {
            return null;
        }

        // Fill in the remaining fields.
        $addedEntry['ip'] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';

        // Assign the username.
        $username = isset($this->input['username']) ? $this->input['username'] : '';
        $username = $this->cleanProperty($username);
        if (empty($username)) {
            $username = $addedEntry['ip'];
        }
        $addedEntry['username'] = $username;

        // Creation time.
        $addedEntry['created'] = time();

        // Find the port.
        $addedEntry['port'] = '55435';
        if (isset($this->input['port'])) {
            $port = $this->cleanProperty($this->input['port']);
            if (!empty($port)) {
                $addedEntry['port'] = $port;
            }
        }

        // Whether or not the server requires a password.
        if (isset($this->input['haspassword'])) {
            $addedEntry['haspassword'] = empty($this->input['haspassword']);
        }

        return $addedEntry;
    }

    /**
     * Cleans the given GET parameter.
     *
     * @param string $input
     *
     * @return mixed
     */
    public function cleanProperty($input = '')
    {
        return preg_replace('/[^ .\/\&\(\)\'\[\]\{\}\,_A-Za-z0-9\-\[\]()]/', '', $input);
    }
}
