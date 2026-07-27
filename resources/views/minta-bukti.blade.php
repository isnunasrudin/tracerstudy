<x-layout title="Isi Survei">
    <div class="text-dark m-auto pb-4" style="background-color: rgba(255, 255, 255, 0.9);-webkit-backdrop-filter: blur(5px);backdrop-filter: blur(5px);width: 500px; z-index: 999">
        <div class="text-center flex p-4 bg-success m-0 text-white">
            <h5>Terima Kasih, <b>{{ $student->name }}</b></h5>
            <p class="mb-0">{{ $survey->name }} Anda telah tersimpan.</p>
        </div>

        <div class="px-5 pt-3">
            <p class="mb-0">Selanjutkan Anda bisa download bukti pengisian melalui tombol dibawah ini:</p>

            <a download="bukti-tracer.jpg" target="_blank" href="{{ $link }}" class="btn btn-success w-100 mt-4" title="Bukti Tracer">DOWNLOAD</a>

            <!-- <b class="w-100 d-block mt-1 mb-3 text-danger" style="font-size: 10px;">Mohon tidak SPAM pesan</b> -->
        </div>
        <footer class="text-center mt-2" style="opacity: .2; font-size:15px">2024 - SMKN 1 Pogalan | <a href="//fb.me/SHeSHeOrankZ" class="text-dark" target="_blank">L</a></footer>
    </div>
</x-layout>