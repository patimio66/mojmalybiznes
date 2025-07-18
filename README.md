# ⚠️ DEPRECATED

### ⚠️ W związku z planowanym wprowadzeniem KSEF w lutym 2026 roku, z powodu braku zasobów do dalszego rozwoju (i możliwości dostosowania się do wymagań KSEF) jesteśmy zmuszeni zakończyć działalnie aplikacji. Aby umożliwić migrację do innej platformy, Mój Mały Biznes będzie dostępny do 30 września 2025. Dziękujemy za zaufanie. ⚠️




<a href="https://github.com/patimio66/mojmalybiznes/actions"><img src="https://github.com/patimio66/mojmalybiznes/actions/workflows/laravel.yml/badge.svg" alt="Status kompilacji"></a>

## O projekcie

[Mój Mały Biznes](https://mojmalybiznes.pl) to proste narzędzie stworzone z myślą o nierejestrowanych przedsiębiorcach.

Jej głównym celem jest pomoc w prowadzeniu jednolitej ewidencji sprzedaży, rejestrowania zakupów (w celu wyliczenia dochodu) oraz generowania faktur sprzedaży, czyli wszystko to, co potrzebne jest w prowadzeniu działalności nierejestrowanej.

### Co robi aplikacja

-   ✅ Pilnuje miesięcznego limitu przychodów
-   ✅ Pomaga w prowadzeniu ewidencji sprzedaży
-   ✅ Pozwala na rejestrowanie kosztów
-   ✅ Za jej pomocą wygenerujesz faktury
-   ✅ Zawiera podsumowania i narzędzia do raportowania

### Czego nie robi aplikacja

-   ❌ Sprawdza poprawność faktur, usług czy produktów pod względem treści
-   ❌ Sprawdza legalność prowadzonej działalności
-   ❌ Nie pilnuje limitu zwolnienia z posiadania kasy fiskalnej (jeszcze)

Ostatecznie to na użytkowniku spoczywa odpowiedzialność prawidłowego rozliczenia się z Urzędem Skarbowym. Dajemy narzędzie, które ma to ułatwić.

## Instalacja

Uwaga. Do prawidłowego działania aplikacji wymagana jest biblioteka [wkhtmltopdf](https://wkhtmltopdf.org/downloads.html).

Aby zainstalować projekt lokalnie, wykonaj poniższe kroki:

1. Sklonuj repozytorium:

    ```sh
    git clone https://github.com/patimio66/mojmalybiznes.git
    cd mojmalybiznes
    ```

2. Zainstaluj zależności:

    ```sh
    composer install
    npm install
    npm run dev
    ```

3. Skonfiguruj plik `.env`:

    ```sh
    cp .env.example .env
    php artisan key:generate
    ```

4. Uruchom migracje bazy danych:

    ```sh
    php artisan migrate
    ```

5. Uruchom serwer deweloperski:
    ```sh
    php artisan serve
    ```

Teraz możesz uzyskać dostęp do aplikacji pod adresem `http://localhost:8000`.

## Luki w zabezpieczeniach

Jeśli odkryjesz lukę w zabezpieczeniach w Laravel, wyślij e-mail na adres [kontakt@quiksite.pl](mailto:kontakt@quiksite.pl). Wszystkie luki w zabezpieczeniach zostaną sprawdzone tak szybko, jak to możliwe. W temacie maila dodaj \[Security\] lub \[Bezpieczeństwo\].
