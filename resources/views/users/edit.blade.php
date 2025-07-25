<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User: {{ $user->name }}</title>
    {{-- <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f7f6;
            color: #333;
        }
        .container {
            max-width: 700px;
            margin: 20px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.2em;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }
        input[type="text"],
        textarea {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus,
        textarea:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        textarea {
            resize: vertical;
            min-height: 80px;
        }
        button {
            background-color: #3498db;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: auto;
        }
        button:hover {
            background-color: #2980b9;
        }
        .error-message-list {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .error {
            color: #e74c3c;
            font-size: 0.9em;
            margin-top: 5px;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        .back-link:hover {
            color: #2980b9;
            text-decoration: underline;
        }
    </style> --}}
</head>
<body>
@extends('layouts.app') @section('title', 'Edit Pengguna: ' . $user->name) @section('content')
    <div class="container">
        <h1>Edit Pengguna: {{ $user->name }}</h1>

        @if ($errors->any())
            <ul class="error-message-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nama:</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
                @error('email') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="password">Password (kosongkan jika tidak ingin mengubah):</label>
                <input type="password" name="password" id="password">
                @error('password') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password:</label>
                <input type="password" name="password_confirmation" id="password_confirmation">
            </div>
            <div class="form-group">
                <label for="role_id">Role:</label>
                <select name="role_id" id="role_id" required>
                    <option value="">-- Pilih Role --</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role_id') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="branch_office_id">Kantor Cabang:</label>
                <select name="branch_office_id" id="branch_office_id">
                    <option value="">-- Pilih Cabang (Opsional) --</option>
                    @foreach ($branchOffices as $branchOffice)
                        <option value="{{ $branchOffice->id }}" {{ old('branch_office_id', $user->branch_office_id) == $branchOffice->id ? 'selected' : '' }}>
                            {{ $branchOffice->name }}
                        </option>
                    @endforeach
                </select>
                @error('branch_office_id') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="phone_number">Nomor Telepon:</label>
                <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
                @error('phone_number') <div class="error">{{ $message }}</div> @enderror
            </div>
            <button type="submit">Update Pengguna</button>
        </form>
        <a href="{{ route('users.index') }}" class="back-link">Kembali ke Daftar Pengguna</a>
    </div>
@endsection
</body>
</html>