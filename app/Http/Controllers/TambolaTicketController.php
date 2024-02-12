<?php

namespace App\Http\Controllers;

use App\Models\TambolaTicket;
use Illuminate\Http\Request;

class TambolaTicketController extends Controller
{
 
    public function generateTickets()
    {
        $tickets = [];
        
        // Generate 24 tickets
        for ($i = 0; $i < 24; $i++) {
            $tickets[] = $this->generateSingleTicket();
        }
        
        TambolaTicket::insert(['tickets' => json_encode($tickets)]);

        return response()->json(['tickets' => $tickets]);
    }

    private function generateSingleTicket()
    {
        $ticket = [];

        // Generate random numbers without repetition
        $numbers = $this->generateRandomNumbers();
        $numbers = array_chunk($numbers, 5); // Split into rows
        
        // Randomly select 5 cells in each row without repetition
        foreach ($numbers as $row) {
            $selectedIndices = $this->generateRandomIndices();
            $rowNumbers = [];

            foreach ($selectedIndices as $index) {
                $rowNumbers[$index] = array_shift($row);
            }

            $ticket[] = $this->fillRow($rowNumbers);
        }

        return $ticket;
    }

    private function generateRandomNumbers()
    {
        $numbers = range(1, 89);
        shuffle($numbers);
        return array_slice($numbers, 0, 15);
    }

    private function generateRandomIndices()
    {
        return array_rand(range(0, 8), 5);
    }

    private function fillRow($rowNumbers)
    {
        $row = [];

        // Fill row with numbers based on column rules
        for ($i = 0; $i < 9; $i++) {
            if (isset($rowNumbers[$i])) {
                $row[$i] = $rowNumbers[$i];
            } else {
                $row[$i] = 0; // Blank cell
            }
        }

        return $row;
    }

    public function fetchTickets()
    {
        $tickets = TambolaTicket::all();

        return response()->json(['tickets' => $tickets]);
    }

    
}
