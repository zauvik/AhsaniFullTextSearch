@extends('layout.master')
@section('page_title', 'Profile')

@section('content')
    <div class="wrapper-profile">
        <div class="profile-tab">
            <p>
                <div class="one">
                    NIM : 16110420
                </div>
                <div class="two">
                    Nama : Ahsani Afif Muhammad Zaen
                </div>
            </p>
            {{-- <table>
                <td>NIM</td><td>16.11.0420</td>
                <td>Nama</td><td>Ahsani Afif Muhammad Zae</td>

            </table> --}}
        </div>
        <table class="crudtable">
            <thead>
                <th>Judul Skripsi</th>
                <th>Status</th>
            </thead>
            <tbody>
                {{-- @foreach() --}}
                    {{-- ambil dari database --}}
                <td></td> {{-- nama --}}
                <td></td> {{-- Judul Skripsi --}}
                <td>
                    <a href="">Update</a>
                    <a href="">Delete</a>
                </td> {{-- CRUD --}}
                {{-- @endforeach --}}
            </tbody>
        </table>
    </div>
@endsection
