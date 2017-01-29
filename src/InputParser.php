<?php

namespace RobLoach\LibretroNetplayRegistry;

class InputParser
{
    private $input;

    public function __construct($input = array())
    {
        $this->input = empty($input) ? $_GET : $input;
    }
    /**
     * Reads the GET parameters to load a new entry.
     */
    public function getEntry()
    {
        $entryProperties = array('username', 'corename', 'coreversion', 'gamename', 'gamecrc');
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

        if (empty($addedEntry)) {
            return null;
        }
        $addedEntry['ip'] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
        $addedEntry['time'] = time();
        $addedEntry['port'] = '55435';
        if (isset($this->input['port'])) {
            $port = $this->cleanProperty($this->input['port']);
            if (!empty($port)) {
                $addedEntry['port'] = $port;
            }
        }
        if (isset($this->input['haspassword'])) {
            $addedEntry['haspassword'] = empty($this->input['haspassword']);
        }

        return $addedEntry;
    }

    /**
     * Cleans the given GET parameter.
     */
    public function cleanProperty($input = '')
    {
        return preg_replace('/[^-a-zA-Z0-9-()[]!,&\'._ ]/', '', $input);
    }
}
