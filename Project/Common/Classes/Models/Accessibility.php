<?php

/**
 * Accessibility short summary.
 *
 * Accessibility description.
 *
 * @version 1.0
 * @author andre
 */
class Accessibility
{
    public $Accessibility_Code;
    public $Description;

    public function __construct($id, $description) {
        $this->Accessibility_Code = $id;
        $this->Description = $description;
    }
}