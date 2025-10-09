<?php

namespace App\Services\export;

use App\Models\User;
use App\Services\common\UserService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use SplFileObject;


class ExportService
{
    // start new function for create and dowload csv file as sending request more and more
    private function makeAjaxRequest($offset, $order_by, $sql, $chunkSize)
    {
        // Retrieve data from the Message model using the offset and limit
        $data = $sql->orderBy($order_by[0], $order_by[1])->skip($offset)->take($chunkSize)->get();
        $has_nex_row = $sql->orderBy($order_by[0], $order_by[1])->skip($offset + $chunkSize)->take($chunkSize)->exists();
        // Determine if there is more data available
        $hasMoreData = false;
        if ($has_nex_row) {
            $hasMoreData = true;
        }
        return [
            'data' => $data,
            'hasMoreData' => $hasMoreData,
        ];
    }
    private function storeData($data, $export_col)
    {
        $file = fopen($this->getCsvFilePath(), 'a');
        $spl_file = new SplFileObject($this->getCsvFilePath(), 'r');

        // Check if the file is empty / store header to csv
        $isFileEmpty = ($spl_file->getSize() === 0);
        if ($isFileEmpty) {
            fputcsv($file, [
                'Ticket',
                'Account',
                'Email',
                'IB Email',
                'Symbol',
                'Profit',
                'Open Time',
                'Close Time',
                'Volume',
            ]);
        }
        // store data to csv
        foreach ($data as $value) {
            $profit = $value->PROFIT;
            $close_time = date('Y-m-d', strtotime($value->CLOSE_TIME));
            if ($close_time === "1970-01-01") {
                $close_time = "Trade Running";
                $profit = "---";
            } else {
                $close_time = date('d M Y h:i:s A', strtotime($value->CLOSE_TIME));
            }

            // PUT DATA IN CSV FILE
            fputcsv($file, [
                $value->TICKET,
                $value->LOGIN,
                $value->email,
                UserService::get_ib_email($value->user_id),
                $value->SYMBOL,
                $profit,
                date('d M Y h:i:s A', strtotime($value->OPEN_TIME)),
                $close_time,
                round(($value->VOLUME / 100), 2),
            ]);
        }
        fclose($file);
    }

    public function export_all($options = [], $order_by = [], $export_col = [])
    {
        // Set the initial offset and limit
        $offset = $options['offset'];
        $limit = $options['chunkSize'];
        $total_import = $options['total_import'];
        $csvFilePath = $this->getCsvFilePath();
        // Make the AJAX request to retrieve data
        $response = $this->makeAjaxRequest($offset, $order_by, $options['sql'], $options['chunkSize']);
        if ($total_import >= 2000) {
            //create new file
            // Store the data in a CSV file
            $this->storeData($response['data'], $export_col, true);
            $total_import = 0;
        } else {
            $this->storeData($response['data'], $export_col, false);
            $total_import += $limit;
        }
        // Check if there is more data available
        if ($response['hasMoreData']) {
            // Send the next AJAX request with updated offset
            $nextOffset = ($offset + $limit);
            gc_collect_cycles(); // Perform garbage collection
            // Return the JSON response with the necessary information
            $response = [
                'offset' => $nextOffset,
                'total_import' => $total_import,
            ];
            if ($total_import >= 2000) {
                $response['file_paths'] = $csvFilePath;
            }
            return Response::json($response);
        } else {
            // Store the final set of data and return the CSV file URL
            // Return the CSV file URL
            return Response::download($csvFilePath, 'export.csv')->deleteFileAfterSend(true);
        }
    }
    private function getCsvFilePath()
    {
        // Define the path and filename for the CSV file
        $folder = public_path('export');
        $filename = 'export.csv';
        // Create the directory if it doesn't exist
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }
        return $folder . '/' . $filename;
    }
}
