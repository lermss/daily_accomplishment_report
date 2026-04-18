<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>
/* ================== PAGE SETUP ================== */
@page {
    size: A4;
    margin: 300px 50px 100px 50px;
}


/* ================== BODY ================== */
body {
    font-family: "Times New Roman", serif;
    font-size: 12pt;
    margin: 0;
    padding: 0;
}

/* ================== HEADER ================== */
.header {
    position: fixed;
    top: -250px;
    left: 0;
    right: 0;
    text-align: center;
}

.header img {
     max-height: 150px;
    margin-bottom: 5px;
    margin-top: auto;
}

.header h3 {
    margin: 3px 0;
    font-weight: bold;
}

.header p {
    margin: 0;
    font-size: 12pt;
}

/* ================== FOOTER ================== */
.footer {
    position: fixed;
    bottom: -70px;
    left: 0;
    right: 0;
    text-align: center;
    font-size: 10pt;
}

/* ================== CONTENT ================== */
.content {
    width: 100%;
}

/* ================== TABLE ================== */
table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

th, td {
    border: 1px solid black;
    padding: 6px;
    vertical-align: top;
    word-wrap: break-word;
}

th {
    background: #f0f0f0;
    text-align: center;
}

/* ❗ IMPORTANT FIX: allow row to break naturally */
tr {
    page-break-inside: auto;
}

td {
    line-height: 1.4;
}

/* ================== SIGNATURE ================== */
.signature-section {
    margin-top: 40px;
    page-break-inside: avoid;
}

.signature-section table {
    border: none;
}

.signature-section td {
    border: none;
    text-align: center;
    padding-top: 60px;
    font-weight: bold;
}
</style>

</head>

<body>

<!-- ================== HEADER ================== -->
<div class="header">
    <img src="{{ public_path('images/HEADER.png') }}">
    <h3>DAILY ACCOMPLISHMENT REPORT</h3>
    <p>{{ $report->file_name }}</p>
</div>

<!-- ================== FOOTER ================== -->
<div class="footer">
    <p>Generated on {{ \Carbon\Carbon::now()->format('F d, Y') }}</p>
    copyright &copy; {{ date('Y') }} DICT. All rights reserved.
</div>

<!-- ================== CONTENT ================== -->
<div class="content">

    <table>
        <thead>
            <tr>
                <th width="13%">Date</th>
                <th width="20%">Activity / Task</th>
                <th>Description</th>
                <th width="20%">Remarks</th>
            </tr>
        </thead>

        <tbody>
            @foreach($report->entries as $entry)
            <tr>
                <td>
                    {{ \Carbon\Carbon::parse($entry->start_date)->format('M d, Y') }} <br>
                    {{ \Carbon\Carbon::parse($entry->end_date)->format('M d, Y') }}
                </td>
                <td>{{ $entry->activity }}</td>
                <td>{{ $entry->details }}</td>
                <td>{{ $entry->remarks }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @php
        $staffSignaturePath = $report->user?->signature_path
            ? storage_path('app/public/' . ltrim($report->user->signature_path, '/'))
            : null;

        $provincialHeadSignaturePath = $report->assignedProvincialHead?->signature_path
            ? storage_path('app/public/' . ltrim($report->assignedProvincialHead->signature_path, '/'))
            : null;
    @endphp

    <!-- ================== SIGNATURE ================== -->
    <div class="signature-section">
        <table>
            <tr>
                <td>
                    Prepared By:<br><br>

                    @if($staffSignaturePath && file_exists($staffSignaturePath))
                        <img src="data:{{ mime_content_type($staffSignaturePath) }};base64,{{ base64_encode(file_get_contents($staffSignaturePath)) }}" alt="Signature" style="max-height: 100px;" >
                    @else
                    <br>

                     _________________________<br>
                   
                    @endif
                    {{ $report->user->name ?? 'Staff Name' }}
                </td>
                <td>
                    Approved By:<br><br>

                    @if($report->status === 'approved' && $provincialHeadSignaturePath && file_exists($provincialHeadSignaturePath))
                        <img src="data:{{ mime_content_type($provincialHeadSignaturePath) }};base64,{{ base64_encode(file_get_contents($provincialHeadSignaturePath)) }}" alt="Signature" style="max-height: 100px;">
                    @else
                        _________________________<br>
                    @endif
                    {{ $report->status === 'approved' ? ($report->assignedProvincialHead->name ?? 'Provincial Head') : 'Pending Approval' }}

                </td>
            </tr>
        </table>
    </div>

</html>