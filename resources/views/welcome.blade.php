<x-layout title="Masuk">
    <div class="m-auto pb-4" style="width: 500px; z-index: 999">
        <h1 class="h5 text-center py-4 bg-primary m-0">Tracer Study Berdaya</h1>
        <form class="p-5 pt-4 text-dark" style="background-color: rgba(255, 255, 255, 0.9);-webkit-backdrop-filter: blur(5px);backdrop-filter: blur(5px);" method="POST" action="" autocomplete="off">
            @if($errors->any())
            <div class="alert alert-info">{{ $errors->first() }}</div>
            @elseif(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div>
                <label class="col-form-label">NISN <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nisn" required value="{{ old('nisn') }}" placeholder="Nomor Induk Siswa Nasional">
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