---
group: Struktura danych
title: Faktury
icon: heroicon-o-document-text
---

Zasób umożliwia zarządzanie fakturami w systemie.

# Pola formularza

- **Kontrahent**: Wybierz kontrahenta z listy lub dodaj nowego. Pole wymagane.
- **Numer faktury**: Unikalny numer faktury. Pole wymagane.
- **Pozycje**: Lista pozycji wchodzących w skład faktury, każda z nich zawiera:
  - **Tytuł pozycji**: Nazwa pozycji. Pole wymagane.
  - **Ilość**: Ilość pozycji. Pole wymagane.
  - **Jednostka miary**: Jednostka miary dla ilości. Pole wymagane.
  - **Kwota**: Cena jednostkowa pozycji. Pole wymagane.
  - **Suma**: Łączna kwota za pozycję (ilość * kwota). Pole automatycznie wyliczane.
- **Suma faktury**: Łączna kwota za wszystkie pozycje. Pole automatycznie wyliczane.
- **Data wystawienia**: Data, kiedy faktura została wystawiona. Pole wymagane.
- **Data płatności**: Data, do kiedy faktura powinna zostać opłacona. Pole wymagane.
- **Notatka**: Prywatna notatka dotycząca faktury, która nie pojawi się w raportach.
- **Zapłacono całą kwotę**: Przełącznik wskazujący, czy cała kwota została zapłacona.

# Do czego służy
Faktury pozwalają na rejestrowanie i śledzenie wszystkich transakcji fakturowych w systemie. Można je powiązać z kontrahentami oraz przychodami, co ułatwia zarządzanie finansami firmy.

> ⚠️ **Uwaga:** Usunięcie faktury powoduje również automatyczne usunięcie wszystkich przypisanych do niej pozycji oraz powiązanych przychodów. Operacja ta jest nieodwracalna!
