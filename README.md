<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Logo Laravel"></a></p>

<p align="center">
<a href="https://github.com/patimio66/mojmalybiznes/actions"><img src="https://github.com/patimio66/mojmalybiznes/actions/workflows/laravel.yml/badge.svg" alt="Status kompilacji"></a>
</p>

## O projekcie

Mój Mały Biznes to proste narzędzie stworzone z myślą o nierejestrowanych przedsiębiorcach. Taką działalność można nazywać działalnością nierejestrowaną, lub działalnością nierejestrową. Będziemy posługiwać się oboma pojęciami zamiennie.

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

Aby zainstalować ten projekt Laravel lokalnie, wykonaj poniższe kroki:

1. Sklonuj repozytorium:

    ```sh
    git clone https://github.com/yourusername/mojmalybiznes.git
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

## Licencja

Treści zawarte w aplikacji są dostępne na licencji [Creative Commons BY-NC 4.0](https://creativecommons.org/licenses/by-nc/4.0/).
