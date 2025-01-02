---
group: Struktura danych
title: Wydatki
icon: heroicon-o-arrow-trending-down
---

Zasób umożliwia zarządzanie wydatkami w systemie.

# Pola formularza

- **Kontrahent**: Wybierz kontrahenta z listy lub dodaj nowego. Pole wymagane.
- **Tytuł transakcji**: Krótki opis transakcji. Pole wymagane.
- **Pozycje**: Lista pozycji wchodzących w skład transakcji, każda z nich zawiera:
  - **Tytuł pozycji**: Nazwa pozycji. Pole wymagane.
  - **Ilość**: Ilość pozycji. Pole wymagane.
  - **Jednostka miary**: Jednostka miary dla ilości. Pole wymagane.
  - **Kwota**: Cena jednostkowa pozycji. Pole wymagane.
  - **Suma**: Łączna kwota za pozycję (ilość * kwota). Pole automatycznie wyliczane.
- **Suma transakcji**: Łączna kwota za wszystkie pozycje. Pole automatycznie wyliczane.
- **Data transakcji**: Data, kiedy transakcja miała miejsce. Pole wymagane.
- **Notatka**: Prywatna notatka dotycząca transakcji, która nie pojawi się w raportach.
- **Zapłacono całą kwotę**: Przełącznik wskazujący, czy cała kwota została zapłacona.

# Do czego służy
Wydatki pozwalają na rejestrowanie i śledzenie wszystkich transakcji wydatkowych w systemie. Można je powiązać z kontrahentami oraz fakturami, co ułatwia zarządzanie finansami firmy.

> ⚠️ **Uwaga:** Usunięcie wydatku powoduje również automatyczne usunięcie wszystkich przypisanych do niego pozycji oraz powiązanych faktur. Operacja ta jest nieodwracalna!
