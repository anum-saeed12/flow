<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>{{ $inquiry->creation }}</title>
    <style type="text/css">
        * {
            font-family:  Verdana, Arial, Helvetica, sans-serif;
        }
        table {
            font-size: small;
        }
        tfoot tr td {
            font-weight: bold;
            font-size: small;
        }
        hr.hr1 {
            border: 15px solid #eae8e4;
        }
    </style>
</head>
<hr class="hr1" />
<body>
<table width="100%">
    <tr>
        <td align="center">
            <h1><b>Inquiry Invoice</b></h1>
        </td>
    </tr>
</table>

<table width="100%">
    <tr>
        <td>
            <strong>From:</strong>
            <address>
            </address>
        </td>
        <td><strong>To:</strong>
            <address>
            </address>
        </td>
        <td>
            <strong>Date #<b>{{ $inquiry->creation }}</b></strong>
        </td>
    </tr>
</table>

<br />

<table width="100%" style="text-align: left">
    <thead style="background-color: #eae8e4;">
    <tr>
        <th>Sr.no</th>
        <th>Item Description</th>
        <th>Brand</th>
        <th>Quantity</th>
        <th>Unit</th>
        <th>Unit Price</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($inquiry as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ ucwords($item->item_description) }}</td>
            <td>{{ ucwords($item->brand_name) }}</td>
            <td>{{ ucwords($item->quantity) }}</td>
            <td>{{ ucwords($item->unit) }}</td>
            <td>{{ ucwords($item->rate) }}</td>
            <td>{{ ucwords($item->amount) }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>

    <tr>
        <td colspan="3"></td>
        <th align="right">Total:</th>
        <td align="right">{{ $inquiry[0]->currency }}{{number_format($inquiry[0]->total )}}</td>
    </tr>
    </tfoot>
</table>
<br><br><br>
<hr class="hr1" />
</body>
</html>
<script>
    function number_format(num)
    {
        var num_parts = num.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return num_parts.join(".");
    }
</script>
