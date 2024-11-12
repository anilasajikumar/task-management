<!DOCTYPE html>
<html>
<head>
    <title>Sales Data</title>
</head>
<body>

<h1>Sales Data from Tally</h1>

<table id="salesTable" border="1">
    <thead>
        <tr>
            <th>Date</th>
            <th>Voucher Type</th>
            <th>Amount</th>
            <!-- Add more columns based on the data structure -->
        </tr>
    </thead>
    <tbody>
        <!-- Rows will be inserted here by JavaScript -->
    </tbody>
</table>

<script>
// JavaScript code to fetch data from Laravel endpoint
fetch('/tally/fetch-sales')
    .then(response => response.json())
    .then(data => {
        // Process and display the data in the table
        const salesTable = document.getElementById('salesTable').getElementsByTagName('tbody')[0];
        
        data.VOUCHER?.forEach(voucher => {
            const row = salesTable.insertRow();
            row.insertCell(0).innerText = voucher.DATE;
            row.insertCell(1).innerText = voucher.VOUCHERTYPENAME;
            row.insertCell(2).innerText = voucher.AMOUNT;
            // Add more cells based on data structure
        });
    })
    .catch(error => console.error('Error:', error));
</script>

</body>
</html>
