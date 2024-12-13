<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Aplikacja do prowadzenia działalności nierejestrowanej - Mój Mały Biznes</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

  <!-- Styles / Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="p-4 font-sans antialiased text-gray-700 bg-gray-200 sm:p-8 lg:p-16">
  <div class="max-w-screen-xl py-12 mx-auto bg-gray-100 shadow-xl shadow-gray-300/10 rounded-xl">
    <nav class="relative flex gap-4 py-8">
      <div class="flex justify-center flex-1">
        <div class="pointer-events-auto">
          <ul class="flex px-3 text-sm font-medium text-gray-800 rounded-full shadow-lg bg-white/90 shadow-gray-800/5 ring-1 ring-gray-900/5 backdrop-blur">
            <li><a class="relative block px-3 py-2 transition hover:text-emerald-500" href="/">Strona główna</a></li>
            <li><a class="relative block px-3 py-2 transition hover:text-emerald-500" href="{{ route('filament.app.auth.login') }}">Zaloguj się</a></li>
          </ul>
        </div>
      </div>
    </nav>
    <main class="relative grid p-4 sm:p-8 lg:p-16 align-center">
      <section class="max-w-screen-sm mx-auto mb-32 text-center" id="hero">
        <div class="inline-flex gap-2 px-3 py-1.5 rounded-xl mb-6 text-xs font-bold uppercase bg-amber-100 text-amber-600">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
            <path fill-rule="evenodd"
              d="M15 8A7 7 0 1 1 1 8a7 7 0 0 1 14 0ZM9 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM6.75 8a.75.75 0 0 0 0 1.5h.75v1.75a.75.75 0 0 0 1.5 0v-2.5A.75.75 0 0 0 8.25 8h-1.5Z"
              clip-rule="evenodd" />
          </svg>
          <span>Wersja rozwojowa</span>
        </div>
        <h1 class="mb-6 text-3xl font-bold leading-none tracking-tight text-gray-900 lg:text-5xl">
          Uporządkuj swoją działalność nierejestrowaną w kilka minut.
        </h1>
        <p class="mb-8 text-gray-500 lg:text-xl">
          Proste narzędzie stworzone z myślą o nierejestrowanych przedsiębiorcach – wszystkie funkcje, których potrzebujesz, w jednym miejscu. Łatwo, szybko i bez opłat.
        </p>
        <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">
          <a href="{{ route('filament.app.auth.register') }}"
            class="inline-flex items-center justify-center px-5 py-3 text-base font-medium text-center text-white transition-all rounded-lg bg-emerald-600 hover:bg-emerald-700 bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 hover:scale-105 hover:rotate-1 hover:shadow-xl hover:shadow-emerald-500/10">
            Załóż darmowe konto
            <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                clip-rule="evenodd">
              </path>
            </svg>
          </a>
        </div>
      </section>
      <section class="py-8" id="dlaczego-warto">
        <h3 class="mb-6 text-2xl font-bold leading-none tracking-tight text-gray-900 lg:text-3xl">
          Dlaczego warto?
        </h3>
        <ul class="mb-8 ml-5 space-y-2 list-disc lg:text-lg">
          <li>
            <strong>Bez zbędnych formalności</strong>: Wszystkie niezbędne procesy staną się proste i intuicyjne. Nie musisz już martwić się o skomplikowaną dokumentację – przepisy
            ciągle się zmieniają, a my trzymamy rękę na pulsie.
          </li>
          <li>
            <strong>Oszczędność czasu</strong>: Automatyzacja kluczowych zadań, takich jak generowanie faktur czy monitorowanie przychodów, pozwala Ci skoncentrować się na tym, co
            naprawdę ważne – rozwijaniu Twojego biznesu.
          </li>
        </ul>
        <h3 class="mb-6 text-2xl font-bold leading-none tracking-tight text-gray-900 lg:text-3xl">
          Zapomnij o prowadzeniu skomplikowanych arkuszy kalkulacyjnych.
        </h3>
        <p class="text-lg">
          Zbierz swoje dane w usystematyzowaną i sprawdzoną strukturę, zapewniając sobie przejrzystość i porządek.
        </p>
      </section>
      <section class="py-8" id="najwazniejsze-funkcje">
        <div class="grid gap-8 lg:grid-cols-3">
          <dl
            class="flex flex-col gap-2 px-4 py-6 transition-all border-2 cursor-default hover:scale-105 rounded-xl border-gray-600/20 hover:border-emerald-600/50 hover:shadow-xl hover:shadow-emerald-600/10">
            <dt class="text-xl font-bold">Monitorowanie limitów</dt>
            <dd>Twoje przychody są automatycznie monitorowane, informując o zbliżaniu się do określonych progów.</dd>
          </dl>
          <dl
            class="flex flex-col gap-2 px-4 py-6 transition-all border-2 cursor-default hover:scale-105 rounded-xl border-gray-600/20 hover:border-emerald-600/50 hover:shadow-xl hover:shadow-emerald-600/10">
            <dt class="text-xl font-bold">Generowanie faktur</dt>
            <dd>W kilku kliknięciach wygenerujesz profesjonalną fakturę na życzenie swojego klienta.</dd>
          </dl>
          <dl
            class="flex flex-col gap-2 px-4 py-6 transition-all border-2 cursor-default hover:scale-105 rounded-xl border-gray-600/20 hover:border-emerald-600/50 hover:shadow-xl hover:shadow-emerald-600/10">
            <dt class="text-xl font-bold">Analizy i raporty</dt>
            <dd>Otrzymuj czytelne podsumowania, które pomogą Ci lepiej planować i rozwijać swoją działalność.</dd>
          </dl>
        </div>
      </section>
      <section class="py-8" id="dzialalnosc-nierejestrowana-co-to">
        <h3 class="mb-6 text-2xl font-bold leading-none tracking-tight text-gray-900 lg:text-3xl">
          Przemyślana pod kątem osób prowadzących działalność nierejestrowaną.
        </h3>
        <p class="mb-2 text-lg">
          Działalność nierejestrowana to możliwość prowadzenia firmy bez rejestracji w urzędach, bez ZUSU i płacenia ksiegowemu.
        </p>
        <p class="mb-2 text-lg">
          Idealny wybór, jeśli zarabiasz na swojej pasji lub hobbystycznych projektach, lub chcesz wypróbować swój pomysł przed rejestracją firmy.
        </p>
      </section>
    </main>
  </div>
  <footer class="py-8">
    <div class="text-center text-gray-400">
      &copy; {{ date('Y') }}
      <a href="{{ url('/') }}" class="hover:underline">Mój Mały Biznes</a>
      &middot;
      made by <a href="https://quiksite.pl" class="hover:underline">Quiksite</a>
    </div>
  </footer>
</body>

</html>
