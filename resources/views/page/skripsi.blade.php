@extends('layout.master')
{{-- @section('page_title', 'Skripsi') --}}

@section('content')
<!-- <small>{{ $a ?? '' }}</small> -->
<form action="{{ route('skripsi.gateway') }}" method="post">
    @csrf
    <div class="wrapper-skripsi">
        <div class="form-group form-input">
            <label for="nim" class="nim">NIM</label>
            <input type="text" placeholder="Masukkan NIM Anda" name="nim" class="input-form" id="nim" value="{{ $formdata->nim ?? ''}}">
            <label for="nama" class="nama">Nama</label>
            <input type="text" placeholder="Masukkan Nama Anda" name="nama" id="nama" class="input-form" value="{{ $formdata->nama ?? ''}}">
        </div>
        <div class="form-group form-judul">
            <label for="judulskripsi" class="judul">Judul</label>
            <input type="text" name="judul" placeholder="Masukkan Judul Anda" class="input-form judul" id="judul" value="{{ $formdata->judul ?? ''}}">
        </div>
        <div class="form-group form-btn">
            <!-- <input type="submit" value="Cek Judul" name="judul-masuk" id="btn-input"> -->
            <button type="submit" name="action" value="check">Cek Judul</button>
            <button type="submit" name="action" value="index">Reset Form</button>
            @if(count($datas) == 0)
                <button type="submit" name="action" value="insert">Insert</button>
            @endif
        </div>
    </div>
</form>

<table class="tableskripsi">
    <thead>
        <tr>
            {{-- <th style="height: 28px; width: 38px;">No</th> --}}
            <th>NIM</th>
            <th>Nama</th>
            <th>Judul Skripsi</th>
            <th style="height: 28px; width: 38px;"> % </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datas as $data)
        <tr>
            <td>{{ $data->nim }}</td>
            <td>{{ $data->nama }}</td>
            <td>{{ $data->judulskripsi }}</td>
            <td>{{ $data->similarity }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection