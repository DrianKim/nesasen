<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Nesasen - Learning Management System for SMKN 1 Subang">
    <meta name="author" content="Development Team">
    <meta name="theme-color" content="#4e73df">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Nesasen | {{ $title }}</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}" type="image/x-icon">

    <!-- Custom fonts for this template-->
    <link href="{{ asset('sbadmin2/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('sbadmin2/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- Custom styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <!-- PWA elements -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="M-Kelas">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-152x152.png') }}">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- Css Custom -->
    <link rel="stylesheet" href="{{ asset('assets/css/style-app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style-beranda.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style-presensi.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style-jadwal.css') }}">

    <!-- Dashboard specific styles or scripts -->
    @stack('styles')
    {{-- <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        body {
            display: flex;
            background-color: #f4f7fc;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: linear-gradient(180deg, #3a4db1 0%, #2c3e8f 100%);
            color: #fff;
            position: fixed;
            left: 0;
            top: 0;
            transition: all 0.3s ease;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            z-index: 100;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar-header {
            padding: 20px 15px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            height: 70px;
        }

        .sidebar-header .logo-icon {
            font-size: 24px;
            margin-right: 10px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-header .logo-text {
            font-size: 20px;
            font-weight: 600;
            white-space: nowrap;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed .logo-text {
            display: none;
        }

        .sidebar-menu {
            padding: 10px 0;
        }

        .menu-header {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 15px 15px 5px;
            color: rgba(255, 255, 255, 0.6);
            white-space: nowrap;
        }

        .sidebar.collapsed .menu-header {
            display: none;
        }

        .menu-item {
            padding: 0;
            list-style: none;
        }

        .menu-link {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            position: relative;
            cursor: pointer;
        }

        .menu-link:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: #fff;
            border-left: 3px solid #fff;
        }

        .menu-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
            border-left: 3px solid #36f;
        }

        .menu-icon {
            width: 20px;
            font-size: 16px;
            margin-right: 15px;
            text-align: center;
        }

        .menu-text {
            white-space: nowrap;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed .menu-text {
            display: none;
        }

        .menu-arrow {
            margin-left: auto;
            transition: transform 0.3s ease;
        }

        .sidebar.collapsed .menu-arrow {
            display: none;
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background-color: rgba(0, 0, 0, 0.1);
        }

        .submenu.open {
            max-height: 500px;
        }

        .submenu-item {
            list-style: none;
        }

        .submenu-link {
            display: flex;
            align-items: center;
            padding: 10px 15px 10px 50px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 14px;
        }

        .submenu-link:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        .submenu-icon {
            width: 16px;
            font-size: 12px;
            margin-right: 10px;
        }

        .sidebar-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 15px;
            text-align: center;
            position: sticky;
            bottom: 0;
            background: inherit;
        }

        .sidebar-toggle {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 0 auto;
        }

        .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Badge styles */
        .badge {
            background-color: #ff5a5f;
            color: white;
            border-radius: 10px;
            padding: 2px 8px;
            font-size: 11px;
            margin-left: 10px;
        }

        /* Content area */
        .content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .content.expanded {
            margin-left: 70px;
        }

        /* Media query for responsive design */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }

            .sidebar .logo-text,
            .sidebar .menu-text,
            .sidebar .menu-arrow,
            .sidebar .menu-header {
                display: none;
            }

            .content {
                margin-left: 70px;
            }
        }

        /* Demo Controls */
        .demo-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            z-index: 999;
        }

        .demo-controls button {
            background: #3a4db1;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
        }

        .demo-controls button:hover {
            background: #2c3e8f;
        }
    </style> --}}
</head>
