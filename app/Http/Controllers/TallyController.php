<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Exception;

class TallyController extends Controller
{
    private $tallyUrl;

    public function __construct()
    {
        $this->tallyUrl = env('TALLY_SERVER_URL'); // Initialize the Tally URL from .env
    }

    public function fetchSalesData()
    {
        // XML request to fetch sales data from Tally
        $xmlRequest = <<<XML
            <ENVELOPE>
                <HEADER>
                    <TALLYREQUEST>Export Data</TALLYREQUEST>
                </HEADER>
                <BODY>
                    <EXPORTDATA>
                        <REQUESTDESC>
                            <REPORTNAME>Sales Register</REPORTNAME>
                            <STATICVARIABLES>
                                <SVFROMDATE>20240101</SVFROMDATE>
                                <SVTODATE>20240131</SVTODATE>
                            </STATICVARIABLES>
                        </REQUESTDESC>
                    </EXPORTDATA>
                </BODY>
            </ENVELOPE>
        XML;

        try {
            // Send request to Tally using the URL from .env
            $response = Http::withBody($xmlRequest, 'application/xml')
                            ->post($this->tallyUrl);

            if ($response->successful()) {
                $data = simplexml_load_string($response->body()); // Parse XML response
                return response()->json(json_decode(json_encode($data), true)); // Convert XML to JSON response
            } else {
                throw new Exception('Failed to fetch data from Tally');
            }
        } catch (Exception $e) {
            // Handle any errors
            return response()->json(['error' => $e->getMessage()], 500);
        }

   
    }

    function insertSalesData($salesData) {
        $xmlRequest = <<<XML
            <ENVELOPE>
                <HEADER>
                    <TALLYREQUEST>Import Data</TALLYREQUEST>
                </HEADER>
                <BODY>
                    <IMPORTDATA>
                        <REQUESTDESC>
                            <REPORTNAME>All Masters</REPORTNAME>
                        </REQUESTDESC>
                        <REQUESTDATA>
                            <TALLYMESSAGE xmlns:UDF="TallyUDF">
                                <VOUCHER VCHTYPE="Sales" ACTION="Create">
                                    <DATE>{$salesData['date']}</DATE>
                                    <PARTYLEDGERNAME>{$salesData['party_name']}</PARTYLEDGERNAME>
                                    <AMOUNT>{$salesData['amount']}</AMOUNT>
                                    <!-- Additional fields as required -->
                                </VOUCHER>
                            </TALLYMESSAGE>
                        </REQUESTDATA>
                    </IMPORTDATA>
                </BODY>
            </ENVELOPE>
        XML;
    
        $response = Http::withBody($xmlRequest, 'application/xml')
                        ->post(env('TALLY_SERVER_URL'));
    
        if ($response->successful()) {
            return 'Data inserted successfully';
        } else {
            throw new \Exception('Failed to insert data into Tally');
        }
    }


    public function fetchSales() {
        try {
            $salesData = fetchSalesData();
            return response()->json($salesData);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function insertSales(Request $request) {
        try {
            $response = insertSalesData($request->all());
            return response()->json(['message' => $response]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
