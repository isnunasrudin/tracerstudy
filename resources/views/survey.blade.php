<x-layout title="Isi Survei">
    <div class="text-dark bg-white m-auto pb-4" style="width: 500px; z-index: 999">
        <h1 class="h5 text-center py-4 bg-primary m-0 text-white">{{ $survey->name }} | SMKN 1 POGALAN</h1>
        <form method="POST" action="" autocomplete="off" x-data="{SaatIni: []}">
            @csrf
            <div class="py-4 px-5">
                @if($errors->any())
                <div class="alert alert-info">{{ $errors->first() }}</div>
                @endif
                <div>
                    <label class="col-form-label">{{ $primary_option->content }} <span class="text-danger">*</span></label>
                    @foreach ($primary_option->options as $option)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="{{$option}}" name="q{{$primary_option->id}}[]" id="input-{{$option}}" x-model="SaatIni">
                        <label class="form-check-label" for="input-{{$option}}">
                            {{ $option }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
        
           @foreach ($survey->questions()->whereNot('id', 1)->whereHas('section')->with('section')->get()->groupBy('section.name') as $section => $questions)
           <div x-show="SaatIni.includes('{{$section}}')">
            <h1 class="h5 py-3 px-3 bg-primary mt-4 m-0 text-white">Kegiatan: <b>{{ $section }}</b></h1>
            <div class="px-5">
            @foreach ($questions as $question)
                    <div>
                        <label class="col-form-label">{{ $question->content }} <span class="text-danger">*</span></label>
                        @if($question->type == 'text')
                        <input type="text" class="form-control" name="q{{$question->id}}">

                        @elseif($question->type == 'multiselect')
                        <select class="form-select" name="q{{$question->id}}">
                            @foreach ($question->options as $option)
                            <option value="{{$option}}">{{$option}}</option>
                            @endforeach
                        </select>

                        @elseif($question->type == 'multiselect')
                            @foreach ($question->options as $option)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{$option}}" name="q{{$question->id}}" id="input-{{$option}}">
                                <label class="form-check-label" for="input-{{$option}}">
                                    {{ $option }}
                                </label>
                            </div>
                            @endforeach

                        @elseif($question->type == 'radio')
                        @foreach ($question->options as $option)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="{{$option}}" name="q{{$question->id}}">
                            <label class="form-check-label">
                                {{ $option }}
                            </label>
                        </div>
                        @endforeach

                        @endif
                    </div>
            @endforeach
            </div>
           </div>
           @endforeach
   
            <div class="px-5">        
                <button type="submit" class="btn btn-primary w-100 mt-4">SIMPAN !</button>
            </div>

        </form>
        <footer class="text-center mt-2" style="opacity: .52; font-size:15px">2024 - SMKN 1 Pogalan | <a href="//fb.me/SHeSHeOrankZ" class="text-dark" target="_blank">L-155-4</a></footer>
    </div>
</x-layout>