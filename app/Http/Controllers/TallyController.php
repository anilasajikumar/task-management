<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Exception;

class TallyController extends Controller
{
    public function showForm()
    {
        return view('tally_form');
    }

    // Insert entry into Tally
    public function insertEntry(Request $request)
    { 
        $name = $request->input('nm'); // Get ledger name from the form input

        $requestXML = '<?xml version="1.0"?>
        <ENVELOPE>
          <HEADER>
            <TALLYREQUEST>Import Data</TALLYREQUEST>
          </HEADER>
          <BODY>
            <IMPORTDATA>
              <REQUESTDESC>
                <REPORTNAME>Vouchers</REPORTNAME>
                <STATICVARIABLES>
                  <SVCURRENTCOMPANY>SAVH</SVCURRENTCOMPANY>
                </STATICVARIABLES>
              </REQUESTDESC>
              <REQUESTDATA>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                  <VOUCHER REMOTEID="123" VCHTYPE="Receipt" VCHKEY="321" ACTION="Create" OBJVIEW="Accounting Voucher View">
                    <PARTYLEDGERNAME>' . $name . '</PARTYLEDGERNAME>
                    <DATE>20241119</DATE>
                    <VOUCHERTYPENAME>Receipt</VOUCHERTYPENAME>
                    <VOUCHERNUMBER>1</VOUCHERNUMBER>
                    <ALLLEDGERENTRIES.LIST>
                      <LEDGERNAME>' . $name . '</LEDGERNAME>
                      <AMOUNT>200000.00</AMOUNT>
                    </ALLLEDGERENTRIES.LIST>
                    <ALLLEDGERENTRIES.LIST>
                      <LEDGERNAME>Bank of Maharashtra</LEDGERNAME>
                      <AMOUNT>-200000.00</AMOUNT>
                    </ALLLEDGERENTRIES.LIST>
                  </VOUCHER>
                </TALLYMESSAGE>
              </REQUESTDATA>
            </IMPORTDATA>
          </BODY>
        </ENVELOPE>';

        $server = 'http://localhost:9000';
        $headers = [
            "Content-type: text/xml",
            "Content-length: " . strlen($requestXML),
            "Connection: close"
        ];

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $server);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 100);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $requestXML);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                throw new Exception(curl_error($ch));
            }

            curl_close($ch);

            return response()->json(['message' => 'Data inserted successfully', 'data' => $response]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Fetch data from Tally
    public function fetchData()
    {
        $requestXML = '<?xml version="1.0"?>
        <ENVELOPE>
          <HEADER>
            <TALLYREQUEST>Export Data</TALLYREQUEST>
          </HEADER>
          <BODY>
            <EXPORTDATA>
              <REQUESTDESC>
                <REPORTNAME>Ledger Vouchers</REPORTNAME>
                <STATICVARIABLES>
                  <SVCURRENTCOMPANY>SAVH</SVCURRENTCOMPANY>
                  <LEDGERNAME>Bank of Maharashtra</LEDGERNAME>
                </STATICVARIABLES>
              </REQUESTDESC>
            </EXPORTDATA>
          </BODY>
        </ENVELOPE>';

        $server = 'http://localhost:9000';
        $headers = [
            "Content-type: text/xml",
            "Content-length: " . strlen($requestXML),
            "Connection: close"
        ];

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $server);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 100);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $requestXML);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                throw new Exception(curl_error($ch));
            }

            curl_close($ch);

            return response()->json(['message' => 'Data fetched successfully', 'data' => $response]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
      
}
