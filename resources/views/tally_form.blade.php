<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tally Integration</title>
</head>
<body>
    <h1>Insert Data into Tally</h1>
    <form action="{{ route('tally.insert') }}" method="POST">
        @csrf
        <label for="nm">Ledger Name:</label>
        <input type="text" id="nm" name="nm" required>
        <button type="submit">Submit</button>
    </form>

    <h1>Fetch Data from Tally</h1>
    <form action="{{ route('tally.fetch') }}" method="GET">
        <button type="submit">Fetch Data</button>
    </form>
</body>
</html>
