# Zrealizowane zadania

## 1. Podstawowe funkcjonalności

- **CRUD zadań** z poziomu panelu użytkownika.
  - Zadania zawierają: tytuł, opis, priorytet, status, termin oraz integrację z Google Calendar.

## 2. Filtrowanie i sortowanie

- Filtrowanie zadań po:
  - statusie,
  - priorytecie,
  - zakresie dat.
- Obsługa daty i godziny przy wyborze terminu wykonania (`due_date_from`, `due_date_to`).
- Sortowanie zadań od rana do wieczora w przypadku wybranej daty.

## 3. Historia zmian

- Każda zmiana zadania zapisywana jest w osobnej tabeli z widokiem historii:
  - Historia ogólna
  - Historia konkretnego zadania

## 4. Google Calendar

- Możliwość podłączenia konta Google.
- Synchronizacja zadań z Google Calendar (jeśli konto podłączone).
- Informacja wizualna o statusie połączenia.
- Opcje: **Połącz**, **Wyloguj**, **Udostępnij w Google Calendar**.

## 5. Udostępnianie zadań

- Publiczny link do zadania:  
  `/tasks/shared-tasks/{token}`

## 6. Autoryzacja i routing

- Uwierzytelnienie za pomocą **Laravel Breeze**.
- Dostęp do zadań tylko po zalogowaniu.

## 7. Seedy i dane testowe

- Seeder tworzy domyślnego użytkownika:
  - Email: `Mateuszkordysinvestments@gmail.com`
  - Hasło: `Haslo123`
- Automatyczne wygenerowanie 500 przykładowych zadań przypisanych do tego użytkownika.
- **Uwaga:** Wymagane z racji używania tymczasowego SMTP, który może wysyłać tylko na podany e-mail.

---

W projekcie udało się zrealizować kluczowe funkcjonalności zarządzania zadaniami, takie jak filtrowanie, historia zmian oraz integracja z Google Calendar. 
Szczególnie wartościowa była implementacja synchronizacji z kalendarzem Google oraz systemu udostępniania zadań oraz historia edycji.
Z racji że jest to zadanie rekrutacyjne, wszystkie pliki konfiguracyjne są dostępne na GitHub.
