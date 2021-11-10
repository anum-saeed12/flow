<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>{{ $quotation->creation }}</title>
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
            <h1><b>Build Con</b></h1>
            <h2><b>Vendor Quotation Invoice</b></h2>
        </td>
    </tr>
</table>

<table width="100%">
    <tr>
        <td>
            <address>
                <p><b>Ref: </b>{{ strtoupper(substr($quotation[0]->inquiry,0,4)) }}-{{ strtoupper(substr($quotation[0]->inquiry,4,4)) }}-{{ \Carbon\Carbon::createFromTimeStamp(strtotime($quotation[0]->created_at))->format('dm') }}-{{ \Carbon\Carbon::createFromTimeStamp(strtotime($quotation[0]->created_at))->format('Y') }}</p>
                <p><b>Attention: </b>{{ ucwords($quotation[0]->attention_person) }}</p>
                <p><b>Customer Name: </b>{{ ucwords($quotation[0]->customer_name) }}</p>
                <p><b>Project Name: </b>{{ ucwords($quotation[0]->project_name) }}</p>
            </address>
        </td>
        <td><p style="color:white"> hello</p></td>
        <td colspan="4" align="top"><strong>Date #{{ $quotation->creation }}</strong></td>
    </tr>
</table>

<br />

<table width="100%" style="text-align: left">
    <thead style="background-color: #eae8e4;">
    <tr>
        <th>Sr.no</th>
        <th>Item Name</th>
        <th>Item Description</th>
        <th>Brand</th>
        <th>Quantity</th>
        <th>Unit</th>
        <th>Unit Price ( {{ ucwords($quotation[0]->currency) }} )</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($quotation as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ ucwords($item->item_name) }}</td>
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
        <td  colspan="3"></td>
        <th align="right">Total:</th>
        <td align="right">{{ $quotation[0]->currency }}{{number_format($quotation[0]->totals )}}</td>
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