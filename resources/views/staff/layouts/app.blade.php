<!DOCTYPE html>
<html>
<head>

<title>Daily Accomplishment Report</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/shared-navbar.css') }}?v={{ filemtime(public_path('css/shared-navbar.css')) }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>

    /* ACTION ICONS */
.report-actions button{
border:none;
background:#f8f9fa;
width:35px;
height:35px;
border-radius:8px;
font-size:16px;
margin-left:6px;
display:flex;
align-items:center;
justify-content:center;
transition:0.2s;
}

.report-actions button:hover{
transform:scale(1.1);
}

/* EDIT */
.edit-btn{
color:#198754;
}

.edit-btn:hover{
background:#e8f5e9;
}

/* DELETE */
.delete-btn{
color:#dc3545;
}

.delete-btn:hover{
background:#fdecea;
}

.staff-notification-item{
border:1px solid #313335;
border-radius:12px;
padding:5px px;
background:rgb(190, 186, 186);
}

.staff-notification-item + .staff-notification-item{
margin-top:10px;
}

.staff-notification-status{
font-size:12px;
font-weight:600;
}

.staff-notification-status.approved{
color:#198754;
}

.staff-notification-status.for_revision{
color:#fd7e14;
}

.staff-notification-item-link:hover .staff-notification-item{
background:rgba(190, 186, 186, 0.8);
border-color:#dee2e6;

}

.staff-notification-item-link{
display:block;
transition:0.2s;
}

</style>

</head>

<body>

@include('partials.navbar-staff')

<!-- PAGE CONTENT -->

<div class="container mt-4">

@yield('content')

</div>

</body>
</html>
