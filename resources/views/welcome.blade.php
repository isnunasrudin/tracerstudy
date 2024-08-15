<!-- <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survei</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

</head>
<body>
    <form action="" method="POST">    
        @include('survey::standard', ['survey' => $survey])
    </form>
</body>
</html> -->

<x-layout title="Masuk">
    <div class="m-auto pb-4" style="width: 500px; z-index: 999">
        <h1 class="h5 text-center py-4 bg-primary m-0">Tracer Study Berdaya</h1>
        <form class="p-5 pt-4 bg-white text-dark" method="POST" action="" autocomplete="off">
            @if($errors->any())
            <div class="alert alert-info">{{ $errors->first() }}</div>
            @endif
            <div>
                <label class="col-form-label">Nomor Induk Siswa Nasional <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nisn" required value="{{ old('nisn') }}" placeholder="NISN">
            </div>
            @csrf
            <div>
                <label class="col-form-label mt-3">Tanggal Lahir <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="born_date" max="2008-12-31" required value="{{ old('born_date') }}">
            </div>
            <div>
                <label class="col-form-label mt-3">No. WhatsApp <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="phone" required value="{{ old('phone') }}">
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-4">MASUK!</button>
        </form>
        <footer class="text-center mt-2" style="opacity: .22; font-size:15px">2024 - SMKN 1 Pogalan | <a href="//fb.me/SHeSHeOrankZ" class="text-white" target="_blank">L-155-4</a></footer>
    </div>
</x-layout>