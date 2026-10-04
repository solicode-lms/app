@extends('layouts.public')
@section('title', "SoliLMS - L'apprentissage réinventé")

@section('content')
  <!-- Hero Section -->
  <section class="relative bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-800 text-white overflow-hidden">
    <!-- Éléments décoratifs d'arrière-plan -->
    <div class="absolute inset-0 bg-opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    <div class="absolute -top-32 -right-32 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>
    <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>

    <div class="relative container mx-auto px-6 py-24 md:py-32 text-center z-10">
      <span class="inline-block py-1.5 px-4 rounded-full bg-blue-500/30 text-blue-100 text-sm font-semibold tracking-wider mb-6 border border-blue-400/30 backdrop-blur-sm shadow-sm">
        NOUVELLE PLATEFORME
      </span>
      <h2 class="text-5xl md:text-6xl font-extrabold mb-6 tracking-tight leading-tight">
        Bienvenue sur <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-cyan-200">SoliLMS</span>
      </h2>
      <p class="text-lg md:text-xl text-blue-100 max-w-3xl mx-auto mb-10 leading-relaxed font-light">
        La plateforme innovante de gestion de l'apprentissage en ligne, conçue pour simplifier l'enseignement et offrir des expériences d'apprentissage hautement personnalisées. Découvrez comment nous transformons l'éducation.
      </p>
      <div class="flex flex-col sm:flex-row justify-center gap-4">
        <a href="/admin/" class="px-8 py-4 bg-white text-blue-700 font-bold rounded-xl shadow-lg hover:bg-blue-50 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
          Accéder à mon espace
        </a>
      </div>
    </div>
  </section>

  <!-- Section Fonctionnalités -->
  <main class="container mx-auto px-6 py-20">
    <div class="text-center mb-16">
      <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">L'excellence Pédagogique</h2>
      <p class="text-gray-500 max-w-2xl mx-auto text-lg">Des outils puissants conçus sur-mesure pour les formateurs et les apprenants afin d'optimiser chaque étape du parcours éducatif.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
      
      <!-- Carte 1 -->
      <div class="group bg-white shadow-md hover:shadow-2xl rounded-2xl overflow-hidden transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 flex flex-col">
        <div class="relative overflow-hidden h-56">
          <img src="{{ asset('images/public/bloc1.webp') }}" alt="Pédagogie Active" class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110">
          <div class="absolute inset-0 bg-gradient-to-t from-gray-900/70 via-gray-900/20 to-transparent"></div>
          <div class="absolute bottom-4 left-6">
            <span class="px-3 py-1 bg-blue-500 text-white text-xs font-bold rounded-md shadow-sm">Pédagogie</span>
          </div>
        </div>
        <div class="p-8 flex flex-col flex-grow">
          <h3 class="text-2xl font-bold text-gray-800 mb-3 group-hover:text-blue-600 transition-colors">Suivi Individualisé</h3>
          <p class="text-gray-600 leading-relaxed mb-6 flex-grow">SoliLMS guide les formateurs dans l'application des méthodes de pédagogie active. Détectez les difficultés et proposez des solutions sur-mesure pour chaque apprenant.</p>
          <a href="/admin/" class="inline-flex items-center text-blue-600 font-semibold hover:text-blue-800 transition-colors mt-auto">
            Explorer
            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
          </a>
        </div>
      </div>
      
       <!-- Carte 2 -->
      <div class="group bg-white shadow-md hover:shadow-2xl rounded-2xl overflow-hidden transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 flex flex-col">
        <div class="relative overflow-hidden h-56">
          <img src="{{ asset('images/public/bloc2.webp') }}" alt="Gestion de Projets" class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110">
          <div class="absolute inset-0 bg-gradient-to-t from-gray-900/70 via-gray-900/20 to-transparent"></div>
          <div class="absolute bottom-4 left-6">
            <span class="px-3 py-1 bg-indigo-500 text-white text-xs font-bold rounded-md shadow-sm">Projets</span>
          </div>
        </div>
        <div class="p-8 flex flex-col flex-grow">
          <h3 class="text-2xl font-bold text-gray-800 mb-3 group-hover:text-indigo-600 transition-colors">Validation et Gestion</h3>
          <p class="text-gray-600 leading-relaxed mb-6 flex-grow">Générez automatiquement des briefs de projets, assurez leur validation par les formateurs et centralisez les évaluations pour une gestion pédagogique optimale.</p>
          <a href="/admin/" class="inline-flex items-center text-indigo-600 font-semibold hover:text-indigo-800 transition-colors mt-auto">
            Explorer
            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
          </a>
        </div>
      </div>
       
      <!-- Carte 3 -->
      <div class="group bg-white shadow-md hover:shadow-2xl rounded-2xl overflow-hidden transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 flex flex-col">
        <div class="relative overflow-hidden h-56">
          <img src="{{ asset('images/public/bloc3.webp') }}" alt="Notes et Appréciations" class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110">
          <div class="absolute inset-0 bg-gradient-to-t from-gray-900/70 via-gray-900/20 to-transparent"></div>
          <div class="absolute bottom-4 left-6">
            <span class="px-3 py-1 bg-cyan-500 text-white text-xs font-bold rounded-md shadow-sm">Évaluations</span>
          </div>
        </div>
        <div class="p-8 flex flex-col flex-grow">
          <h3 class="text-2xl font-bold text-gray-800 mb-3 group-hover:text-cyan-600 transition-colors">Appréciations Précises</h3>
          <p class="text-gray-600 leading-relaxed mb-6 flex-grow">Chaque formation dispose d'un système d'appréciation précis. Les notes des modules et projets sont gérées efficacement avec une traçabilité totale.</p>
          <a href="/admin/" class="inline-flex items-center text-cyan-600 font-semibold hover:text-cyan-800 transition-colors mt-auto">
            Explorer
            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
          </a>
        </div>
      </div>
      
    </div>
  </main>
@endsection