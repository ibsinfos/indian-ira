<?php

namespace IndianIra\Utilities;

trait FormatsDateAndTime
{
    /**
     * Format the date and time of the given column.
     *
     * @param   string  $column
     * @param   string  $format
     * @param   string  $timezone
     * @return  string
     */
    public function dateAndTime($column, $format = 'D jS F, Y, h:i A', $timezone = 'Asia/Kolkata')
    {
        return $this->{$column}
             ->timezone($timezone)
             ->format($format);
    }

    /**
     * Display the created_at date in default format.
     *
     * @param   string  $format
     * @return  string
     */
    public function formatsCreatedAt($format = 'D jS F, Y, h:i A')
    {
        return $this->dateAndTime('created_at', $format);
    }
}
