@extends('layouts.app')

@section('title', 'Головна')




@section('content')

<livewire:dependent-dropdown />

<div class="page__container">
    <div class="page__wrapper">
        <div class="page__video video-section">
            <div class="video-section__block _block">
                <div class="video-section__video">
                    <iframe src="https://www.youtube.com/embed/fDnSE-hbivg" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen=""></iframe>
                </div>
                <h1 class="video-section__title section-title section-title--margin">
                    ДЕТАЛЬНА ІНФОРМАЦІЯ ПРО ТУРНІР
                </h1>
            </div>
        </div>
    </div>
</div>
@endsection	
   