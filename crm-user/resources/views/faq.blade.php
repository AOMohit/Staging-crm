@extends('layouts.userlayout.index')
@section('container')

<div class="col-md-10 col-12">
    <div class="border-shadow">
        <div class="card">
            <div class="card-header bg-white information">
                @include('layouts.userlayout.card-header')
            </div>
        </div>
      
         <div class="card-body faq-section">
        
                <h6 class="text fw-bold">FAQ’s</h6>
            
            <div class="accordion" id="faqAccordion">
                @foreach($data as $index => $faq)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{ $index }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}">
                            {{ $faq->question }}
                        </button>
                    </h2>
                    <div id="collapse{{ $index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            {!! nl2br(e($faq->answer)) !!}
                        </div>
                    </div>
                </div>
                @endforeach
               
            </div>
        
        <hr>
        <div class="faq-imp">
            @include('imp')
        </div>
    </div>
</div>
</div>
@endsection