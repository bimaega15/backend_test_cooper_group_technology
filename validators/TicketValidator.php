<?php

namespace app\validators;

class TicketValidator
{
    public static function validateTicket($postData)
    {
        $errorData = [];

        // Validasi Ticket[ticket_no]
        self::validateTicketNo($postData, $errorData);

        // Validasi Ticket[description]
        self::validateDescription($postData, $errorData);

        // Validasi TicketFile[file]
        self::validateTicketFile($postData, $errorData);

        return $errorData;
    }

    private static function validateTicketNo($postData, &$errorData)
    {
        if (isset($postData['Ticket']['ticket_no'])) {
            if (empty($postData['Ticket']['ticket_no'])) {
                array_push($errorData, 'Ticket[ticket_no] cannot be blank');
            }
        } else {
            array_push($errorData, 'Ticket[ticket_no] cannot be blank');
        }

        if (isset($postData['Ticket']['ticket_no'])) {
            if (strlen($postData['Ticket']['ticket_no']) > 32) {
                array_push($errorData, 'Ticket[ticket_no] max 32 characters');
            }
        } else {
            array_push($errorData, 'Ticket[ticket_no] max 32 characters');
        }
    }

    private static function validateDescription($postData, &$errorData)
    {
        if (isset($postData['Ticket']['description'])) {
            if (empty($postData['Ticket']['description'])) {
                array_push($errorData, 'Ticket[description] cannot be blank');
            }
        } else {
            array_push($errorData, 'Ticket[description] cannot be blank');
        }
    }

    private static function validateTicketFile($postData, &$errorData)
    {
        $errorFile = [];
        if (isset($postData['TicketFile'])) {
            if (empty($postData['TicketFile'])) {
                array_push($errorFile, 'TicketFile[file] cannot be blank');
            } else {
                foreach ($postData['TicketFile'] as $fileData) {
                    if (isset($fileData['file'])) {
                        if (empty($fileData['file'])) {
                            array_push($errorFile, 'TicketFile[file] cannot be blank');
                        }
                    } else {
                        array_push($errorFile, 'TicketFile[file] cannot be blank');
                    }
                }
            }
        } else {
            array_push($errorFile, 'TicketFile[file] cannot be blank');
        }

        if (!empty($errorFile)) {
            array_push($errorData, 'TicketFile[file] cannot be blank');
        }
    }
}
