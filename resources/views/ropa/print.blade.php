<!DOCTYPE html>
<html>
<head>
    <title>Print ROPA</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 25px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #f0f0f0; }
        h2 { margin-bottom: 10px; }
        .print-btn { margin-bottom: 20px; }
        @media print {
            .print-btn { display: none; }
        }
    </style>
</head>
<body>

<button class="print-btn" onclick="window.print()">Print</button>

<h2>ROPA Record #{{ $ropa->id }}</h2>

<table>
    @foreach ($data as $label => $value)
        <tr>
            <th>{{ ucwords(str_replace('_', ' ', $label)) }}</th>
            <td>{{ $value }}</td>
        </tr>
    @endforeach
</table>

</body>
</html>
