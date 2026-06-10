@extends('layouts.app')

@php
use Illuminate\Support\Str;

$contenu = $lecon->contenu;
$isRemote = Str::startsWith($contenu, ['http://', 'https://']);
$videoEmbedUrl = null;
$isRemoteVideoFile = $isRemote && Str::endsWith($contenu, ['.mp4', '.webm', '.ogg']);
if ($lecon->type === 'video' && $isRemote) {
    if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|v\/))([^&?\/]+)/', $contenu, $matches)) {
        $videoEmbedUrl = 'https://www.youtube.com/embed/' . $matches[1];
    }
}
@endphp

@section('title', $lecon->titre)

@section('content')
<div class="card">
    <div class="card-header">
        <h5>{{ $lecon->titre }}</h5>
        <p class="mb-0 text-muted">Type : <strong>{{ ucfirst($lecon->type) }}</strong> • Leçon {{ $lecon->ordre }}</p>
    </div>
    <div class="card-body">
        @if($lecon->type === 'texte')
            <div class="mb-4">
                {!! nl2br(e($lecon->contenu)) !!}
            </div>
        @elseif($lecon->type === 'video')
            @if($isRemote && $videoEmbedUrl)
                <div class="ratio ratio-16x9 mb-4">
                    <iframe src="{{ $videoEmbedUrl }}" allowfullscreen></iframe>
                </div>
            @elseif($isRemote)
                <video controls class="w-100 mb-4">
                    <source src="{{ $contenu }}" type="video/mp4">
                    Votre navigateur ne supporte pas la vidéo.
                </video>
            @else
                <video controls class="w-100 mb-4">
                    <source src="{{ asset('storage/' . $lecon->contenu) }}" type="video/mp4">
                    Votre navigateur ne supporte pas la vidéo.
                </video>
            @endif
        @elseif($lecon->type === 'pdf')
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="badge bg-danger fs-6"><i class="bi bi-file-earmark-pdf"></i> Document PDF</span>
                    <a href="{{ asset('storage/' . $lecon->contenu) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-download"></i> Télécharger le PDF
                    </a>
                </div>
                <iframe src="{{ asset('storage/' . $lecon->contenu) }}" 
                        style="width: 100%; height: 70vh; border: 1px solid #ddd; border-radius: 8px;" 
                        title="{{ $lecon->titre }}">
                    <p>Votre navigateur ne supporte pas l'affichage PDF. 
                       <a href="{{ asset('storage/' . $lecon->contenu) }}">Téléchargez le PDF</a>.</p>
                </iframe>
            </div>
        @else
            <div class="mb-4">
                {!! nl2br(e($lecon->contenu)) !!}
            </div>
        @endif

        <form method="POST" action="{{ url('etudiant/lecon/' . $lecon->id . '/complete') }}">
            @csrf
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check2-circle"></i> Marquer comme terminé
            </button>
        </form>

        <div class="mt-4">
            <a href="{{ route('etudiant.cours.show', $lecon->cours) }}" class="btn btn-outline-secondary">
                <i class="bi bi-list-ul"></i> Retour au cours
            </a>
        </div>
    </div>
</div>
@endsection