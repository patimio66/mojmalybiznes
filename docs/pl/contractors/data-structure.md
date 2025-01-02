---
group: Struktura danych
title: Kontrahenci
icon: heroicon-o-building-office-2
---

Zasób umożliwia zarządzanie kontrahentami w systemie.

# Pola formularza

- **Nazwa własna**: Nazwa firmy lub imię i nazwisko kontrahenta. Pole wymagane.
- **Numer podatkowy**: Numer identyfikacji podatkowej (NIP) lub PESEL.
- **Adres**: Adres kontrahenta.
- **Kod pocztowy**: Kod pocztowy w formacie `99-999`.
- **Miasto**: Miasto kontrahenta.
- **Email**: Adres email kontrahenta.
- **Telefon**: Numer telefonu kontrahenta.
- **Notatka**: Prywatna notatka dotycząca kontrahenta, która nie pojawi się w raportach, ani na fakturach.

# Do czego służy
Kontrahentów można wykorzystywać zarówno podczas wprowadzania przychodu, jak i wydatku. Jak sama nazwa wskazuje, jest osoba lub podmiot, z którym zawieramy umowę handlową lub inną transakcję. Kontrahenci mogą być klientami, dostawcami, partnerami biznesowymi i tak dalej.

> ⚠️ **Uwaga:** Usunięcie kontrahenta powoduje również automatyczne usunięcie wszystkich przypisanych do niego zasobów, takich jak przychody, wydatki, czy faktury. Operacja ta jest nieodwracalna!

# Relacje między modelami
Kontrahenci są powiązani z fakturami, przychodami oraz wydatkami. Każdy kontrahent może być przypisany do wielu faktur, przychodów oraz wydatków. Relacje te umożliwiają śledzenie transakcji każdego z kontrahentów oraz spójność danych.
